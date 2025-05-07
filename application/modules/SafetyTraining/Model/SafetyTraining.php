<?php


/**
 * Охрана труда
 * Class SafetyTraining
 */
class SafetyTraining extends Model
{
    /**
     * @param array $data
     * @return int
     */
    public function addSafetyTrainingLog(array $data): int
    {
        $sqlData = $this->prepearTableData('safety_training_log', $data);
        $sqlData['created_by'] = App::getUserId();
        $sqlData['organization_id'] = App::getOrganizationId();
        $result = $this->DB->Insert('safety_training_log', $sqlData);
        return intval($result);
    }

    /**
     * @param array $filter
     * @return array
     */
    public function getSafetyTrainingLog(array $filter = []): array
    {
        $organizationId = App::getOrganizationId();
        $where = "";
        $limit = "";
        $order = [
            'by' => 'id',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if (!empty($filter['search'])) {
                // ФИО
                if (isset($filter['search']['fio'])) {
                    $where .= "TRIM(CONCAT(last_name, ' ', name, ' ', second_name)) LIKE '%{$filter['search']['fio']}%' AND ";
                }

                // Вид инструктажа
                if (isset($filter['search']['training_type'])) {
                    $where .= "training_type LIKE '%{$filter['search']['training_type']}%' AND ";
                }

                // Дата инструктажа
                if (isset($filter['search']['training_date'])) {
                    $where .= "LOCATE('{$filter['search']['training_date']}', DATE_FORMAT(training_date, '%d.%m.%Y')) > 0 AND ";
                }
                if ( isset($filter['search']['dateStart']) ) {
                    $where .= "(training_date >= '{$filter['search']['dateStart']}' AND training_date <= '{$filter['search']['dateEnd']}') AND ";
                }
            }

            // везде
            if (isset($filter['search']['everywhere'])) {
                $where .= "1 AND ";
            }
        }

        $where .= "organization_id = {$organizationId}";

        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'fio':
                    $order['by'] = "LEFT(TRIM(CONCAT(last_name, ' ', name, ' ', second_name)), 1)";
                    break;
                case 'training_type':
                    $order['by'] = 'training_type';
                    break;
                case 'training_date':
                    $order['by'] = 'training_date';
                    break;
                case 'instructed_position':
                    $order['by'] = 'u.WORK_POSITION';
                    break;
                default:
                    $order['by'] = 'id';
            }
        }


        // работа с пагинацией
        if (isset($filter['paginate'])) {
            $offset = 0;
            // количество строк на страницу
            if (isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0) {
                $length = $filter['paginate']['length'];

                if (isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0) {
                    $offset = $filter['paginate']['start'];
                }
                $limit = "LIMIT {$offset}, {$length}";
            }
        }

        $result = [];

        $data = $this->DB->Query(
            "SELECT *,
                        TRIM(CONCAT(last_name, ' ', name, ' ', second_name)) AS fio 
                    FROM safety_training_log 
                    WHERE {$where}
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT * 
                    FROM safety_training_log 
                    WHERE {$where}"
        )->SelectedRowsCount();
        $dataFiltered = $this->DB->Query(
            "SELECT * 
                    FROM safety_training_log 
                    WHERE {$where}"
        )->SelectedRowsCount();

        while ($row = $data->Fetch()) {
            $row['ru_training_date'] = isset($row['training_date']) ? date('d.m.Y', strtotime($row['training_date'])): '';

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }
}
