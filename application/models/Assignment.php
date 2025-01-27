<?php
/**
 * Модель для распределения оборудования
 * Class Assignment
 */
class Assignment extends Model
{
    public function getList()
    {
        $data = $this->DB->Query(
            "SELECT `ID`,
                    `OBJECT`
             FROM ba_oborud
             ORDER BY ID 
        ");

        $response = [];
        while ($row = $data->Fetch()) {
            $response[] = $row;
        }

        return $response;
    }

    public function SetEquipment (array $data)
    {

        $key = array_key_first($data);
        $updateFor = "'" . stristr(array_key_first($data), "-", true) . "'";

        //$values = serialize($data[$key]);
        $param = [
            // 'equipment' => "'" . $this->DB->ForSql($values) . "'"
            'equipment' => "'" . json_encode($data[$key], JSON_UNESCAPED_UNICODE) . "'"
        ];

        // echo '<pre>';
        // print_r($param);
        // echo '<pre>';
        // die;

        $where = "WHERE equipment_for = {$updateFor}";
        $this->DB->Update('journal_equipment', $param, $where);
    }

    public function GetEquipment (string $for)
    {
        $data = $this->DB->Query(
            "SELECT `equipment`
             FROM journal_equipment
             WHERE equipment_for = '" . $for . "'
        ")->Fetch();

        return json_decode($data['equipment'], true);
    }

    public function GetNameEquipment (string $for)
    {
        $data = $this->DB->Query(
            "SELECT `equipment`
             FROM journal_equipment
             WHERE equipment_for = '" . $for . "'
        ")->Fetch();

        $equip = json_decode($data['equipment'], true);

        $response = [];
        foreach ($equip as $key => $item) {
            $equipment = $this->DB->Query(
                "SELECT `OBJECT`,
                        `FACTORY_NUMBER`
                 FROM ba_oborud
                 WHERE ID = '" . $item . "'
            ")->Fetch();

            $response[$key]['ID'] = $item;
            $response[$key]['OBJECT'] = $equipment['OBJECT'];
            $response[$key]['FACTORY_NUMBER'] = $equipment['FACTORY_NUMBER'];
        }

        return $response;
    }
}
