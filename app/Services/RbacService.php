<?php

namespace App\Services;

use App\Model\PivotRolePermission;
use App\Model\RbacMenu;

class RbacService
{
    /**
     * 获取权限编号组
     * @param int $accountId 用户编号
     * @return array
     */
    public function getPermissionIds($accountId)
    {
        return PivotRolePermission::whereIn('rbac_role_id', function ($query) use ($accountId) {
            $query->from('pivot_role_accounts')->select('rbac_role_id')->where('account_id', $accountId);
        })->pluck('rbac_permission_id');
    }

    /**
     * 获取菜单
     * @param int $accountId 用户编号
     * @return mixed
     */
    public function getMenus($accountId)
    {
        $menus = RbacMenu::with(['parent'])
            ->whereIn('id', function ($query) use ($accountId) {
                $query->from('pivot_role_menus')->select('rbac_menu_id')->whereIn('rbac_role_id', function ($query) use ($accountId) {
                    $query->from('pivot_role_accounts')->select('rbac_role_id')->where('account_id', $accountId);
                });
            })
            ->orderBy('sort')
            ->orderByDesc('id')
            ->get();

        return $menus;
    }

    /**
     * 组合成树
     * @param array $data 等待操作的数组
     * @param int $parentId 父级编号
     * @return array
     */
    public function toTree(array $data, $parentId = 0)
    {
        $tree = [];
        foreach ($data as $k => $v) {
            if ($v['parent_id'] == $parentId) {        //父亲找到儿子
                $v['sub'] = self::toTree($data, $v['id']);
                $tree[] = $v;
//                unset($data[$k]);
            }
        }
        return $tree;
    }
}
