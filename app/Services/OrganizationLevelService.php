<?php

namespace App\Services;

use App\Model\Organization;
use Illuminate\Support\Facades\Session;

class OrganizationLevelService
{
    private $_organizationIds = [];

    /**
     * 通过session获取深度的机构信息
     * @return array
     * @throws \Exception
     */
    public function getDeepBySession()
    {
        $organizationId = Session::get('account.organization_id');
        if (!$organizationId) throw new \Exception('用户数据中不包含机构信息');
        return array_merge($this->getDeep($organizationId), [Session::get('account.organization_id')]);
    }

    /**
     * 获取深度的机构信息
     * @param int $organizationId 机构编号
     * @return array
     */
    public function getDeep($organizationId)
    {
        $organizations = Organization::where('parent_id', $organizationId)->select('id')->get();
        if ($organizations) {
            $tmp = [];
            foreach ($organizations as $organization) {
                $tmp[] = $organization->id;
                $this->_organizationIds[] = $organization->id;
                $this->getDeep($organization->id);
            }
            return $this->_organizationIds;
        } else {
            return $this->_organizationIds;
        }
    }
}
