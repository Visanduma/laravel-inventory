<?php


namespace Visanduma\LaravelInventory\Traits;


trait TableConfigs
{
    public function getTable(): string
    {
        return config('inventory.table_name_prefix') . "_" . $this->tableName;
    }

    public function getTableName($name)
    {
        return config('inventory.table_name_prefix') . "_" . $name;

    }
}
