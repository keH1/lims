<?php

/**
 * Класс модель для Зернового состава
 * Class Grain
 */
class Grain extends Model
{
    const SEAVE = [
        0 => [
            "seave_main_type" => "round",
            "seave_type" => [
                0 => [
                    "type_to_input" => 200,
                    "type_to_title" => "Обечайка 200",
                    "values" => [
                        "0,05 (сетка)",  "0,063 (сетка)",     "0,071 (сетка)",    "0,1 (сетка)",
                        "0,125 (сетка)", "0,140 (сетка)",     "0,160 (сетка)",    "0,250 (сетка)",
                        "0,315 (сетка)", "0,5 (сетка)",       "0,63 (сетка)",     "0,7 (сетка)",
                        "0,9 (сетка)",   "1,25 (перф.)", "2,5 (перф.)", "5,0 (перф.)"
                    ]
                ],
                1 => [
                    "type_to_input" => 300,
                    "type_to_title" => "Обечайка 300",
                    "values" => [
                        "1,0 (сетка)",        "2,0 (сетка)", "3,0", "6,5", "7,5 (перф.)",
                        "10,0 (перф.)",  "12,5 (перф.)",  "15,0 (перф.)",  "17,5 (перф.)",
                        "18,75 (перф.)", "20,0 (перф.)",  "25,0 (перф.)",  "30,0 (перф.)",
                        "40,0 (перф.)",  "50,0 (перф.)",  "55,0 (перф.)",  "60,0 (перф.)",
                        "70,0 (перф.)",  "80,0 (перф.)",  "87,5 (перф.)",  "90,0 (перф.)",
                        "95,0 (перф.)",  "100,0 (перф.)", "110,0 (перф.)", "120,0 (перф.)",
                        "150,0 (перф.)"
                    ]
                ]
            ]
        ],
        1 => [
            "seave_main_type" => "square",
            "seave_type" => [
                0 => [
                    "type_to_input" => 200,
                    "type_to_title" => "Обечайка 200",
                    "values" => [
                        "1,0 (сетка)", "1,6", "2,0 (сетка)",       "2,8 (сетка)",       "4,0 (сетка)",
                        "5,6 (перф.)",   "6,3 (перф.)",  "8,0 (сетка)",       "11,2 (перф.)",
                        "16,0 (перф.)",  "22,4 (перф.)", "31,5 (перф.)", "45,0 (перф.)"
                    ]
                ],
                1 => [
                    "type_to_input" => 300,
                    "type_to_title" => "Обечайка 300",
                    "values" => [
                        "2,8 (перф.)",  "4,0 (перф.)",   "5,6 (перф.)",  "6,3 (перф.)",
                        "8,0 (перф.)",  "11,2 (перф.)",  "14,0 (перф.)", "16,0 (перф.)",
                        "22,4 (перф.)", "31,5 (перф.)",  "45,0 (перф.)", "63,0 (перф.)",
                        "90,0 (перф.)", "126,0 (перф.)", "180,0 (перф.)"
                    ]
                ]
            ]
        ],
    ];

    public function getGrainGostList(int $grainListID)
    {
        $result = [];
        $ba_gost = $this->DB->Query("SELECT bg.ID AS gost_id,
                                            bg.GOST,
                                            bg.GOST_PUNKT,
                                            bg.SPECIFICATION,
                                            zr.ID AS grain_id
                                     FROM ba_gost AS bg

                                     LEFT JOIN ZERN AS zr
                                     ON bg.ID = zr.GOST_ID

                                     WHERE bg.NON_ACTUAL != 1
        ");

        while ($gost = $ba_gost->Fetch()) {
            $result[] = [
                "gost_id" => $gost['gost_id'],
                "gost" => $gost['GOST'],
                "gost_point" => $gost['GOST_PUNKT'],
                "gost_specification" => $gost['SPECIFICATION'],
                "grain_list_id" => $gost['grain_id']
            ];
        }

        return $result;
    }

    public function getGrainSeaveSize()
    {
        return SELF::SEAVE;
    }

    public function getGrainSeaveValues(int $grainListID)
    {
        $result = [];
        $query = $this->DB->Query("SELECT *
                                   FROM ZERN
                                   WHERE ID = " . $grainListID
        )->Fetch();

        $result = [
            "name" => $query['NAME'],
            "data" => json_decode($query['DATA'], true)
        ];

        return $result;
    }

    public function update(int $grainListID, array $post)
    {
        $data['NAME'] = $post['grain_list_name'];
        $data['GOST_ID'] = isset($post['grain_list_gost']) ? $post['grain_list_gost'] : "";
        $data['DATA'] =  json_encode($post['grain'], JSON_UNESCAPED_UNICODE);

        $sqlData = $this->prepearTableData('ZERN', $data);

        $this->DB->Update("ZERN", $sqlData,
                          "WHERE ID = " . $grainListID);
    }

    public function getDataToJournalGrain(array $filter = []): array
    {
        $where = "";
        $limit = "";
        $order = [
            'by' => 'NAME',
            'dir' => 'DESC'
        ];

        if ( !empty($filter) ) {
            // работа с фильтрами
            if ( !empty($filter['search']) ) {
                if ( isset($filter['search']['material_name']) ) {
                    $where .= "NAME LIKE '%{$filter['search']['material_name']}%' AND ";
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

        $data = $this->DB->Query("SELECT `ID`,
                                         `NAME`
                                  FROM ZERN
                                  WHERE {$where}
                                  GROUP BY ID
                                  ORDER BY {$order['by']} {$order['dir']} {$limit}
        ");

        $dataTotal = $this->DB->Query("SELECT count(*) val
                                       FROM ZERN
        ")->Fetch();

        $dataFiltered = $this->DB->Query("SELECT count(*) val
                                          FROM ZERN
        ")->Fetch();

        while ($row = $data->Fetch()) {
            $row['material_name'] = $row['NAME'];
            $result[] = $row;
        }

        $result['recordsTotal'] = $dataTotal['val'];
        $result['recordsFiltered'] = $dataFiltered['val'];

        return $result;
    }
}