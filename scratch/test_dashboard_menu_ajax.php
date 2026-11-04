<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Modules\Dashboard\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

echo "=== MEMULAI TEST DASHBOARD MENU & AJAX PAGINATION ===\n";

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

// 1. Mock or get first user
$user = User::first();
if (!$user) {
    $user = new User();
    $user->id = 1;
    $user->name = 'Test User';
}
Auth::login($user);

$controller = new DashboardController();

// ==========================================
// TEST 1: Initial Page Load (Non-AJAX)
// ==========================================
echo "\n--- Testing Initial Page Load (Non-AJAX) ---\n";
$req1 = Request::create('/dashboard', 'GET');
$req1->setUserResolver(fn() => $user);

$response1 = $controller->index($req1);
assertCondition('Response is a View', $response1 instanceof \Illuminate\View\View);

$viewData = $response1->getData();
assertCondition('View has menus collection', isset($viewData['menus']));
assertCondition('View has menuPagination', isset($viewData['menuPagination']));
assertCondition('First page has exactly 8 items (proportional limit)', $viewData['menus']->count() === 8);
assertCondition('Total items equals 11', $viewData['menuPagination']->total() === 11);
assertCondition('First page has more pages (hasMorePages is true)', $viewData['menuPagination']->hasMorePages() === true);
assertCondition('Current page is 1', $viewData['menuPagination']->currentPage() === 1);
assertCondition('Last page is 2', $viewData['menuPagination']->lastPage() === 2);

// Render the view to check HTML output
$renderedHtml = $response1->render();
assertCondition('HTML contains menu-grid-container', strpos($renderedHtml, 'id="menu-grid-container"') !== false);
assertCondition('HTML contains btn-menu-next with data-page="2"', strpos($renderedHtml, 'id="btn-menu-next"') !== false && strpos($renderedHtml, 'data-page="2"') !== false);
assertCondition('HTML contains pagination bar', strpos($renderedHtml, 'class="menu-pagination-bar') !== false);
assertCondition('HTML renders card for first item (Keuangan)', strpos($renderedHtml, 'Keuangan') !== false);

// ==========================================
// TEST 2: Page 2 AJAX Request
// ==========================================
echo "\n--- Testing Page 2 via AJAX ---\n";
$req2 = Request::create('/dashboard?page=2&ajax_menus=1', 'GET');
$req2->headers->set('X-Requested-With', 'XMLHttpRequest');
$req2->headers->set('Accept', 'application/json');
$req2->setUserResolver(fn() => $user);

$response2 = $controller->index($req2);
assertCondition('AJAX Response is JsonResponse', $response2 instanceof \Illuminate\Http\JsonResponse);

$json = $response2->getData(true);
assertCondition('JSON status is success', isset($json['status']) && $json['status'] === 'success');
assertCondition('JSON has html property', !empty($json['html']));
assertCondition('JSON pagination current_page is 2', $json['pagination']['current_page'] === 2);
assertCondition('JSON pagination has_more is false', $json['pagination']['has_more'] === false);
assertCondition('JSON pagination has_prev is true', $json['pagination']['has_prev'] === true);
assertCondition('JSON pagination from is 9', $json['pagination']['from'] === 9);
assertCondition('JSON pagination to is 11', $json['pagination']['to'] === 11);
assertCondition('JSON pagination total is 11', $json['pagination']['total'] === 11);

// Verify page 2 HTML contains expected items
assertCondition('HTML for page 2 contains Akuntansi', strpos($json['html'], 'Akuntansi') !== false);
assertCondition('HTML for page 2 contains Budgeting', strpos($json['html'], 'Budgeting') !== false);
assertCondition('HTML for page 2 contains Alumni', strpos($json['html'], 'Alumni') !== false);
assertCondition('HTML for page 2 does NOT contain Keuangan (page 1 item)', strpos($json['html'], 'Keuangan') === false);

echo "\n==========================================\n";
echo "HASIL TEST: $passCount PASS, $failCount FAIL\n";
echo "==========================================\n";

if ($failCount > 0) {
    exit(1);
}

