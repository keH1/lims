<?php


/**
 * Пожарная безопасность
 * Class FireSafety
 */
class FireSafety extends Model
{
    /**
     * @param array $data
     * @return int
     */
    public function addFireSafetyLog(array $data): int
    {
        $sqlData = $this->prepearTableData('fire_safety_log', $data);
        $sqlData['created_by'] = (int)$_SESSION['SESS_AUTH']['USER_ID'];

        if (!isset($data['practice_date']) || $data['practice_date'] === '' || $data['practice_date'] === null) {
            $sqlData['practice_date'] = 'NULL';
        }

        $result = $this->DB->Insert('fire_safety_log', $sqlData);

        return intval($result);
    }

    /**
     * @param array $filter
     * @return array
     */
    public function getFireSafetyLog(array $filter = []): array
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'fsl.id',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
            // из $filter собирать строку $where тут
            // формат такой: $where .= "что-то = чему-то AND ";
            // или такой:    $where .= "что-то LIKE '%чему-то%' AND ";
            // слева без пробела, справа всегда AND пробел

            // работа с фильтрами
            if (!empty($filter['search'])) {
                // Дата проведения теоретического инструктажа
                if (isset($filter['search']['theory_date'])) {
                    $where .= "LOCATE('{$filter['search']['theory_date']}', DATE_FORMAT(fsl.theory_date, '%d.%m.%Y')) > 0 AND ";
                }
                if ( isset($filter['search']['dateStart']) ) {
                    $where .= "(fsl.theory_date >= '{$filter['search']['dateStart']}' AND fsl.theory_date <= '{$filter['search']['dateEnd']}') AND ";
                }

                // Вид проводимого инструктажа
                if (isset($filter['search']['instruction_type'])) {
                    $where .= "fsl.instruction_type LIKE '%{$filter['search']['instruction_type']}%' AND ";
                }

                // Фамилия, имя, отчество инструкируемого
                if (isset($filter['search']['instructed_name'])) {
                    $where .= "CONCAT(u.LAST_NAME, ' ', u.NAME, ' ', u.SECOND_NAME) LIKE '%{$filter['search']['instructed_name']}%' AND ";
                }

                // Профессия, должность инструкируемого
                if (isset($filter['search']['instructed_position'])) {
                    $where .= "u.WORK_POSITION LIKE '%{$filter['search']['instructed_position']}%' AND ";
                }

                // Фамилия, имя, отчество  инструктирующего, номер документа об образовании и (или) квалификации, документа об обучении
                if (isset($filter['search']['theory_instructor_fio_doc'])) {
                    $where .= "CONCAT(fsl.theory_instructor_lastname, ' ', fsl.theory_instructor_name, ' ', 
                            fsl.theory_instructor_secondname, ', ', fsl.theory_instructor_doc) LIKE '%{$filter['search']['theory_instructor_fio_doc']}%' AND ";
                }

                // Дата проведения практического инструктажа
                if (isset($filter['search']['practice_date'])) {
                    $where .= "LOCATE('{$filter['search']['practice_date']}', DATE_FORMAT(fsl.practice_date, '%d.%m.%Y')) > 0 AND ";
                }

                // Фамилия, имя, отчество инструктирующего, номер документа об образовании и (или) квалификации, документа об обучении
                if (isset($filter['search']['practice_instructor_name_doc'])) {
                    $where .= "CONCAT(fsl.practice_instructor_lastname, ' ', fsl.practice_instructor_name, ' ', 
                            fsl.practice_instructor_secondname, ', ', fsl.practice_instructor_doc) LIKE '%{$filter['search']['practice_instructor_name_doc']}%' AND ";
                }
            }

            // везде
            if (isset($filter['search']['everywhere'])) {
                $where .=
                    "";
            }
        }


        // работа с сортировкой
        if (!empty($filter['order'])) {
            if ($filter['order']['dir'] === 'asc') {
                $order['dir'] = 'ASC';
            }

            switch ($filter['order']['by']) {
                case 'theory_date':
                    $order['by'] = 'fsl.theory_date';
                    break;
                case 'instruction_type':
                    $order['by'] = 'fsl.instruction_type';
                    break;
                case 'instructed_name':
                    $order['by'] = "CONCAT(u.LAST_NAME, ' ', u.NAME, ' ', u.SECOND_NAME)";
                    break;
                case 'instructed_position':
                    $order['by'] = 'u.WORK_POSITION';
                    break;
                case 'theory_instructor_fio_doc':
                    $order['by'] = "CONCAT(fsl.theory_instructor_lastname, ' ', fsl.theory_instructor_name, ' ', 
                            fsl.theory_instructor_secondname, ', ', fsl.theory_instructor_doc)";
                    break;
                case 'practice_date':
                    $order['by'] = 'fsl.practice_date';
                    break;
                case 'practice_instructor_name_doc':
                    $order['by'] = "CONCAT(fsl.practice_instructor_lastname, ' ', fsl.practice_instructor_name, ' ', 
                            fsl.practice_instructor_secondname, ', ', fsl.practice_instructor_doc)";
                    break;
                default:
                    $order['by'] = 'fsl.id';
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

        $where .= "1 ";

        $result = [];

        $data = $this->DB->Query(
            "SELECT fsl.*,
                        CONCAT(u.LAST_NAME, ' ', u.NAME, ' ', u.SECOND_NAME) AS instructed_name, 
                        u.WORK_POSITION AS instructed_position, 
                        CONCAT(fsl.theory_instructor_lastname, ' ', fsl.theory_instructor_name, ' ', 
                            fsl.theory_instructor_secondname, ', ', fsl.theory_instructor_doc) AS theory_instructor_fio_doc, 
                        CONCAT(fsl.practice_instructor_lastname, ' ', fsl.practice_instructor_name, ' ', 
                            fsl.practice_instructor_secondname, ' ', fsl.practice_instructor_doc) AS practice_instructor_name_doc
                    FROM fire_safety_log fsl 
                    LEFT JOIN b_user u ON fsl.instructed_id = u.ID
                    WHERE {$where}
                    ORDER BY {$order['by']} {$order['dir']} {$limit}"
        );

        $dataTotal = $this->DB->Query(
            "SELECT fsl.* 
                    FROM fire_safety_log fsl 
                    LEFT JOIN b_user u ON fsl.instructed_id = u.ID
                    WHERE {$where}"
        )->SelectedRowsCount();
        $dataFiltered = $this->DB->Query(
            "SELECT fsl.* 
                    FROM fire_safety_log fsl 
                    LEFT JOIN b_user u ON fsl.instructed_id = u.ID
                    WHERE {$where}"
        )->SelectedRowsCount();

        while ($row = $data->Fetch()) {
            $row['ru_theory_date'] = date('d.m.Y', strtotime($row['theory_date']));
            $row['ru_practice_date'] = isset($row['practice_date']) ? date('d.m.Y', strtotime($row['practice_date'])): '';

            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal;
        $result['recordsFiltered'] = $dataFiltered;

        return $result;
    }
}