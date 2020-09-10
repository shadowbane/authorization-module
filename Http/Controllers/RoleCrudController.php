<?php

namespace Modules\Authorization\Http\Controllers;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Modules\Authorization\Http\Requests\RoleStoreCrudRequest as StoreRequest;
use Modules\Authorization\Http\Requests\RoleUpdateCrudRequest as UpdateRequest;

// VALIDATION

class RoleCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        $this->role_model = $role_model = config('authorization.models.role');
        $this->permission_model = $permission_model = config('authorization.models.permission');

        $this->crud->setModel($role_model);
        $this->crud->setEntityNameStrings(trans('authorization::permissionmanager.role'), trans('authorization::permissionmanager.roles'));
        $this->crud->setRoute(backpack_url('role'));

        // deny access according to configuration file
        if (config('authorization.allow_role_create') == false) {
            $this->crud->denyAccess('create');
        }
        if (config('authorization.allow_role_update') == false) {
            $this->crud->denyAccess('update');
        }
        if (config('authorization.allow_role_delete') == false) {
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
        $this->crud->addColumn([   // select_multiple: n-n relationship (with pivot table)
            'label'     => trans('authorization::permissionmanager.users'), // Table column heading
            'type'      => 'relationship_count',
            'name'      => 'users', // the method that defines the relationship in your Model
            'wrapper'   => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('user?role='.$entry->getKey());
                },
            ],
            'suffix'    => ' users',
        ]);
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

        $this->crud->addField([
            'label'     => ucfirst(trans('authorization::permissionmanager.permission_plural')),
            'type'      => 'checklist',
            'name'      => 'permissions',
            'entity'    => 'permissions',
            'attribute' => 'name',
            'model'     => $this->permission_model,
            'pivot'     => true,
        ]);
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
