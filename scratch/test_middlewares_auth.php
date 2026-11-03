<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Http\Middleware\EnsureSuperUser;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\AclMiddleware;
use App\Http\Middleware\AccessCheck;
use App\Http\Middleware\CheckMenuPermission;
use App\Http\Middleware\Proctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

echo "=== MEMULAI TEST MIDDLEWARE & AUTH INTEGRATION ===\n";

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

// 1. Mock Next Closure
$next = function ($request) {
    return new \Illuminate\Http\Response('OK', 200);
};

// 2. Concrete Mock Classes
class MockSuperUser extends User {
    protected $table = 'users';
    public $id = 1;
    public function isSuperUser(): bool { return true; }
    public function hasAnyRole($roles, $orgId = null): bool { return true; }
    public function hasRole($role, $orgId = null): bool { return true; }
    public function hasMenuPermission($menu, $action = 'is_view', $orgId = null): bool { return true; }
}

class MockRegularUser extends User {
    protected $table = 'users';
    public $id = 2;
    public function getActiveOrganizationIdAttribute() { return 1; }
    public function isSuperUser(): bool { return false; }
    public function hasAnyRole($roles, $orgId = null): bool {
        $roles = (array)$roles;
        return in_array('admin-sekolah', $roles);
    }
    public function hasRole($role, $orgId = null): bool {
        return $role === 'admin-sekolah';
    }
    public function hasMenuPermission($menu, $action = 'is_view', $orgId = null): bool {
        return $menu === 'user' && $action === 'is_view';
    }
}

$superUser = new MockSuperUser();
$regularUser = new MockRegularUser();

// ==========================================
// TEST 1: EnsureSuperUser
// ==========================================
echo "\n--- Testing EnsureSuperUser ---\n";
$mw = new EnsureSuperUser();

// Unauthenticated
$req = Request::create('/admin/something', 'GET');
$res = $mw->handle($req, $next);
assertCondition('EnsureSuperUser redirects unauthenticated user', $res->isRedirection());

// Regular user (should throw 403)
$req = Request::create('/admin/something', 'GET');
$req->setUserResolver(fn() => $regularUser);
try {
    $mw->handle($req, $next);
    assertCondition('EnsureSuperUser denies regular user', false);
} catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
    assertCondition('EnsureSuperUser denies regular user with 403', $e->getStatusCode() === 403);
}

// Superuser (should pass)
$req = Request::create('/admin/something', 'GET');
$req->setUserResolver(fn() => $superUser);
$res = $mw->handle($req, $next);
assertCondition('EnsureSuperUser allows super user', $res->getStatusCode() === 200);

// ==========================================
// TEST 2: RoleMiddleware
// ==========================================
echo "\n--- Testing RoleMiddleware ---\n";
$mwRole = new RoleMiddleware();

// Unauthenticated
$req = Request::create('/admin/something', 'GET');
$res = $mwRole->handle($req, $next, 'admin-sekolah');
assertCondition('RoleMiddleware redirects unauthenticated user', $res->isRedirection());

// Super user passes even for non-matching role
$req = Request::create('/admin/something', 'GET');
$req->setUserResolver(fn() => $superUser);
$res = $mwRole->handle($req, $next, 'some-random-role');
assertCondition('RoleMiddleware allows super user for any role', $res->getStatusCode() === 200);

// Regular user with matching role
$req = Request::create('/admin/something', 'GET');
$req->setUserResolver(fn() => $regularUser);
$res = $mwRole->handle($req, $next, 'admin-sekolah');
assertCondition('RoleMiddleware allows user with matching role', $res->getStatusCode() === 200);

// Regular user with non-matching role
$req = Request::create('/admin/something', 'GET');
$req->setUserResolver(fn() => $regularUser);
try {
    $mwRole->handle($req, $next, 'teacher,student');
    assertCondition('RoleMiddleware denies user without required role', false);
} catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
    assertCondition('RoleMiddleware denies user without role with 403', $e->getStatusCode() === 403);
}

// ==========================================
// TEST 3: AclMiddleware (Gate check bug fix verification)
// ==========================================
echo "\n--- Testing AclMiddleware ---\n";
$mwAcl = new AclMiddleware();

Gate::define('test-allow-ability', function ($user) {
    return true;
});
Gate::define('test-deny-ability', function ($user) {
    return false;
});

// Superuser bypass
$req = Request::create('/test', 'GET');
$req->setUserResolver(fn() => $superUser);
$res = $mwAcl->handle($req, $next, 'test-deny-ability');
assertCondition('AclMiddleware allows super user even if ability is denied', $res->getStatusCode() === 200);

// Regular user with allowed gate ability
$req = Request::create('/test', 'GET');
$req->setUserResolver(fn() => $regularUser);
$res = $mwAcl->handle($req, $next, 'test-allow-ability');
assertCondition('AclMiddleware allows regular user when Gate returns true (previously broken!)', $res->getStatusCode() === 200);

// Regular user with denied gate ability
$req = Request::create('/test', 'GET');
$req->setUserResolver(fn() => $regularUser);
try {
    $mwAcl->handle($req, $next, 'test-deny-ability');
    assertCondition('AclMiddleware denies regular user when Gate returns false', false);
} catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
    assertCondition('AclMiddleware throws 403 when Gate returns false', $e->getStatusCode() === 403);
}

// ==========================================
// TEST 4: CheckMenuPermission
// ==========================================
echo "\n--- Testing CheckMenuPermission ---\n";
$mwMenu = new CheckMenuPermission();

// Superuser bypass
$req = Request::create('/user', 'GET');
$req->setUserResolver(fn() => $superUser);
$res = $mwMenu->handle($req, $next, 'user', 'is_view');
assertCondition('CheckMenuPermission allows super user', $res->getStatusCode() === 200);

// Regular user with permission
$req = Request::create('/user', 'GET');
$req->setUserResolver(fn() => $regularUser);
$res = $mwMenu->handle($req, $next, 'user', 'is_view');
assertCondition('CheckMenuPermission allows regular user with permission', $res->getStatusCode() === 200);

// Regular user without delete permission
$req = Request::create('/user', 'DELETE');
$req->setUserResolver(fn() => $regularUser);
try {
    $mwMenu->handle($req, $next, 'user', 'is_delete');
    assertCondition('CheckMenuPermission denies unauthorized action', false);
} catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
    assertCondition('CheckMenuPermission denies unauthorized action with 403', $e->getStatusCode() === 403);
}

// ==========================================
// TEST 5: AccessCheck Non-GET Response
// ==========================================
echo "\n--- Testing AccessCheck ---\n";
$mwAccess = new AccessCheck();

// Regular user on non-GET denied
$req = Request::create('/restricted-post', 'POST');
$req->setUserResolver(fn() => $regularUser);
try {
    $mwAccess->handle($req, $next, 'non-existent-role');
    assertCondition('AccessCheck denies POST without role', false);
} catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
    assertCondition('AccessCheck throws 403 for POST (not returning null!)', $e->getStatusCode() === 403);
}

// ==========================================
// TEST 6: Proctor
// ==========================================
echo "\n--- Testing Proctor ---\n";
$mwProctor = new Proctor();

// Super user passes
$req = Request::create('/proctor', 'GET');
$req->setUserResolver(fn() => $superUser);
$res = $mwProctor->handle($req, $next);
assertCondition('Proctor allows super user', $res->getStatusCode() === 200);

// Regular user without proctor role
$req = Request::create('/proctor', 'GET');
$req->setUserResolver(fn() => $regularUser);
try {
    $mwProctor->handle($req, $next);
    assertCondition('Proctor denies user without proctor role', false);
} catch (\Symfony\Component\HttpKernel\Exception\HttpException $e) {
    assertCondition('Proctor denies user without proctor role with 403', $e->getStatusCode() === 403);
}

echo "\n==========================================\n";
echo "HASIL TEST: $passCount PASS, $failCount FAIL\n";
echo "==========================================\n";

if ($failCount > 0) {
    exit(1);
}
