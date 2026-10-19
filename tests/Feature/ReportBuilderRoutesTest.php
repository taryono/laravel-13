<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Models\ReportTemplate;
use Models\ReportBuilderComponent;
use Models\ReportDataSource;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ReportBuilderRoutesTest extends TestCase
{
    protected User $user;
    protected ReportTemplate $template;
    protected ReportBuilderComponent $component;

    protected function setUp(): void
    {
        parent::setUp();
        config([
            'database.default' => 'mysql',
            'database.connections.mysql.database' => 'laravel',
            'database.connections.mysql.host' => '127.0.0.1',
            'database.connections.mysql.username' => 'root',
            'database.connections.mysql.password' => '',
        ]);
        \Illuminate\Support\Facades\DB::purge('mysql');

        $this->user = User::first() ?? User::factory()->create();
        
        $this->template = ReportTemplate::first() ?? ReportTemplate::create([
            'name' => 'Template Test Rapor',
            'type' => 'academic',
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'margin_top' => 2,
            'margin_right' => 2,
            'margin_bottom' => 2,
            'margin_left' => 2.5,
            'is_active' => true,
            'configuration' => [
                'version' => '1.0',
                'page' => ['size' => 'A4', 'orientation' => 'portrait'],
                'sections' => [],
            ],
        ]);

        $this->component = ReportBuilderComponent::first() ?? ReportBuilderComponent::create([
            'name' => 'Komponen Test Unit',
            'type' => 'text',
            'category' => 'text',
            'order' => 1,
            'is_active' => true,
            'default_config' => ['type' => 'text', 'content' => 'Sample'],
        ]);
    }

    public function test_index_page_returns_ok(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.report-builder.index'));
        $response->assertStatus(200);
        $response->assertViewIs('ReportBuilder::index');
    }

    public function test_builder_create_page_returns_ok(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.report-builder.builder'));
        $response->assertStatus(200);
        $response->assertViewIs('ReportBuilder::builder');
    }

    public function test_builder_edit_page_returns_ok(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.report-builder.builder', $this->template->id));
        $response->assertStatus(200);
        $response->assertViewIs('ReportBuilder::builder');
    }

    public function test_preview_route_returns_ok(): void
    {
        $response = $this->actingAs($this->user)->post(route('admin.report-builder.preview'), [
            'configuration' => $this->template->configuration,
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'margin_top' => 2,
            'margin_right' => 2,
            'margin_bottom' => 2,
            'margin_left' => 2.5,
        ]);

        $response->assertStatus(200);
    }

    public function test_store_template_returns_json_and_stores_in_db(): void
    {
        $payload = [
            'name' => 'Template Baru Dari Unit Test',
            'type' => 'pesantren',
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'margin_top' => 2,
            'margin_right' => 2,
            'margin_bottom' => 2,
            'margin_left' => 2.5,
            'configuration' => [
                'version' => '1.0',
                'sections' => [
                    [
                        'id' => 'sec_unit_1',
                        'type' => 'text',
                        'order' => 1,
                        'content' => 'Judul Test Unit',
                        'style' => ['fontSize' => 14],
                    ],
                ],
            ],
        ];

        $response = $this->actingAs($this->user)
            ->postJson(route('admin.report-builder.store'), $payload);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('report_templates', [
            'name' => 'Template Baru Dari Unit Test',
            'type' => 'pesantren',
        ]);
    }

    public function test_preview_render_template_returns_ok(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.report-builder.preview-render', $this->template->id));

        $response->assertStatus(200);
    }

    public function test_preview_pdf_template_returns_ok(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.report-builder.preview-pdf', $this->template->id));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }

    public function test_toggle_template_status(): void
    {
        $initialStatus = $this->template->is_active;

        $response = $this->actingAs($this->user)
            ->postJson(route('admin.report-builder.toggle', $this->template->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->template->refresh();
        $this->assertEquals(!$initialStatus, $this->template->is_active);
    }

    public function test_set_default_template(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.report-builder.set-default', $this->template->id));

        $response->assertStatus(302);

        $this->template->refresh();
        $this->assertTrue($this->template->is_default);
    }

    public function test_duplicate_template(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('admin.report-builder.duplicate', $this->template->id));

        $response->assertStatus(302);

        $this->assertDatabaseHas('report_templates', [
            'name' => $this->template->name . ' (Salinan)',
        ]);
    }

    public function test_upload_asset(): void
    {
        Storage::fake('public');
        $file = UploadedFile::fake()->image('logo.png', 100, 100);

        $response = $this->actingAs($this->user)->postJson(route('admin.report-builder.upload-asset'), [
            'file' => $file,
            'type' => 'logo',
            'template_id' => $this->template->id,
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    public function test_components_index_returns_ok(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.report-builder.components.index'));

        $response->assertStatus(200);
        $response->assertViewIs('ReportBuilder::components.index');
    }

    public function test_components_create_returns_ok(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.report-builder.components.create'));

        $response->assertStatus(200);
        $response->assertViewIs('ReportBuilder::components.create');
    }

    public function test_components_store(): void
    {
        $response = $this->actingAs($this->user)->postJson(route('admin.report-builder.components.store'), [
            'name' => 'Komponen Baru Dari Feature Test',
            'type' => 'table',
            'category' => 'custom',
            'icon' => '🏷️',
            'description' => 'Deskripsi komponen testing',
            'order' => 50,
            'is_active' => '1',
            'default_config' => ['type' => 'table', 'title' => 'Tabel Baru'],
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('report_builder_components', [
            'name' => 'Komponen Baru Dari Feature Test',
        ]);
    }

    public function test_components_edit_returns_ok(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('admin.report-builder.components.edit', $this->component->id));

        $response->assertStatus(200);
        $response->assertViewIs('ReportBuilder::components.edit');
    }

    public function test_components_update(): void
    {
        $response = $this->actingAs($this->user)
            ->putJson(route('admin.report-builder.components.update', $this->component->id), [
                'name' => $this->component->name . ' Updated',
                'type' => $this->component->type,
                'category' => $this->component->category,
                'icon' => '⭐',
                'description' => 'Updated deskripsi',
                'order' => 10,
                'is_active' => '1',
            ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('report_builder_components', [
            'id' => $this->component->id,
            'name' => $this->component->name . ' Updated',
        ]);
    }

    public function test_components_toggle(): void
    {
        $initialStatus = $this->component->is_active;

        $response = $this->actingAs($this->user)
            ->postJson(route('admin.report-builder.components.toggle', $this->component->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->component->refresh();
        $this->assertEquals(!$initialStatus, $this->component->is_active);
    }

    public function test_components_destroy(): void
    {
        $temp = ReportBuilderComponent::create([
            'name' => 'Temp Component To Delete',
            'type' => 'text',
            'category' => 'custom',
            'order' => 999,
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson(route('admin.report-builder.components.destroy', $temp->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertSoftDeleted('report_builder_components', [
            'id' => $temp->id,
        ]);
    }

    public function test_destroy_template(): void
    {
        $temp = ReportTemplate::create([
            'name' => 'Temp Template To Delete',
            'type' => 'academic',
            'paper_size' => 'A4',
            'orientation' => 'portrait',
            'margin_top' => 2,
            'margin_right' => 2,
            'margin_bottom' => 2,
            'margin_left' => 2.5,
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson(route('admin.report-builder.destroy', $temp->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertSoftDeleted('report_templates', [
            'id' => $temp->id,
        ]);
    }

    public function test_alias_routes_work(): void
    {
        $response = $this->actingAs($this->user)->get(route('report-builder.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->user)->get(route('report-builder.builder'));
        $response->assertStatus(200);
    }

    public function test_data_sources_index_page_returns_ok(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.report-builder.data-sources.index'));
        $response->assertStatus(200);
        $response->assertViewIs('ReportBuilder::data-sources.index');
        $response->assertSee('Kelola Sumber Data');
    }

    public function test_data_sources_create_page_returns_ok(): void
    {
        $response = $this->actingAs($this->user)->get(route('admin.report-builder.data-sources.create'));
        $response->assertStatus(200);
        $response->assertViewIs('ReportBuilder::data-sources.create');
        $response->assertSee('Tambah Sumber Data');
    }

    public function test_data_sources_store_creates_record(): void
    {
        $uniqueKey = 'test_source_' . time();
        $payload = [
            'name' => 'Data Source Test Automation',
            'key' => $uniqueKey,
            'category' => 'custom',
            'description' => 'Sumber data untuk pengujian otomatis unit test',
            'is_active' => '1',
            'order' => 99,
            'fields' => [
                ['name' => 'student_name', 'label' => 'Nama Santri', 'type' => 'string'],
                ['name' => 'score', 'label' => 'Nilai Ujian', 'type' => 'number'],
            ],
            'sample_data' => [
                ['student_name' => 'Ahmad Fulan', 'score' => 95],
            ],
        ];

        $response = $this->actingAs($this->user)
            ->post(route('admin.report-builder.data-sources.store'), $payload);

        $response->assertRedirect(route('admin.report-builder.data-sources.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('report_data_sources', [
            'key' => $uniqueKey,
            'name' => 'Data Source Test Automation',
            'category' => 'custom',
        ]);
    }

    public function test_data_sources_edit_page_returns_ok(): void
    {
        $dataSource = ReportDataSource::first();
        $this->assertNotNull($dataSource, 'Must have at least one ReportDataSource seeded');

        $response = $this->actingAs($this->user)
            ->get(route('admin.report-builder.data-sources.edit', $dataSource->id));

        $response->assertStatus(200);
        $response->assertViewIs('ReportBuilder::data-sources.edit');
        $response->assertSee($dataSource->name);
    }

    public function test_data_sources_update_modifies_record(): void
    {
        $dataSource = ReportDataSource::create([
            'name' => 'Source Before Update',
            'key' => 'source_to_update_' . time(),
            'category' => 'academic',
            'description' => 'Original description',
            'fields' => [['name' => 'field1', 'label' => 'Field 1', 'type' => 'string']],
            'is_active' => true,
        ]);

        $payload = [
            'name' => 'Source After Update',
            'key' => $dataSource->key,
            'category' => 'pesantren',
            'description' => 'Updated description text',
            'is_active' => '1',
            'order' => 5,
            'fields' => [
                ['name' => 'field1', 'label' => 'Field 1 Renamed', 'type' => 'string'],
                ['name' => 'field2', 'label' => 'Field 2 Added', 'type' => 'number'],
            ],
        ];

        $response = $this->actingAs($this->user)
            ->put(route('admin.report-builder.data-sources.update', $dataSource->id), $payload);

        $response->assertRedirect(route('admin.report-builder.data-sources.index'));

        $this->assertDatabaseHas('report_data_sources', [
            'id' => $dataSource->id,
            'name' => 'Source After Update',
            'category' => 'pesantren',
            'description' => 'Updated description text',
        ]);

        $dataSource->delete();
    }

    public function test_data_sources_toggle_status(): void
    {
        $dataSource = ReportDataSource::create([
            'name' => 'Toggle Status Source',
            'key' => 'toggle_src_' . time(),
            'category' => 'character',
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->patchJson(route('admin.report-builder.data-sources.toggle', $dataSource->id));

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'is_active' => false,
        ]);

        $this->assertDatabaseHas('report_data_sources', [
            'id' => $dataSource->id,
            'is_active' => false,
        ]);

        $dataSource->delete();
    }

    public function test_data_sources_api_list(): void
    {
        $response = $this->actingAs($this->user)
            ->getJson(route('admin.report-builder.data-sources.api-list'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'data' => [
                '*' => ['id', 'key', 'name', 'category', 'description', 'fields']
            ]
        ]);
    }

    public function test_data_sources_destroy_soft_deletes_record(): void
    {
        $dataSource = ReportDataSource::create([
            'name' => 'Source To Delete',
            'key' => 'src_to_delete_' . time(),
            'category' => 'custom',
            'is_system' => false,
            'is_active' => true,
        ]);

        $response = $this->actingAs($this->user)
            ->deleteJson(route('admin.report-builder.data-sources.destroy', $dataSource->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertSoftDeleted('report_data_sources', [
            'id' => $dataSource->id,
        ]);
    }
}
