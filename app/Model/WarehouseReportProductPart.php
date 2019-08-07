<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseReportProductPart extends Model
{
    use SoftDeletes;

    public static $OPERATION_DIRECTION = [
        'IN' => '入库',
        'OUT' => '出库'
    ];
    protected $fillable = [
        'warehouse_procurement_part_id',
        'warehouse_product_part_id',
        'number',
        'send_person_name',
        'send_person_phone',
        'in_at',
        'in_person_id',
        'fix_workflow_id',
        'operation_direction',
    ];

    public function getOperationDirectionAttribute($value)
    {
        return self::$OPERATION_DIRECTION[$value];
    }

    public function flipOperationDirection($value)
    {
        return array_flip(self::$OPERATION_DIRECTION)[$value];
    }

    public function warehouseProcurementPart()
    {
        return $this->hasOne(WarehouseProcurementPart::class, 'id', 'warehouse_procurement_part_id');
    }

    public function warehouseProductPart()
    {
        return $this->hasOne(WarehouseProductPart::class, 'id', 'warehouse_product_part_id');
    }

    public function inPerson()
    {
        return $this->hasOne(Account::class, 'id', 'in_person_id');
    }

    public function fixWorkflow()
    {
        return $this->hasOne(FixWorkflow::class, 'id', 'fix_workflow_id');
    }
}
