<?php

class Object extends Model
{
    public function add($data)
    {
        $data['KM'] = floatval($data['KM']);
        $data['ID_COMPANY'] = intval($data['ID_COMPANY']);
        $data['CITY_ID'] = intval($data['CITY_ID']);

        $sqlData = $this->prepearTableData('DEV_OBJECTS', $data);

        return $this->DB->Insert('DEV_OBJECTS', $sqlData);
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
        $where = "WHERE ID = {$id}";
        return $this->DB->Update($table, $data, $where);
    }


}