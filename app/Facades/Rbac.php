<?php
namespace App\Facades;
use App\Services\RbacService;
use Illuminate\Support\Facades\Facade;


/**
 * Class Rbac
 * @method static getPermissionIds($accountId)
 * @method static getMenus($accountId)
 * @method static toTree(array $data)
 * @method static parseTree(array $tree)
 * @package App\Facades
 */
class Rbac extends Facade{
    protected static function getFacadeAccessor()
    {
        return RbacService::class;
    }
}
