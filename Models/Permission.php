<?php

namespace Modules\Authorization\Models;

use Exception;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Spatie\Permission\Models\Permission as OriginalPermission;

/**
 * Class Permission
 * @package Modules\Authorization\Models
 */
class Permission extends OriginalPermission
{
    use CrudTrait;

    protected $fillable = ['name', 'guard_name', 'updated_at', 'created_at'];
    
    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();
        if (empty($model->guard_name)) {
            static::creating(function ($model) {
                if (empty($model->guard_name)) {
                    try {
                        $model->guard_name = 'web';
                    } catch (Exception $e) {
                        abort(500, $e->getMessage());
                    }
                }
            });
        }
    }
}
