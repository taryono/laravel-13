<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Core\Menu\MenuRepository;
use App\Core\Menu\MenuService;
use App\Core\Menu\MenuTreeBuilder;
use App\Models\User;
use App\Modules\Dashboard\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== MEMULAI TEST MENU CATEGORY FILTER & REPOSITORY ===\n";

$passCount = 0;
$failCount = 0;

function assertCondition($description, $condition) {
    global $passCount, $failCount;
    if ($condition) {
        echo " [PASS] $description\n";
        $passCount++;
    } else {
        echo " [FAIL] $description\n";
        $failCount++;
    }
}

$user = User::first();
if (!$user) {
    $user = new User();
    $user->id = 1;
    $user->name = 'Test User';
}
Auth::login($user);

$repo = new MenuRepository();

// ==========================================
// TEST 1: MenuRepository::getMenuIdsForCategory
// ==========================================
echo "\n--- Testing MenuRepository::getMenuIdsForCategory ---\n";

// Category 2: Data Akademik (roots: 30, 40, 50, 130, 140, 200)
$menuIdsCat2 = $repo->getMenuIdsForCategory(2);
assertCondition('Category 2 returns array of IDs', is_array($menuIdsCat2));
assertCondition('Category 2 contains root menu ID 30 (academic)', in_array(30, $menuIdsCat2));
assertCondition('Category 2 contains child menu ID 31 (academic-year)', in_array(31, $menuIdsCat2));
assertCondition('Category 2 contains child menu ID 32 (academic-semester)', in_array(32, $menuIdsCat2));
assertCondition('Category 2 contains root menu ID 40 (students)', in_array(40, $menuIdsCat2));
assertCondition('Category 2 contains child menu ID 41 (student)', in_array(41, $menuIdsCat2));
assertCondition('Category 2 does NOT contain Finance menu 90', !in_array(90, $menuIdsCat2));

// Category 1: Keuangan (root: 90)
$menuIdsCat1 = $repo->getMenuIdsForCategory(1);
assertCondition('Category 1 contains root menu ID 90 (finance)', in_array(90, $menuIdsCat1));
assertCondition('Category 1 contains child menu ID 91 (fee-master)', in_array(91, $menuIdsCat1));
assertCondition('Category 1 does NOT contain Academic menu 30', !in_array(30, $menuIdsCat1));

// ==========================================
// TEST 2: MenuRepository::getByCategory / all($categoryId)
// ==========================================
echo "\n--- Testing MenuRepository::getByCategory ---\n";
$menusCat2 = $repo->getByCategory(2);
assertCondition('getByCategory returns Collection', $menusCat2 instanceof \Illuminate\Support\Collection);
assertCondition('All items in getByCategory(2) are from category 2 hierarchy', $menusCat2->isNotEmpty());
$hasAcademic = $menusCat2->contains(fn($m) => $m->id === 30 || $m->id === 31);
$hasFinance = $menusCat2->contains(fn($m) => $m->id === 90);
assertCondition('getByCategory(2) contains academic items', $hasAcademic);
assertCondition('getByCategory(2) excludes finance items', !$hasFinance);

// ==========================================
// TEST 3: MenuService Integration with session
// ==========================================
echo "\n--- Testing MenuService with session('active_menu_category_id') ---\n";
$service = app(MenuService::class);

// Without category filter (all menus)
session()->forget('active_menu_category_id');
$splitAll = $service->getSplitMenu('/dashboard', $user);
$allSidebarMenus = $splitAll['menu'];
assertCondition('Without session filter, sidebar has menus', $allSidebarMenus->isNotEmpty());

// With category filter (Category 2: Data Akademik)
session(['active_menu_category_id' => 2]);
$splitCat2 = $service->getSplitMenu('/dashboard', $user);
$cat2SidebarMenus = $splitCat2['menu'];
assertCondition('With category 2 in session, sidebar returns menus', $cat2SidebarMenus->isNotEmpty());
assertCondition('Category 2 sidebar count is subset of all menus', $cat2SidebarMenus->count() < $allSidebarMenus->count());

$sidebarHasAcademic = $cat2SidebarMenus->contains(fn($m) => $m->id === 30 || $m->name === 'academic');
$sidebarHasFinance = $cat2SidebarMenus->contains(fn($m) => $m->id === 90 || $m->name === 'finance');
assertCondition('Category 2 sidebar contains academic menu', $sidebarHasAcademic);
assertCondition('Category 2 sidebar excludes finance menu', !$sidebarHasFinance);

// ==========================================
// TEST 4: DashboardController::selectCategory endpoint
// ==========================================
echo "\n--- Testing selectCategory Endpoint ---\n";
$controller = new DashboardController();

// AJAX POST to select category 5
$postReq = Request::create('/dashboard/select-category', 'POST', ['menu_category_id' => 5]);
$postReq->headers->set('X-Requested-With', 'XMLHttpRequest');
$postRes = $controller->selectCategory($postReq);
assertCondition('selectCategory AJAX response is JsonResponse', $postRes instanceof \Illuminate\Http\JsonResponse);
assertCondition('Session active_menu_category_id is updated to 5', session('active_menu_category_id') === 5);

// Reset category via 'all'
$resetReq = Request::create('/dashboard/select-category/all', 'GET');
$resetRes = $controller->selectCategory($resetReq, 'all');
assertCondition('selectCategory with "all" clears active_menu_category_id from session', session('active_menu_category_id') === null);

echo "\n==========================================\n";
echo "HASIL TEST: $passCount PASS, $failCount FAIL\n";
echo "==========================================\n";

if ($failCount > 0) {
    exit(1);
}

