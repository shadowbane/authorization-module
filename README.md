# Authorization Module
### Modular Authorization package for Laravel Backpack

Yet another Admin interface for [spatie/laravel-permission](https://github.com/spatie/laravel-permission). It allows admins to easily add/edit/remove users, roles and permissions, using [Laravel Backpack](https://laravelbackpack.com).
This module is based on [Backpack\PermissionManager](https://github.com/Laravel-Backpack/PermissionManager) and [spatie/laravel-permission](https://github.com/spatie/laravel-permission). So if you like the package, please support the original authors.

## Installation
0) Make sure you've already installed Backpack.

1) Install [nWidart/laravel-modules](https://github.com/nWidart/laravel-modules) and [joshbrw/laravel-module-installer](https://github.com/joshbrw/laravel-module-installer)

2) In your terminal:
    ``` bash
    composer require backpack/permissionmanager
    ```

3) Finish all installation steps for [spatie/laravel-permission](https://github.com/spatie/laravel-permission#installation), which as been pulled as a dependency. Run its migrations. Publish its config files. Most likely it's:
    ```bash
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="migrations"
    php artisan migrate
    php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider" --tag="config"
    // then First, add the Spatie\Permission\Traits\HasRoles trait to your User model(s)
    ```

4) Publish the config file & run the migrations
    ```bash
    php artisan vendor:publish --provider="Modules\Authorization\Providers\AuthorizationServiceProvider" --tag="config"
    ```

5) Add `CrudTrait` and `HasRole` to user model
    ```php
    <?php namespace App\Models;
    
    use Backpack\CRUD\app\Models\Traits\CrudTrait; // <------------------------------- this one
    use Spatie\Permission\Traits\HasRoles;// <---------------------- and this one
    use Illuminate\Foundation\Auth\User as Authenticatable; 
    
    class User extends Authenticatable
    {
        use CrudTrait; // <----- this
        use HasRoles; // <------ and this
    
        /**
         * Your User Model content
         */
    ```
6) [Optional] Add a menu item for it in ```resources/views/vendor/backpack/base/inc/sidebar_content.blade.php``` or ```menu.blade.php```:
    ```html
    <!-- Users, Roles, Permissions -->
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#">
            <i class="nav-icon la la-users"></i> Authentication
        </a>
    
        <ul class="nav-dropdown-items">
            <li class="nav-item">
                <a class="nav-link" href="{{ backpack_url('role') }}">
                    <i class="nav-icon la la-id-badge"></i><span>Roles</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ backpack_url('permission') }}">
                    <i class="nav-icon la la-key"></i><span>Permissions</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ backpack_url('user') }}">
                    <i class="nav-icon la la-user"></i>
                    <span>Users</span>
                </a>
            </li>
        </ul>
    </li>
    ```

## Change log

## Documentation
`Coming soon`

## License

## Credits
All original developers of 
 - [Backpack/PermissionManager](https://github.com/Laravel-Backpack/PermissionManager)
 - [spatie/laravel-permission](https://github.com/spatie/laravel-permission)
 - [Laravel Backpack](https://backpackforlaravel.com)
 