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
        $sqlData['created_by'] = App::getUserId();
        $sqlData['organization_id'] = App::getOrganizationId();

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
        $organizationId = App::getOrganizationId();
        $where = "";
        $limit = "";
        $order = [
            'by' => 'fsl.id',
            'dir' => 'DESC'
        ];

        if (!empty($filter)) {
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
                    $where .= "TRIM(CONCAT_WS(' ', u.LAST_NAME, u.NAME, u.SECOND_NAME)) LIKE '%{$filter['search']['instructed_name']}%' AND ";
                }

                // Профессия, должность инструкируемого
                if (isset($filter['search']['instructed_position'])) {
                    $where .= "u.WORK_POSITION LIKE '%{$filter['search']['instructed_position']}%' AND ";
                }

                // Фамилия, имя, отчество  инструктирующего, номер документа об образовании и (или) квалификации, документа об обучении
                if (isset($filter['search']['theory_instructor_fio_doc'])) {
                    $where .= "TRIM(CONCAT(fsl.theory_instructor_lastname, ' ', fsl.theory_instructor_name, ' ', 
                            fsl.theory_instructor_secondname, ', ', fsl.theory_instructor_doc)) LIKE '%{$filter['search']['theory_instructor_fio_doc']}%' AND ";
                }

                // Дата проведения практического инструктажа
                if (isset($filter['search']['practice_date'])) {
                    $where .= "LOCATE('{$filter['search']['practice_date']}', DATE_FORMAT(fsl.practice_date, '%d.%m.%Y')) > 0 AND ";
                }

                // Фамилия, имя, отчество инструктирующего, номер документа об образовании и (или) квалификации, документа об обучении
                if (isset($filter['search']['practice_instructor_name_doc'])) {
                    $where .= "TRIM(CONCAT(fsl.practice_instructor_lastname, ' ', fsl.practice_instructor_name, ' ', 
                            fsl.practice_instructor_secondname, ', ', fsl.practice_instructor_doc)) LIKE '%{$filter['search']['practice_instructor_name_doc']}%' AND ";
                }
            }

            // везде
            if (isset($filter['search']['everywhere'])) {
                $where .=
                    "";
            }
        }

        if (isset($filter['sortByMaxDate']) && $filter['sortByMaxDate']) {
            $order['by'] = 'GREATEST(IFNULL(fsl.theory_date, "1000-01-01"), IFNULL(fsl.practice_date, "1000-01-01"))';
            
            if (!empty($filter['order']['dir'])) {
                $order['dir'] = $filter['order']['dir'];
            }
        } elseif (!empty($filter['order'])) {
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
                    $order['by'] = "LEFT(TRIM(CONCAT_WS(' ', u.LAST_NAME, u.NAME, u.SECOND_NAME)), 1)";
                    break;
                case 'instructed_position':
                    $order['by'] = 'u.WORK_POSITION';
                    break;
                case 'theory_instructor_fio_doc':
                    $order['by'] = "LEFT(TRIM(CONCAT(fsl.theory_instructor_lastname, ' ', fsl.theory_instructor_name, ' ', 
                            fsl.theory_instructor_secondname, ', ', fsl.theory_instructor_doc)), 1)";
                    break;
                case 'practice_date':
                    $order['by'] = 'fsl.practice_date';
                    break;
                case 'practice_instructor_name_doc':
                    $order['by'] = "LEFT(TRIM(CONCAT(fsl.practice_instructor_lastname, ' ', fsl.practice_instructor_name, ' ', 
                            fsl.practice_instructor_secondname, ', ', fsl.practice_instructor_doc)), 1)";
                    break;
                default:
                    $order['by'] = 'fsl.id';
            }
        }

        if (isset($filter['paginate'])) {
            $offset = 0;
            if (isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0) {
                $length = $filter['paginate']['length'];

                if (isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0) {
                    $offset = $filter['paginate']['start'];
                }
                $limit = "LIMIT {$offset}, {$length}";
            }
        }

        $where .= "organization_id =  {$organizationId} ";

        $result = [];

        $data = $this->DB->Query(
            "SELECT fsl.*,
                        TRIM(CONCAT_WS(' ', u.LAST_NAME, u.NAME, u.SECOND_NAME)) AS instructed_name, 
                        u.WORK_POSITION AS instructed_position, 
                        TRIM(CONCAT(fsl.theory_instructor_lastname, ' ', fsl.theory_instructor_name, ' ', 
                            fsl.theory_instructor_secondname, ' ', fsl.theory_instructor_doc)) AS theory_instructor_fio_doc, 
                        TRIM(CONCAT(fsl.practice_instructor_lastname, ' ', fsl.practice_instructor_name, ' ', 
                            fsl.practice_instructor_secondname, ' ', fsl.practice_instructor_doc)) AS practice_instructor_name_doc
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