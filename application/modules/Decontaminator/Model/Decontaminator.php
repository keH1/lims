<?php


class Decontaminator extends Model
{
    public string $location = '/decontaminator/list/';

    public function getList($filter = []): array
    {
        $filtersForGetList = [
            'having' => "1 ", //Затычка, что бы не было пустого HAVING в SQL запросе
            'limit' => "",
            'order' => "sampling_id DESC", // Запрос к БД ORDER BY 'order' Задается значение по умолчанию
        ];
        $result['recordsTotal'] = count($this->getFromSQL('getList', $filtersForGetList));

        if (!empty($filter['search'])) {
            foreach ($filter['search'] as $key => $item) {
                $filtersForGetList['having'] .= "AND $key LIKE '%$item%'";
            }
        }
        $result['recordsFiltered'] = count($this->getFromSQL('getList', $filtersForGetList));

        if (!empty($filter['order'])) {
            $filtersForGetList['order'] = "{$filter['order']['by']} {$filter['order']['dir']} ";
        }

        $filtersForGetList['limit'] = "LIMIT {$filter['paginate']['start']}, {$filter['paginate']['length']}";

        return array_merge($result, $this->getFromSQL('getList', $filtersForGetList));
    }

    public function addToSQL(array $data, string $type = null): int
    {
        $namesTable = [
            'decontaminator'
        ];

        if ($type == null) {
            $name = array_key_first($data);
            if (!in_array($name, $namesTable, true)) {
                return 0;
            }
            $dataAdd = $data[$name];
        }

        foreach ($dataAdd as $key => $item) {
            if (is_string($item)) {
                $dataAdd[$key] = $this->quoteStr($this->DB->ForSql(trim($item)));
            }
        }

        return $this->DB->Insert($name, $dataAdd);
    }

    public function getFromSQL(
        string $name,
        array $filters = null
    ): array {
        $namesTable = [
            'allRecord' => 'water',
            'decontaminator_type' => 'decontaminator_type'
        ];
        $response = [];
        if (isset($namesTable[$name])) {
            $requestFromSQL = $this->DB->Query("SELECT * from $namesTable[$name]");
        }

        if ($name == 'getList') {
            $requestFromSQL = $this->DB->Query(
                "SELECT OBJECT,TYPE_OBORUD,FACTORY_NUMBER,INV_NUM, unit_fridge.name,
                        CONCAT(first_range,' — ',last_range,' °C' ) AS range_full
                         FROM fridge
                        JOIN unit_fridge ON fridge.id_unit_fridge =unit_fridge.id
                        JOIN ba_oborud ON fridge.id_ba_oborud=ba_oborud.ID
            HAVING {$filters['having']}
            ORDER BY {$filters['order']}
            {$filters['limit']}
        "
            );
        }
        if ($name == 'oborud') {
            $requestFromSQL = $this->DB->Query(
                "
                SELECT ID AS id,
                CONCAT(
                    SUBSTRING(OBJECT,1,31),' ',SUBSTRING(TYPE_OBORUD,1,21),' Зав №',FACTORY_NUMBER
                    ) AS name
                FROM ba_oborud
                WHERE IDENT='VO'
                AND ID NOT IN (select id_ba_oborud from decontaminator)
                AND NAME LIKE '%зар%'
             "
            );
        }
        if ($name == 'decontaminator') {
            $requestFromSQL = $this->DB->Query(
                "
                           SELECT ba_oborud.ID AS id,
                CONCAT(
                    SUBSTRING(OBJECT,1,31),' ',SUBSTRING(TYPE_OBORUD,1,21),' Зав №',FACTORY_NUMBER
                    ) AS name
                FROM ba_oborud
                JOIN decontaminator ON decontaminator.id_ba_oborud=ba_oborud.ID 
             "
            );
        }


        while ($row = $requestFromSQL->Fetch()) {
            $response[] = $row;
        }
        return $response;
    }
}

