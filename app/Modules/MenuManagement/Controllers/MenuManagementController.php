<?php 
namespace App\Modules\MenuManagement\Controllers;

use App\Core\Menu\MenuRepository;
use App\Core\Menu\MenuTreeBuilder;
use App\Http\Controllers\MainController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Models\Menu;
use Models\Module;   // sesuaikan namespace model Module Anda
use Models\Role; // sesuaikan kalau bukan pakai spatie/permission

class MenuManagementController extends MainController
{
     public function __construct(){
        parent::__construct(null);
    }
    public function index()
    {
        $items = (new MenuRepository())->allForAdmin();
        $tree  = (new MenuTreeBuilder())->build($items); // currentUrl tidak relevan di sini

        $modules = Module::orderBy('name')->get(['id', 'name']);
        $roles   = Role::orderBy('name')->pluck('name');

        // Flat list untuk dropdown "Parent Menu" (dengan indentasi sesuai depth)
        $flatForParentSelect = $this->flattenForSelect($tree);

        return view('MenuManagement::index', [
            'tree' => $tree,
            'modules' => $modules,
            'roles' => $roles,
            'parentOptions' => $flatForParentSelect,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['sequence'] = $data['sequence'] ?? ((Menu::max('sequence') ?? 0) + 1);

        $menu = Menu::create($data);

        $this->clearMenuCache();

        return response()->json(['message' => 'Menu berhasil ditambahkan.', 'data' => $menu]);
    }

    public function show(Menu $menu)
    {
        return response()->json($menu);
    }

    public function update(Request $request, Menu $menu)
    {
        $data = $this->validated($request, $menu->id);
        $menu->update($data);

        $this->clearMenuCache();

        return response()->json(['message' => 'Menu berhasil diperbarui.', 'data' => $menu]);
    }

    public function destroy(Menu $menu)
    {
        if ($menu->children()->exists() ?? Menu::where('parent_id', $menu->id)->exists()) {
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
     * Payload: [{id, parent_id, sequence, children: [...]}, ...]
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

    private function applyReorder(array $nodes, ?int $parentId): void
    {
        foreach ($nodes as $index => $node) {
            Menu::where('id', $node['id'])->update([
                'parent_id' => $parentId,
                'sequence'  => $index + 1,
            ]);

            if (!empty($node['children'])) {
                $this->applyReorder($node['children'], (int) $node['id']);
            }
        }
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'text'          => 'required|string|max:150',
            'name'          => 'nullable|string|max:150',
            'url'           => 'nullable|string|max:255',
            'route'         => 'nullable|string|max:150',
            'icon'          => 'nullable|string|max:100',
            'icon_type'     => 'nullable|string|in:material,fontawesome,bootstrap',
            'action'        => 'nullable|string|max:150',
            'can'           => 'nullable|string|max:150',
            'flyout_title'  => 'nullable|string|max:150',
            'parent_id'     => [
                'nullable',
                'integer',
                Rule::exists('menus', 'id')->whereNot('id', $ignoreId),
            ],
            'module_id'     => 'nullable|integer|exists:modules,id',
            'role'          => 'nullable|string|max:100',
            'menu_type_id'  => 'required|integer|in:1,2', // 1 = topnav, 2 = sidebar
            'is_publish'    => 'required|boolean',
            'is_active'     => 'required|boolean',
        ]);
    }

    private function flattenForSelect($tree, int $depth = 0): array
    {
        $result = [];
        foreach ($tree as $item) {
            $result[] = [
                'id' => $item->id,
                'label' => str_repeat('— ', $depth) . $item->text,
            ];
            if (!empty($item->children)) {
                $result = array_merge($result, $this->flattenForSelect($item->children, $depth + 1));
            }
        }
        return $result;
    }

    private function clearMenuCache(): void
    {
        // Sesuaikan dengan strategi cache MenuService yang sudah kita buat sebelumnya.
        // Kalau pakai Cache::tags(), lebih baik: Cache::tags('menu')->flush();
        Cache::flush(); // ⚠️ ganti dengan invalidation yang lebih spesifik di production
    }
}