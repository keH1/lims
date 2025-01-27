<?php 
/**
 * Модель для контроля градуировки pH-метра
 * Class Graduationphmetr
 */
class Graduationphmetr extends Model
{
    public function getList(int $userId, array $filter = []): array
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'g.id',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            if ( !empty($filter['search']) ) {

                if (isset($filter['search']['date'])) {
                    $where .= "g.date LIKE '%{$filter['search']['date']}%' AND ";
                }

                if (isset($filter['paginate']) ) {
                    $offset = 0;
                    if ( isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0 ) {
                        $length = $filter['paginate']['length'];
    
                        if ( isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0 ) {
                            $offset = $filter['paginate']['start'];
                        }
                        $limit = "LIMIT {$offset}, {$length}";
                    }
                }
            } else {
                $where = 1;
            }

            if (isset($filter['equip']) || isset($filter['date'])) {
                $where = "";

                if (!empty($filter['equip'])) {
                    if ($filter['equip'] != "all") {
                        $where = "g.equipment = {$filter['equip']}";
                    } else {
                        $where = 1;
                    }
                }
                if (!empty($filter['date']) && empty($filter['equip'])) {
                    $date = new DateTimeImmutable($filter['date']);
                    $filterDate = "'" . $date->format('Y-m-d') . "'";

                    $where = "g.date = {$filterDate}";
                }
                if (!empty($filter['equip']) && !empty($filter['date'])) {

                    if ($filter['equip'] != "all") {
                        $selected = "g.equipment = {$filter['equip']} AND";
                    }
                    $date = new DateTimeImmutable($filter['date']);
                    $filterDate = "'" . $date->format('Y-m-d') . "'";
    
                    $where = "{$selected} g.date = {$filterDate}";
                }
            }
        }

        $data = $this->DB->Query(
            "SELECT g.date,
                    g.global_assigned,
                    gr.results,
                    gr.id_graduation,
                    bo.OBJECT,
                    bo.FACTORY_NUMBER
             FROM graduation g
             LEFT JOIN graduation_result gr
             ON g.id = gr.id_graduation
             LEFT JOIN ba_oborud bo
             ON g.equipment = bo.ID
             WHERE {$where}
             ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $response = [];

        while ($row = $data->Fetch()) {
            if ($row['results']) {
                $row['results'] = json_decode($row['results'], true);
            }
            $response[] = $row;
        }

        if (empty($response)) {
            return $response = [];
        }
       
        $data = $this->calculateGraduation($response);

        return $data;
    }

    public function setGraduation(array $data, int $user)
    {       
        $tables = [
            "graduation",
            "graduation_result"
        ];

        $newData = [];
        $id_graduation = "";
        foreach ($tables as $table) {
            if ($table == "graduation") {
                $newData['date'] = $data['graduation']['date'];
                $newData['equipment'] = $data['graduation']['equipment'];
            } else {
                $newData = [];
                $newData['results'] = json_encode($data['graduation']['result'], JSON_UNESCAPED_UNICODE);
                $newData['id_graduation'] = $id_graduation;
                $user = "";
            }
            
            $id_graduation = $this->insertToSQL($newData, $table, $user);
        }
    }

    public function calculateGraduation(array $data)
    {
        $output = [];
        foreach ($data as $key => $item) {
            $inc = 0;
            foreach ($item['results'] as $keyRes => $res) {

                foreach ($res as $keyResult => $result) {

                    if ($inc == 0) {
                        $output[$key][$inc]['date'] = $item['date'];
                        $output[$key][$inc]['object'] = $item['OBJECT'];
                        $output[$key][$inc]['factory_number'] = $item['FACTORY_NUMBER'];
                        $output[$key][$inc]['global_assigned'] = $item['global_assigned'];
                    } else {
                        $output[$key][$inc]['date'] = "";
                        $output[$key][$inc]['object'] = "";
                        $output[$key][$inc]['factory_number'] = "";
                        $output[$key][$inc]['global_assigned'] = "";
                    }

                    $output[$key][$inc]['result_' . ($keyResult + 1)] = $result;

                    $output[$key][$inc]['infelicity_' . ($keyResult + 1)] = round($this->getInfelicity($result, $keyRes), 2);
                    if ($output[$key][$inc]['infelicity_' . ($keyResult + 1)] < 0.05) {
                        $output[$key][$inc]['conclusion_' . ($keyResult + 1)] = "Удовлетворительное";
                    } else {
                        $output[$key][$inc]['conclusion_' . ($keyResult + 1)] = "Неудовлетворительное";
                    }

                    $output[$key][$inc]['id_graduation'] = $item['id_graduation'];
                }
                $inc++;
            }
        }

        for ($i = 1; $i < count($output); $i++) {
            for ($j = 0; $j < count($output[$i]); $j++) {
                array_push($output[0], $output[$i][$j]);
            }
        }

        foreach ($output as $key => $item) {
            if ($key == 0) {
                $output[0] = $item;
            } else {
                unset($output[$key]);
            }
        }

        $output = $this->changeIdUserToName($output[0]);

        return $output;
    }

    public function getInfelicity(float $result, $keyConst)
    {
        $buffer = $this->DB->Query(
            "SELECT `value`
             FROM graduation_buffer
             WHERE id = '" . $keyConst . "'
        ")->Fetch();

        return abs($buffer['value'] - $result);
    }
}