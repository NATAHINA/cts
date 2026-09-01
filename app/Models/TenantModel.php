<?php

namespace App\Models;

use CodeIgniter\Model;

class TenantModel extends Model
{
    protected $beforeFind = ['filterByTenant'];
    protected $beforeInsert = ['addTenantId'];

    protected function filterByTenant(array $data)
    {
        $tenantId = (int) session('tenant_id');

        if ($tenantId <= 0) {
            return $data;
        }

        if (isset($data['builder'])) {
            $data['builder']->where(
                $this->table . '.tenant_id',
                $tenantId
            );
        }

        return $data;
    }

    protected function addTenantId(array $data)
    {
        // $tenantId = (int) session('tenant_id');

        // if ($tenantId > 0) {
        //     $data['data']['tenant_id'] = $tenantId;
        // }

        if (! isset($data['data']['tenant_id'])) {
            $tenantId = (int) session('tenant_id');

            if ($tenantId > 0) {
                $data['data']['tenant_id'] = $tenantId;
            }
        }

        return $data;
    }
}