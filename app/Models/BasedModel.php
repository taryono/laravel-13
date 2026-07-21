<?php

namespace Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;


class BasedModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];
    protected $casts = [
        'deleted_at' => 'datetime',
    ];
    public $timestamps = true;
    protected $hidden = [
        "created_at",
        "updated_at",
        "deleted_at",
    ];

    protected $exceptions = ['_method', '_token', 'id', 'password_confirmation', 'image', 'photo', 'file', 'logo', 'thumb'];
    protected $ignored = []; 
  
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('certification_id', function (Builder $builder) {
            $table = $builder->getModel()->getTable(); 
            if($table == "preparations"){
                if(Session()->get('certification_id') != null){ 
                    $builder->where('preparations.certification_id', Session()->get('certification_id'));
                }
            } 

        });
        static::saving(function ($model) {
            if($model instanceof \Models\CertificationRequestStatus){
                $model->user_id = auth()->user()->id;
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
            $result = array_filter($result);

        } catch (\Exception $e) {
            throw new \RuntimeException('Error serializing request: ' . $e->getMessage());
        }

        return $result;
    }

    /**
     * Get table columns excluding timestamps
     */
    protected function getTableColumns()
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

}
