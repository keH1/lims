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
            'by' => 'h.DATE',
            'dir' => 'DESC'
        ];

        if ( !empty($filter) ) {
            // работа с фильтрами
            if ( !empty($filter['search']) ) {
				// Дата
				if (isset($filter['search']['dateStart'])) {
					$where .= "(h.DATE >= '{$filter['search']['dateStart']}' AND h.DATE <= '{$filter['search']['dateEnd']}') AND ";
				}
                if (isset($filter['search']['REQUEST'])) {
                    $where .= "h.REQUEST LIKE '%{$filter['search']['REQUEST']}%' AND ";
                }
				if (isset($filter['search']['PROT_NUM'])) {
                    $protocolNumber = $filter['search']['PROT_NUM'];
                    if (ctype_digit($protocolNumber)) {
                        $where .= "h.PROT_NUM = '{$protocolNumber}' AND ";
                    } else {
                        $where .= "(1=0) AND ";
                    }
                }
				if (isset($filter['search']['TZ_ID'])) {
                    $where .= "h.TZ_ID = '{$filter['search']['TZ_ID']}' AND ";
                }
				if (isset($filter['search']['DATE'])) {
                    $where .= "DATE_FORMAT(h.DATE, '%d.%m.%Y %H:%i:%s') LIKE '%{$filter['search']['DATE']}%' AND ";
                }
				if (isset($filter['search']['TYPE'])) {
                    $where .= "h.TYPE LIKE '%{$filter['search']['TYPE']}%' AND ";
                }
				if (isset($filter['search']['ASSIGNED'])) {
                    $where .= "h.ASSIGNED LIKE '%{$filter['search']['ASSIGNED']}%' AND ";
                }
            }

            // работа с сортировкой
            if ( !empty($filter['order']) ) {
                if ( $filter['order']['dir'] === 'asc' ) {
                    $order['dir'] = 'ASC';
                }
                switch ($filter['order']['by']) {
                    case 'ASSIGNED':
                        $order['by'] = 'LEFT(TRIM(h.ASSIGNED), 1)';
                        break;
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

        $data = $this->DB->Query("SELECT DATE_FORMAT(h.DATE, '%d.%m.%Y %H:%i:%s') AS DATE,
                                         h.TYPE, h.PROT_NUM, h.TZ_ID, h.ASSIGNED, h.REQUEST
                                  FROM HISTORY AS h
                                  WHERE {$where}
                                  ORDER BY {$order['by']} {$order['dir']} {$limit}
        ");

        $dataTotal = $this->DB->Query("SELECT count(*) val
                                       FROM HISTORY AS h
        ")->Fetch();

        $dataFiltered = $this->DB->Query("SELECT count(*) val
                                          FROM HISTORY AS h
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