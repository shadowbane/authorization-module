<?php

namespace Modules\Authorization\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Modules\Authorization\Http\Requests\PermissionStoreCrudRequest as StoreRequest;
use Modules\Authorization\Http\Requests\PermissionUpdateCrudRequest as UpdateRequest;

// VALIDATION

class PermissionCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        $this->role_model = $role_model = config('authorization.models.role');
        $this->permission_model = $permission_model = config('authorization.models.permission');

        $this->crud->setModel($permission_model);
        $this->crud->setEntityNameStrings(trans('authorization::permissionmanager.permission_singular'), trans('authorization::permissionmanager.permission_plural'));
        $this->crud->setRoute(backpack_url('permission'));

        // deny access according to configuration file
        if (config('authorization.allow_permission_create') == false) {
            $this->crud->denyAccess('create');
        }
        if (config('authorization.allow_permission_update') == false) {
            $this->crud->denyAccess('update');
        }
        if (config('authorization.allow_permission_delete') == false) {
            $this->crud->denyAccess('delete');
        }
    }

    public function setupListOperation()
    {
        $this->crud->addColumn([
            'name'  => 'name',
            'label' => trans('authorization::permissionmanager.name'),
            'type'  => 'text',
        ]);

        if (config('authorization.multiple_guards')) {
            $this->crud->addColumn([
                'name'  => 'guard_name',
                'label' => trans('authorization::permissionmanager.guard_type'),
                'type'  => 'text',
            ]);
        }
    }

    public function setupCreateOperation()
    {
        $this->addFields();
        $this->crud->setValidation(StoreRequest::class);

        //otherwise, changes won't have effect
        cache()->forget('spatie.permission.cache');
    }

    public function setupUpdateOperation()
    {
        $this->addFields();
        $this->crud->setValidation(UpdateRequest::class);

        //otherwise, changes won't have effect
        cache()->forget('spatie.permission.cache');
    }

    private function addFields()
    {
        $this->crud->addField([
            'name'  => 'name',
            'label' => trans('authorization::permissionmanager.name'),
            'type'  => 'text',
        ]);

        if (config('authorization.multiple_guards')) {
            $this->crud->addField([
                'name'    => 'guard_name',
                'label'   => trans('authorization::permissionmanager.guard_type'),
                'type'    => 'select_from_array',
                'options' => $this->getGuardTypes(),
            ]);
        }
    }

    /*
     * Get an array list of all available guard types
     * that have been defined in app/config/auth.php
     *
     * @return array
     **/
    private function getGuardTypes()
    {
        $guards = config('auth.guards');

        $returnable = [];
        foreach ($guards as $key => $details) {
            $returnable[$key] = $key;
        }

        return $returnable;
    }
}