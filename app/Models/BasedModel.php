<?php

namespace Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
// use App\Traits\HasUniqueCode;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;


class BasedModel extends Model
{
    use HasFactory, SoftDeletes;
    protected static array $relationsCache = [];
    // use HasUniqueCode;

    protected $guarded = ['id'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    public $timestamps = true;
    protected $hidden = [];

    protected $exceptions = ['_method', '_token', 'id', 'password_confirmation', 'image', 'photo', 'file', 'logo', 'thumb'];
    protected $ignored = [];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('organization_id', function (Builder $builder) {
            if (!Auth::check()) return;
            $user = Auth::user();
            if (!$user || !$user instanceof \App\Models\User) return;
            if (!$user || $user->isCheckingRole ?? false) {
                return;
            }

            // Tandai sedang mengecek untuk mencegah loop
            $user->isCheckingRole = true;

            try {
                $table = $builder->getModel()->getTable();
                if (!Schema::hasColumn($table, 'organization_id')) return;

                // 🔑 Sistem Super Admin = LIHAT SEMUA, tanpa filter
                if ($user->isSystemSuperAdmin()) {
                    return; // Tidak diterapkan filter — bisa akses semua organisasi
                }

                // 2. Ambil semua ID organisasi yang bisa diakses user ini
                $accessibleOrgIds = \Models\Organization::getIdsForUser($user);

                // 3. Terapkan filter
                if (!empty($accessibleOrgIds)) {
                    $builder->whereIn($table . '.organization_id', $accessibleOrgIds);
                }
            } finally {
                // Lepas tanda setelah selesai
                $user->isCheckingRole = false;
            }
        });

        static::saving(function ($model) {
            if (Auth::guest()) {
                return;
            }

            $user = Auth::user();
            if (!$user || ($user->isCheckingRole ?? false)) {
                return;
            }

            $user->isCheckingRole = true;

            try {
                if (!Schema::hasColumn($model->getTable(), 'organization_id')) {
                    return;
                }
                if ($user->isSystemSuperAdmin()) {
                    // Super Admin bisa pilih organisasi secara manual
                    return;
                }
                // Isi otomatis dengan organisasi aktif user
                if (!$model->organization_id) {
                    $model->organization_id = $user->get_current_organization_id;
                }
            } finally {
                $user->isCheckingRole = false;
            }
        });
    }


    protected function _getFields($model)
    {
        return $model->getConnection()->getSchemaBuilder()->getColumnListing($model->getTable());
    }
    /**
     * Create a new model instance with automatic request serialization
     */
    public static function createFromRequest(Request $request, array $ignored = [])
    {
        $instance = new static;
        $serializedData = $instance->serializeRequest($request, $ignored);

        if (count($serializedData) == 0) {
            throw new \InvalidArgumentException('No valid data to create record');
        }

        return static::create($serializedData);
    }

    /**
     * Update model with automatic request serialization
     */
    public function updateFromRequest(Request $request, array $ignored = [])
    {
        $serializedData = $this->serializeRequest($request, $ignored);

        if (count($serializedData) == 0) {
            throw new \InvalidArgumentException('No valid data to update record');
        }

        return $this->update($serializedData);
    }

    /**
     * Find and update model with automatic request serialization
     */
    public static function findAndUpdateFromRequest($id, Request $request, array $ignored = [])
    {
        $instance = static::findOrFail($id);
        return $instance->updateFromRequest($request, $ignored);
    }

    /**
     * Serialize request data based on model fields
     */
    protected function serializeRequest(Request $request, array $ignored = [])
    {
        // Merge class-level ignored fields with method-level ignored fields
        $allIgnored = array_merge($this->ignored, $ignored);

        // Get data from request, excluding standard exceptions
        $data = $request->except($this->exceptions);

        try {
            // Remove ignored fields
            if ($allIgnored) {
                foreach ($allIgnored as $field) {
                    unset($data[$field]);
                }
            }

            // Get model fields
            $fields = $this->getTableColumns();

            $result = [];
            foreach ($data as $key => $val) {
                // Skip empty password
                if ($key == "password" && empty($val)) {
                    continue;
                }

                // Only include fields that exist in table
                if (in_array($key, $fields)) {
                    if ($key == "publish_date" && !empty($val)) {
                        $result[$key] = dateFormat($val);
                    } else {
                        $result[$key] = $val;
                    }
                }
            }
            // Ganti array_filter dengan penyaringan yang aman
            $result = array_filter($result, function ($value) {
                return $value !== null && $value !== '';
            });
        } catch (\Exception $e) {
            throw new \RuntimeException('Error serializing request: ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * Get table columns excluding timestamps
     */
    public function getTableColumns()
    {
        return array_diff(
            $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable()),
            ["created_at", "updated_at", "deleted_at"]
        );
    }

    /**
     * Set fields to ignore during serialization
     */
    public function setIgnored(array $ignored)
    {
        $this->ignored = $ignored;
        return $this;
    }

    /**
     * Add field to ignore list
     */
    public function addIgnored(string $field)
    {
        $this->ignored[] = $field;
        return $this;
    }

    public static function getModelRelations(): array
    {
        $class = static::class;

        // 1. Cek L1 cache (static property, per-request)
        if (isset(static::$relationsCache[$class])) {
            return static::$relationsCache[$class];
        }

        // 2. Cek L2 cache (persisten, lintas-request)
        $cacheKey = 'model_relations:' . $class;

        $relations = Cache::rememberForever($cacheKey, function () use ($class) {
            return static::resolveModelRelations();
        });

        // Simpan ke L1 cache supaya panggilan berikutnya di request yang sama super cepat
        static::$relationsCache[$class] = $relations;

        return $relations;
    }

    /**
     * Logika reflection yang sebenarnya, dipisah supaya rapi.
     */
    protected static function resolveModelRelations(): array
    {
        $relations = [];
        $instance = new static();

        $reflection = new ReflectionClass($instance);

        foreach ($reflection->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            // Lewati method bawaan base Eloquent Model
            if ($method->getDeclaringClass()->getName() === Model::class) {
                continue;
            }

            if ($method->getNumberOfParameters() > 0) {
                continue;
            }

            if ($method->isStatic() || $method->isAbstract()) {
                continue;
            }

            try {
                $return = $method->invoke($instance);

                if ($return instanceof Relation) {
                    $relations[$method->getName()] = get_class($return);
                }
            } catch (\Throwable $e) {
                continue;
            }
        }

        return $relations;
    }

    /**
     * Opsional: helper untuk hapus cache relasi (dipanggil manual, misal via artisan command
     * setelah deploy, atau otomatis lewat deploy script).
     */
    public static function forgetRelationsCache(): void
    {
        $class = static::class;
        unset(static::$relationsCache[$class]);
        Cache::forget('model_relations:' . $class);
    }
}
