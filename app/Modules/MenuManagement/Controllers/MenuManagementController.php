<?php

namespace App\Modules\MenuManagement\Controllers;

use App\Core\Menu\MenuRepository;
use App\Core\Menu\MenuTreeBuilder;
use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Models\Action;
use Models\Menu;
use Models\Role;
use App\Models\User; // sesuaikan namespace kalau User model kamu bukan di sini

/**
 * CATATAN MIGRASI DARI SKEMA LAMA:
 * - Tabel `modules` sudah tidak dipakai lagi -> semua referensi module_id dihapus.
 * - Kolom menu: text -> title, name -> slug, menu_type_id(int) -> type(enum topbar/sidebar),
 *   sequence -> sort_order. Kolom action, can, is_publish sudah tidak ada.
 * - `role` (string tunggal di menu) digantikan tabel pivot `menu_role` (many-to-many,
 *   dengan flag is_view/is_create/is_edit/is_delete per role).
 * - Permission granular per aksi sekarang pakai tabel `actions` + `action_role` + `action_user`.
 * - `flyout_title` tidak ada di dump SQL yang dilampirkan, ditambahkan lewat migration terpisah
 *   (2026_08_01_000000_add_flyout_title_to_menus_table.php). Kalau memang tidak dipakai lagi,
 *   silakan hapus dari $fillable, validasi di bawah, dan migration-nya.
 *
 * PENTING: MenuRepository & MenuTreeBuilder belum saya sentuh karena kodenya tidak ada
 * di percakapan ini. Keduanya kemungkinan besar masih mereferensikan kolom lama (`text`,
 * `sequence`, dst) dan perlu disesuaikan manual ke `title` / `sort_order`.
 */
class MenuManagementController extends MainController
{
    public function __construct()
    {
        parent::__construct(null);
    }

    public function index()
    {
        $items = (new MenuRepository())->allForAdmin();
        $tree  = (new MenuTreeBuilder())->build($items);

        $roles = Role::orderBy('name')->get(['id', 'name', 'display_name']);
        $users = User::orderBy('name')->get(['id', 'name', 'email']);

        $flatForParentSelect = $this->flattenForSelect($tree);

        return view('MenuManagement::index', [
            'tree'          => $tree,
            'roles'         => $roles,
            'users'         => $users,
            'parentOptions' => $flatForParentSelect,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug']       = $data['slug'] ?? Str::slug($data['title']);
        $data['sort_order'] = $data['sort_order'] ?? ((Menu::max('sort_order') ?? 0) + 1);

        $menu = Menu::create($data);

        $this->clearMenuCache();

        return response()->json(['message' => 'Menu berhasil ditambahkan.', 'data' => $menu]);
    }

    public function show(Menu $menu)
    {
        return response()->json($menu->load(['actions', 'roles']));
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $this->validated($request, $menu->id);

        if (isset($data['parent_id']) && $this->wouldCreateCycle($menu->id, $data['parent_id'])) {
            return response()->json([
                'message' => 'Parent menu tidak valid: menu tidak boleh menjadi turunannya sendiri.',
            ], 422);
        }

        if (!isset($data['slug'])) {
            $data['slug'] = $menu->slug;
        }

        $menu->update($data);

        $this->clearMenuCache();

        return response()->json(['message' => 'Menu berhasil diperbarui.', 'data' => $menu]);
    }

    public function destroy(Menu $menu)
    {
        if ($menu->children()->exists()) {
            return response()->json([
                'message' => 'Menu ini masih punya sub-menu. Hapus atau pindahkan sub-menu-nya dulu.',
            ], 422);
        }

        $menu->delete();
        $this->clearMenuCache();

        return response()->json(['message' => 'Menu berhasil dihapus.']);
    }

    /**
     * Simpan hasil drag-and-drop: urutan & parent_id baru untuk seluruh tree.
     * Payload: [{id, parent_id, sort_order, children: [...]}, ...]
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'tree' => 'required|array',
        ]);

        DB::transaction(function () use ($request) {
            $this->applyReorder($request->input('tree'), null);
        });

        $this->clearMenuCache();

        return response()->json(['message' => 'Urutan menu berhasil disimpan.']);
    }

    /**
     * Assign role + flag CRUD (is_view/is_create/is_edit/is_delete) ke sebuah menu.
     * Bersifat replace: role yang tidak dikirim akan di-detach dari menu ini.
     *
     * Payload:
     * {
     *   "assignments": [
     *     {"role_id": 1, "is_view": 1, "is_create": 0, "is_edit": 0, "is_delete": 0},
     *     ...
     *   ]
     * }
     */
    public function syncRoles(Request $request, Menu $menu)
    {
        $validated = $request->validate([
            'assignments'              => 'present|array',
            'assignments.*.role_id'    => 'required|integer|exists:roles,id',
            'assignments.*.is_view'    => 'nullable|boolean',
            'assignments.*.is_create'  => 'nullable|boolean',
            'assignments.*.is_edit'    => 'nullable|boolean',
            'assignments.*.is_delete'  => 'nullable|boolean',
        ]);

        DB::transaction(function () use ($menu, $validated) {
            $submittedRoleIds = collect($validated['assignments'])->pluck('role_id')->all();

            // Lepas role yang tidak lagi disertakan
            $menu->roles()->newPivotStatement()
                ->where('menu_id', $menu->id)
                ->when(!empty($submittedRoleIds), fn ($q) => $q->whereNotIn('role_id', $submittedRoleIds))
                ->delete();

            foreach ($validated['assignments'] as $assignment) {
                DB::table('menu_role')->updateOrInsert(
                    ['menu_id' => $menu->id, 'role_id' => $assignment['role_id']],
                    [
                        'is_view'    => $assignment['is_view'] ?? 0,
                        'is_create'  => $assignment['is_create'] ?? 0,
                        'is_edit'    => $assignment['is_edit'] ?? 0,
                        'is_delete'  => $assignment['is_delete'] ?? 0,
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            }
        });

        $this->clearMenuCache();

        return response()->json([
            'message' => 'Assignment role untuk menu berhasil disimpan.',
            'data'    => $menu->roles()->get(),
        ]);
    }

    /**
     * Daftar action milik satu menu.
     */
    public function actionsIndex(Menu $menu)
    {
        return response()->json(
            $menu->actions()->with(['roles:id,name', 'users:id,name'])->orderBy('name')->get()
        );
    }

    public function storeAction(Request $request, Menu $menu)
    {
        $data = $request->validate([
            'name'         => ['required', 'string', 'max:191', Rule::unique('actions', 'name')],
            'display_name' => 'required|string|max:191',
            'description'  => 'nullable|string|max:255',
            'is_active'    => 'boolean',
        ]);

        $data['menu_id'] = $menu->id;

        $action = Action::create($data);

        $this->clearMenuCache();

        return response()->json(['message' => 'Action berhasil ditambahkan.', 'data' => $action]);
    }

    public function updateAction(Request $request, Action $action)
    {
        $data = $request->validate([
            'name'         => ['sometimes', 'required', 'string', 'max:191', Rule::unique('actions', 'name')->ignore($action->id)],
            'display_name' => 'sometimes|required|string|max:191',
            'description'  => 'nullable|string|max:255',
            'is_active'    => 'boolean',
        ]);

        $action->update($data);

        $this->clearMenuCache();

        return response()->json(['message' => 'Action berhasil diperbarui.', 'data' => $action]);
    }

    public function destroyAction(Action $action)
    {
        $action->delete();

        $this->clearMenuCache();

        return response()->json(['message' => 'Action berhasil dihapus.']);
    }

    /**
     * Assign action ke role dan/atau user langsung.
     * Bersifat replace (sync) untuk masing-masing sisi.
     *
     * Payload: { "role_ids": [1,2], "user_ids": [5] }
     */
    public function syncActionAccess(Request $request, Action $action)
    {
        $validated = $request->validate([
            'role_ids'   => 'sometimes|array',
            'role_ids.*' => 'integer|exists:roles,id',
            'user_ids'   => 'sometimes|array',
            'user_ids.*' => 'integer|exists:users,id',
        ]);

        DB::transaction(function () use ($action, $validated) {
            if (array_key_exists('role_ids', $validated)) {
                $action->roles()->sync($validated['role_ids']);
            }
            if (array_key_exists('user_ids', $validated)) {
                $action->users()->sync($validated['user_ids']);
            }
        });

        $this->clearMenuCache();

        return response()->json([
            'message' => 'Assignment action berhasil disimpan.',
            'data'    => $action->load(['roles', 'users']),
        ]);
    }

    private function applyReorder(array $nodes, ?int $parentId): void
    {
        foreach ($nodes as $index => $node) {
            Menu::where('id', $node['id'])->update([
                'parent_id'  => $parentId,
                'sort_order' => $index + 1,
            ]);

            if (!empty($node['children'])) {
                $this->applyReorder($node['children'], (int) $node['id']);
            }
        }
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'title'         => 'required|string|max:191',
            'flyout_title'  => 'nullable|string|max:191',
            'slug'          => [
                'nullable',
                'string',
                'max:191',
                Rule::unique('menus', 'slug')->ignore($ignoreId),
            ],
            'icon'          => 'nullable|string|max:191',
            'icon_type'     => 'nullable|string|in:material,fa',
            'route'         => 'nullable|string|max:191',
            'url'           => 'nullable|string|max:255',
            'type'          => 'required|string|in:topbar,sidebar',
            'parent_id'     => array_filter([
                'nullable',
                'integer',
                Rule::exists('menus', 'id'),
                // Tidak boleh jadi parent dari dirinya sendiri.
                // Cek siklus lebih dalam (parent = salah satu keturunannya) ditangani
                // manual lewat wouldCreateCycle() di method update().
                $ignoreId ? Rule::notIn([$ignoreId]) : null,
            ]),
            'sort_order'    => 'nullable|integer|min:0',
            'is_active'     => 'required|boolean',
        ]);
    }

    /**
     * Cegah parent_id diarahkan ke diri sendiri atau ke salah satu keturunannya sendiri
     * (yang akan bikin loop tak berhingga di tree).
     */
    private function wouldCreateCycle(int $menuId, ?int $newParentId): bool
    {
        if ($newParentId === null) {
            return false;
        }
        if ($newParentId === $menuId) {
            return true;
        }

        $currentId = $newParentId;
        while ($currentId !== null) {
            if ($currentId === $menuId) {
                return true;
            }
            $currentId = Menu::where('id', $currentId)->value('parent_id');
        }

        return false;
    }

    private function flattenForSelect($tree, int $depth = 0): array
    {
        $result = [];
        foreach ($tree as $item) {
            $result[] = [
                'id'    => $item->id,
                'label' => str_repeat('— ', $depth) . $item->title,
            ];
            if (!empty($item->children)) {
                $result = array_merge($result, $this->flattenForSelect($item->children, $depth + 1));
            }
        }
        return $result;
    }

    private function clearMenuCache(): void
    {
        // Sesuaikan dengan strategi cache MenuService.
        // Kalau pakai Cache::tags(), lebih baik: Cache::tags('menu')->flush();
        Cache::flush(); // ⚠️ ganti dengan invalidation yang lebih spesifik di production
    }
}