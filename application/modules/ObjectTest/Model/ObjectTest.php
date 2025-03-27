<?php

class ObjectTest extends Model
{
    public function add($data) {
        $data['KM'] = floatval($data['KM']);
        $data['ID_COMPANY'] = intval($data['ID_COMPANY']);
        $data['CITY_ID'] = intval($data['CITY_ID']);

        $sqlData = $this->prepearTableData('DEV_OBJECTS', $data);
        $this->DB->Insert('DEV_OBJECTS', $sqlData);

        return intval($this->DB->LastID());
    }

    // Добавить города в справочник
    public function addSql($cityName)
    {
        $this->DB->Query("
            INSERT INTO `settlements` (name, type, country) 
            VALUES ('{$cityName}', 'город', 'Россия')
        ");
    }


    public function update(array $data, string $table, int $id)
    {
        $sqlData = $this->prepearTableData($table, $data);

        $where = "WHERE ID = {$id}";
        return $this->DB->Update($table, $sqlData, $where);
    }


}