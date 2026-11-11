<?php

namespace Tests\Feature;

use App\Http\Middleware\AccessCheck;
use App\Http\Middleware\AclMiddleware;
use App\Http\Middleware\AdminPpdbAuth;
use App\Http\Middleware\ApplicantAuth;
use App\Http\Middleware\CheckMenuPermission;
use App\Http\Middleware\EnsureSessionIsActive;
use App\Http\Middleware\EnsureSuperUser;
use App\Http\Middleware\EnsureTenantRole;
use App\Http\Middleware\ParentAuth;
use App\Http\Middleware\Proctor;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\ResolveActiveOrganization;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Middleware\StudentAuth;
use App\Models\User;
use App\Providers\AuthServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Models\Applicant;
use Tests\TestCase;

class MiddlewareConfigurationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'cache.default' => 'array',
            'session.driver' => 'array',
        ]);
        DB::purge('sqlite');
        Cache::flush();

        $this->createSchema();
        $this->registerTestRoutes();
    }

    protected function createSchema(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->unsignedBigInteger('current_organization_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->boolean('isCheckingRole')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('code')->nullable();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('organization_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('user_id');
            $table->boolean('is_default')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->default(1);
            $table->string('code')->nullable();
            $table->string('name');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->string('route')->nullable();
            $table->string('url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('menu_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->unsignedBigInteger('role_id');
            $table->boolean('is_view')->default(false);
            $table->boolean('is_create')->default(false);
            $table->boolean('is_edit')->default(false);
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id')->nullable();
            $table->unsignedBigInteger('menu_id')->nullable();
            $table->string('name');
            $table->string('display_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('action_role', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->unsignedBigInteger('role_id');
            $table->integer('is_allowed')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('action_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('action_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('registration_number')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('canteen_tenant_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('canteen_tenant_id');
            $table->unsignedBigInteger('user_id');
            $table->string('role')->default('owner');
            $table->timestamps();
        });

        DB::table('organizations')->insert([
            ['id' => 1, 'code' => 'ORG1', 'name' => 'Org Satu', 'is_active' => 1],
            ['id' => 2, 'code' => 'ORG2', 'name' => 'Org Dua', 'is_active' => 1],
        ]);
    }

    protected function registerTestRoutes(): void
    {
        Route::middleware('web')->group(function () {
            Route::get('/_test/superuser', fn () => response()->json(['ok' => true]))->middleware('superuser')->name('test.superuser');
            Route::get('/_test/access-role', fn () => response()->json(['ok' => true]))->middleware('access:guru,wali-kelas')->name('test.access.role');
            Route::get('/_test/access-gate', fn () => response()->json(['ok' => true]))->middleware('access')->name('role.create');
            Route::get('/_test/acl-action', fn () => response()->json(['ok' => true]))->middleware('acl:,user-manage')->name('test.acl.action');
            Route::get('/_test/acl-policy/{id}', fn () => response()->json(['ok' => true]))->middleware('acl:update,,App\Models\User')->name('test.acl.policy');
            Route::get('/_test/acl-class-policy', fn () => response()->json(['ok' => true]))->middleware('acl:viewAny,,App\Models\User')->name('test.acl.class_policy');
            Route::get('/_test/proctor', fn () => response()->json(['ok' => true]))->middleware('proctor')->name('test.proctor');
            Route::get('/_test/menu-explicit', fn () => response()->json(['ok' => true]))->middleware('menu.permission:roles,create')->name('test.menu.explicit');
            Route::get('/_test/menu-auto', fn () => response()->json(['ok' => true]))->middleware('menu.permission')->name('role.edit');
            Route::get('/_test/role-mw', fn () => response()->json(['ok' => true]))->middleware('role:guru|kepala-sekolah')->name('test.role');
            Route::get('/_test/role-empty', fn () => response()->json(['ok' => true]))->middleware('role')->name('test.role.empty');

            Route::get('/_test/student-portal', fn () => response()->json(['ok' => true]))->middleware('student.auth')->name('test.student');
            Route::get('/student/protected-check', fn () => response()->json(['ok' => true]))->middleware('auth')->name('test.student.guest_check');
            Route::get('/_test/parent-portal', fn () => response()->json(['ok' => true]))->middleware('parent.auth')->name('test.parent');
            Route::get('/_test/applicant-portal', fn () => response()->json(['ok' => true]))->middleware('applicant.auth')->name('test.applicant');
            Route::get('/_test/guest-applicant', fn () => response()->json(['guest' => true]))->middleware('guest:applicant')->name('test.guest.applicant');

            Route::get('/_test/canteen/{tenant}/cashier', fn (\Illuminate\Http\Request $request) => response()->json([
                'tenant_role' => $request->attributes->get('tenant_role'),
            ]))->middleware(['auth', 'tenant.role:owner,cashier'])->name('test.canteen.cashier');

            Route::get('/_test/canteen/{tenant}/owner', fn (\Illuminate\Http\Request $request) => response()->json([
                'tenant_role' => $request->attributes->get('tenant_role'),
            ]))->middleware('tenant.role:owner')->name('test.canteen.owner');

            Route::get('/_test/session-ping', fn () => response()->json(['ok' => true, 'org' => session('active_organization_id')]))->name('test.session.ping');
            Route::get('/_test/org-check', fn () => response()->json([
                'id' => session('active_organization_id'),
                'obj_id' => session('active_organization')?->id,
                'shared_id' => view()->shared('activeOrganization')?->id,
            ]))->name('test.org.check');
        });
    }

    protected function makeUser(string $email, array $orgIds = [1], array $roles = []): User
    {
        $user = User::create([
            'name' => explode('@', $email)[0],
            'email' => $email,
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
        ]);

        foreach ($orgIds as $index => $orgId) {
            DB::table('organization_user')->insert([
                'organization_id' => $orgId,
                'user_id' => $user->id,
                'is_default' => $index === 0 ? 1 : 0,
                'is_active' => 1,
            ]);
        }

        foreach ($roles as $roleSpec) {
            $code = $roleSpec['code'];
            $name = $roleSpec['name'] ?? $code;
            $orgId = $roleSpec['org_id'] ?? 1;

            $roleId = DB::table('roles')->where('code', $code)->value('id');
            if (! $roleId) {
                $roleId = DB::table('roles')->insertGetId([
                    'organization_id' => 1,
                    'code' => $code,
                    'name' => $name,
                    'is_active' => 1,
                ]);
            }

            DB::table('role_user')->insert([
                'user_id' => $user->id,
                'role_id' => $roleId,
                'organization_id' => $orgId,
                'is_default' => 1,
            ]);
        }

        return $user;
    }

    public function test_bootstrap_aliases_and_web_group_middlewares_are_registered(): void
    {
        $router = app('router');
        $aliases = $router->getMiddleware();
        $groups = $router->getMiddlewareGroups();

        $expectedAliases = [
            'superuser' => EnsureSuperUser::class,
            'access' => AccessCheck::class,
            'acl' => AclMiddleware::class,
            'proctor' => Proctor::class,
            'menu.permission' => CheckMenuPermission::class,
            'guest' => RedirectIfAuthenticated::class,
            'applicant.auth' => ApplicantAuth::class,
            'student.auth' => StudentAuth::class,
            'parent.auth' => ParentAuth::class,
            'ppdb-admin.auth' => AdminPpdbAuth::class,
            'tenant.role' => EnsureTenantRole::class,
            'role' => RoleMiddleware::class,
        ];

        foreach ($expectedAliases as $alias => $class) {
            $this->assertArrayHasKey($alias, $aliases);
            $this->assertSame($class, $aliases[$alias]);
        }

        $this->assertContains(EnsureSessionIsActive::class, $groups['web']);
        $this->assertContains(ResolveActiveOrganization::class, $groups['web']);
    }

    public function test_superuser_middleware(): void
    {
        $regular = $this->makeUser('regular@example.com', [1], [['code' => 'guru', 'name' => 'Guru']]);
        $super = $this->makeUser('super@example.com', [1], [['code' => 'super-admin', 'name' => 'Super Administrator']]);

        $this->getJson('/_test/superuser')->assertStatus(401);
        $this->get('/_test/superuser')->assertRedirect(route('login'));

        $this->actingAs($regular)->getJson('/_test/superuser')->assertStatus(403);
        $this->actingAs($regular)->get('/_test/superuser')->assertRedirect(route('dashboard'));

        $this->actingAs($super)->getJson('/_test/superuser')->assertOk();
    }

    public function test_role_and_access_check_middlewares_work_with_scoped_organizations(): void
    {
        $guru = $this->makeUser('guru@example.com', [1], [['code' => 'guru', 'name' => 'Guru']]);
        $siswa = $this->makeUser('siswa@example.com', [1], [['code' => 'siswa', 'name' => 'Siswa']]);
        $super = $this->makeUser('super2@example.com', [1], [['code' => 'super-admin', 'name' => 'Super Administrator']]);

        $this->actingAs($guru)->getJson('/_test/role-mw')->assertOk();
        $this->actingAs($siswa)->getJson('/_test/role-mw')->assertStatus(403);
        $this->actingAs($siswa)->getJson('/_test/role-empty')->assertOk();
        $this->actingAs($super)->getJson('/_test/role-mw')->assertOk();

        $this->actingAs($guru)->getJson('/_test/access-role')->assertOk();
        $this->actingAs($siswa)->getJson('/_test/access-role')->assertStatus(403);
        $this->actingAs($super)->getJson('/_test/access-role')->assertOk();
    }

    public function test_access_check_and_menu_permission_work_with_menu_gates_and_route_matching(): void
    {
        $menuId = DB::table('menus')->insertGetId([
            'organization_id' => 1,
            'name' => 'role',
            'slug' => 'roles',
            'route' => 'role.index',
            'is_active' => 1,
        ]);

        $guru = $this->makeUser('guru-menu@example.com', [1], [['code' => 'guru', 'name' => 'Guru']]);
        $guruRoleId = DB::table('roles')->where('code', 'guru')->value('id');

        DB::table('menu_role')->insert([
            'menu_id' => $menuId,
            'role_id' => $guruRoleId,
            'is_view' => 1,
            'is_create' => 1,
            'is_edit' => 0,
            'is_delete' => 0,
        ]);

        Cache::flush();
        (new AuthServiceProvider($this->app))->boot();

        // role.create resolves to menu slug "roles" + action "create"
        $this->actingAs($guru)->getJson('/_test/access-gate')->assertOk();
        $this->actingAs($guru)->getJson('/_test/menu-explicit')->assertOk();

        // role.edit resolves to menu slug "roles" + action "edit" (is_edit = 0 -> denied)
        $this->actingAs($guru)->getJson('/_test/menu-auto')->assertStatus(403);
    }

    public function test_acl_middleware_handles_actions_gates_and_policies_without_sql_errors(): void
    {
        $actionId = DB::table('actions')->insertGetId([
            'organization_id' => 1,
            'name' => 'user-manage',
            'is_active' => 1,
        ]);

        $allowedUser = $this->makeUser('acl-ok@example.com', [1], [['code' => 'admin-hr', 'name' => 'Admin HR']]);
        $deniedUser = $this->makeUser('acl-no@example.com', [1], [['code' => 'guru', 'name' => 'Guru']]);
        $allowedUser->department_id = 10;
        $allowedUser->save();

        $targetSameDept = $this->makeUser('target-same@example.com', [1]);
        $targetSameDept->department_id = 10;
        $targetSameDept->save();

        $targetOtherDept = $this->makeUser('target-other@example.com', [1]);
        $targetOtherDept->department_id = 20;
        $targetOtherDept->save();

        $hrRoleId = DB::table('roles')->where('code', 'admin-hr')->value('id');
        $guruRoleId = DB::table('roles')->where('code', 'guru')->value('id');

        DB::table('action_role')->insert([
            ['action_id' => $actionId, 'role_id' => $hrRoleId, 'is_allowed' => 1],
            ['action_id' => $actionId, 'role_id' => $guruRoleId, 'is_allowed' => 0],
        ]);

        $this->actingAs($allowedUser)->getJson('/_test/acl-action')->assertOk();
        $this->actingAs($deniedUser)->getJson('/_test/acl-action')->assertStatus(403);

        // Policy instance check via UserPolicy::update
        $this->actingAs($allowedUser)->getJson('/_test/acl-policy/' . $targetSameDept->id)->assertOk();
        $this->actingAs($allowedUser)->getJson('/_test/acl-policy/' . $targetOtherDept->id)->assertStatus(403);

        // Class-level policy check (UserPolicy::viewAny returns false for non-superuser)
        $this->actingAs($allowedUser)->getJson('/_test/acl-class-policy')->assertStatus(403);
    }

    public function test_proctor_middleware_and_proctoring_dashboard_web_session(): void
    {
        $proctor = $this->makeUser('proctor@example.com', [1], [['code' => 'operator-cbt', 'name' => 'Operator CBT']]);
        $siswa = $this->makeUser('siswa-proctor@example.com', [1], [['code' => 'siswa', 'name' => 'Siswa']]);

        $this->getJson('/_test/proctor')->assertStatus(401);
        $this->actingAs($proctor)->getJson('/_test/proctor')->assertOk();
        $this->actingAs($siswa)->getJson('/_test/proctor')->assertStatus(403);

        $proctorRoute = app('router')->getRoutes()->getByName('proctoring_dashboard.index');
        $this->assertNotNull($proctorRoute);
        $this->assertContains('web', $proctorRoute->gatherMiddleware());
    }

    public function test_student_parent_ppdb_admin_and_applicant_auth_middlewares(): void
    {
        $siswa = $this->makeUser('student1@example.com', [1], [['code' => 'siswa', 'name' => 'Siswa']]);
        $parent = $this->makeUser('parent1@example.com', [1], [['code' => 'orang-tua', 'name' => 'Orang Tua']]);
        $guru = $this->makeUser('guru1@example.com', [1], [['code' => 'guru', 'name' => 'Guru']]);
        $ppdbAdmin = $this->makeUser('ppdbadmin@example.com', [1], [['code' => 'admin-ppdb', 'name' => 'Panitia PPDB']]);

        // StudentAuth allows siswa & orang-tua, rejects guru
        $this->actingAs($siswa)->getJson('/_test/student-portal')->assertOk();
        $this->actingAs($parent)->getJson('/_test/student-portal')->assertOk();
        $this->actingAs($guru)->getJson('/_test/student-portal')->assertStatus(403);

        // ParentAuth allows orang-tua, rejects siswa
        $this->actingAs($parent)->getJson('/_test/parent-portal')->assertOk();
        $this->actingAs($siswa)->getJson('/_test/parent-portal')->assertStatus(403);

        // AdminPpdbAuth allows admin-ppdb, rejects guru, redirects unauthenticated to ppdb-admin.login even on /admin-ppdb root
        $this->get('/admin-ppdb')->assertRedirect(route('ppdb-admin.login'));
        $this->actingAs($guru)->get('/admin-ppdb')->assertStatus(403); // Dashboard itself must return 403, not redirect loop!
        $this->actingAs($guru)->get('/admin-ppdb/verifikasi')->assertRedirect(route('dashboard'));
        $this->actingAs($ppdbAdmin)->getJson('/admin-ppdb/verifikasi')->assertOk();

        // ApplicantAuth with active and inactive applicant
        $activeApplicant = Applicant::create(['name' => 'Calon Aktif', 'is_active' => true]);
        $inactiveApplicant = Applicant::create(['name' => 'Calon Nonaktif', 'is_active' => false]);

        $this->getJson('/_test/applicant-portal')->assertStatus(401);

        Auth::guard('applicant')->login($activeApplicant);
        $this->getJson('/_test/applicant-portal')->assertOk();

        Auth::guard('applicant')->login($inactiveApplicant);
        $this->getJson('/_test/applicant-portal')->assertStatus(403);
        $this->assertFalse(Auth::guard('applicant')->check());
    }

    public function test_guest_redirect_if_authenticated_uses_redirect_users_to_callback(): void
    {
        $ppdbAdmin = $this->makeUser('guest-ppdb@example.com', [1], [['code' => 'admin-ppdb', 'name' => 'Panitia PPDB']]);
        $regular = $this->makeUser('guest-reg@example.com', [1], [['code' => 'guru', 'name' => 'Guru']]);

        // Unauthenticated can open login pages
        $this->get('/login')->assertOk();
        $this->get('/admin-ppdb/login')->assertOk();

        // Authenticated ppdb-admin visiting /admin-ppdb/login goes to ppdb-admin.dashboard (not hardcoded /dashboard!)
        $this->actingAs($ppdbAdmin)->get('/admin-ppdb/login')->assertRedirect(route('ppdb-admin.dashboard'));

        // Authenticated regular user visiting /login goes to dashboard
        $this->actingAs($regular)->get('/login')->assertRedirect(route('dashboard'));

        // Authenticated applicant visiting guest:applicant route redirects via redirectUsersTo
        $applicant = Applicant::create(['name' => 'Guest Applicant', 'is_active' => true]);
        Auth::guard('applicant')->login($applicant);
        $this->get('/_test/guest-applicant')->assertRedirect('/');
    }

    public function test_tenant_role_middleware(): void
    {
        $owner = $this->makeUser('owner@example.com', [1]);
        $cashier = $this->makeUser('cashier@example.com', [1]);
        $outsider = $this->makeUser('outsider@example.com', [1]);

        DB::table('canteen_tenant_users')->insert([
            ['canteen_tenant_id' => 5, 'user_id' => $owner->id, 'role' => 'owner'],
            ['canteen_tenant_id' => 5, 'user_id' => $cashier->id, 'role' => 'cashier'],
        ]);

        // Unauthenticated without auth wrapper returns 401 cleanly (no null->id crash)
        $this->getJson('/_test/canteen/5/owner')->assertStatus(401);

        $this->actingAs($owner)
            ->getJson('/_test/canteen/5/owner')
            ->assertOk()
            ->assertJson(['tenant_role' => 'owner']);

        $this->actingAs($cashier)
            ->getJson('/_test/canteen/5/owner')
            ->assertStatus(403);

        $this->actingAs($cashier)
            ->getJson('/_test/canteen/5/cashier')
            ->assertOk()
            ->assertJson(['tenant_role' => 'cashier']);

        $this->actingAs($outsider)
            ->getJson('/_test/canteen/5/cashier')
            ->assertStatus(403);

        // Missing table fails closed with 403
        Schema::drop('canteen_tenant_users');
        $this->actingAs($owner)->getJson('/_test/canteen/5/owner')->assertStatus(403);
    }

    public function test_ensure_session_is_active_expires_stale_sessions_in_carbon3(): void
    {
        $user = $this->makeUser('session@example.com', [1]);

        // Fresh session updates _last_activity_at
        $this->actingAs($user)
            ->get('/_test/session-ping')
            ->assertOk()
            ->assertSessionHas('_last_activity_at');

        // Stale session older than lifetime must expire and log out user
        config(['session.lifetime' => 30]);
        $this->actingAs($user)
            ->withSession([
                '_token' => 'test-token',
                '_last_activity_at' => now()->subMinutes(45),
            ])
            ->getJson('/_test/session-ping')
            ->assertStatus(401)
            ->assertJson(['code' => 'SESSION_EXPIRED']);

        $this->assertGuest();
    }

    public function test_resolve_active_organization_blocks_users_without_org_and_syncs_session(): void
    {
        $noOrgUser = $this->makeUser('noorg@example.com', []);
        $multiOrgUser = $this->makeUser('multiorg@example.com', [1, 2]);

        $this->actingAs($noOrgUser)
            ->getJson('/_test/org-check')
            ->assertStatus(403);
        $this->assertGuest();

        // Default org (1) resolved first
        $this->actingAs($multiOrgUser)
            ->getJson('/_test/org-check')
            ->assertOk()
            ->assertJson([
                'id' => 1,
                'obj_id' => 1,
                'shared_id' => 1,
            ]);

        // Switching active_organization_id in session updates active_organization & shared view
        $this->actingAs($multiOrgUser)
            ->withSession(['active_organization_id' => 2])
            ->getJson('/_test/org-check')
            ->assertOk()
            ->assertJson([
                'id' => 2,
                'obj_id' => 2,
                'shared_id' => 2,
            ]);
    }

    public function test_ajax_and_portal_exception_handling(): void
    {
        // AJAX request (X-Requested-With: XMLHttpRequest without Accept: application/json) receives 401 JSON
        $this->get('/_test/superuser', ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertStatus(401)
            ->assertJsonStructure(['error']);

        // Unauthenticated guest on /student/* falls back safely to login when student.login isn't defined
        $this->get('/student/protected-check')->assertRedirect(route('login'));
    }
}

