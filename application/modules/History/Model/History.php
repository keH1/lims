<?php

class History extends Model
{
	public function addHistory($data)
	{
		$sql = $this->prepearTableData('HISTORY', $data);
		$this->DB->Insert('HISTORY', $sql);
	}

	/**
	 * @param array $filter
	 * @return array
	 */
	public function getDataToJournalHistory(array $filter = []): array
	{
        $where = "";
        $limit = "";
        $order = [
            'by' => 'DATE',
            'dir' => 'DESC'
        ];

        if ( !empty($filter) ) {
            // работа с фильтрами
            if ( !empty($filter['search']) ) {
				// Дата
				if ( isset($filter['search']['dateStart']) ) {
					$where .= "(DATE >= '{$filter['search']['dateStart']}' AND DATE <= '{$filter['search']['dateEnd']}') AND ";
				}
                if ( isset($filter['search']['REQUEST']) ) {
                    $where .= "REQUEST LIKE '%{$filter['search']['REQUEST']}%' AND ";
                }
				if ( isset($filter['search']['PROT_NUM']) ) {
                    $where .= "PROT_NUM = '{$filter['search']['PROT_NUM']}' AND ";
                }
				if ( isset($filter['search']['TZ_ID']) ) {
                    $where .= "TZ_ID = '{$filter['search']['TZ_ID']}' AND ";
                }
				if ( isset($filter['search']['DATE']) ) {
                    $where .= "DATE LIKE '%{$filter['search']['DATE']}%' AND ";
                }
				if ( isset($filter['search']['TYPE']) ) {
                    $where .= "TYPE LIKE '%{$filter['search']['TYPE']}%' AND ";
                }
				if ( isset($filter['search']['ASSIGNED']) ) {
                    $where .= "ASSIGNED LIKE '%{$filter['search']['ASSIGNED']}%' AND ";
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }
            }

            // работа с пагинацией
            if ( isset($filter['paginate']) ) {
                $offset = 0;
                // количество строк на страницу
                if ( isset($filter['paginate']['length']) && $filter['paginate']['length'] > 0 ) {
                    $length = $filter['paginate']['length'];

                    if ( isset($filter['paginate']['start']) && $filter['paginate']['start'] > 0 ) {
                        $offset = $filter['paginate']['start'];
                    }
                    $limit = "LIMIT {$offset}, {$length}";
                }
            }
        }
        $where .= "1 ";

        $result = [];

        $data = $this->DB->Query("SELECT *
                                  FROM HISTORY
                                  WHERE {$where}
                                  GROUP BY ID
                                  ORDER BY {$order['by']} {$order['dir']} {$limit}
        ");

        $dataTotal = $this->DB->Query("SELECT count(*) val
                                       FROM HISTORY
        ")->Fetch();

        $dataFiltered = $this->DB->Query("SELECT count(*) val
                                          FROM HISTORY
										  WHERE {$where}
        ")->Fetch();

        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal['val'];
        $result['recordsFiltered'] = $dataFiltered['val'];

        return $result;
    }
}