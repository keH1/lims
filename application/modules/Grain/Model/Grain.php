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
        2 => [
            "seave_main_type" => "round_info",
            "seave_type" => [
                0 => [
                    "type_to_input" => '',
                    "type_to_title" => "Сита",
                    "values" => [
                        0  => "Сито 200 мм",  44 => "Сито 180 мм",  43 => "Сито 126 мм",  57 => "Сито 150 мм",  50 => "Сито 125 мм",  1  => "Сито 120 мм",
                        58 => "Сито 105 мм",  2  => "Сито 100 мм",  34 => "Сито 90 мм",   40 => "Сито 87,5 мм", 3  => "Сито 80 мм",   41 => "Сито 70 мм",
                        33 => "Сито 63 мм",   4  => "Сито 60 мм",   42 => "Сито 55 мм",   38 => "Сито 50 мм",   32 => "Сито 45 мм",  5  => "Сито 40 мм",
                        59 => "Сито 35 мм",   6  => "Сито 31,5 мм", 39 => "Сито 30 мм",   37 => "Сито 25 мм",   7  => "Сито 22,4 мм", 8  => "Сито 20 мм",

                        74 => "Сито менее 20 мм", 47 => "Сито 17,5 мм", 9  => "Сито 16 мм",   10 => "Сито 15 мм",   35 => "Сито 12,5 мм", 11 => "Сито 11,2 мм",
                        12 => "Сито 10 мм",   73 => "Сито менее 10 мм", 13 => "Сито 8 мм",    36 => "Сито 7,5 мм",  31 => "Сито 5,6 мм", 14 => "Сито 5 мм",
                        72 => "Сито менее 5 мм", 15 => "Сито 4 мм",     46 => "Сито 3 мм",    45 => "Сито 2,8 мм",  16 => "Сито 2,5 мм", 71 => "Сито менее 2,5 мм",
                        17 => "Сито 2 мм",    70 => "Сито 1,6 мм", 18 => "Сито 1,25 мм",  19 => "Сито 1 мм",    66 => "Сито менее 1 мм", 20 => "Сито 0,63 мм",

                        21 => "Сито 0,5 мм",  22 => "Сито 0,315 мм", 23 => "Сито 0,25 мм", 24 => "Сито 0,16 мм", 25 => "Сито 0,125 мм", 26 => "Сито 0,1 мм",
                        27 => "Сито 0,071 мм",28 => "Сито 0,063 мм", 29 => "Сито 0,05 мм", 30 => "Сито 0,010 мм", 60 => "Сито 0,005 мм", 61 => "Сито 0,001 мм",
                        62 => "Сито менее 0,001 мм", 63 => "Сито 0,80 мм", 64 => "Сито 0,40 мм", 65 => "Сито 0,20 мм", 67 => "Сито менее 0,16 мм", 68 => "Сито менее 0,125 мм",
                        69 => "Сито менее 0,05 мм", 75 => "Сито 56 мкм", 76 => "Сито 20 мкм", 77 => "Сито 10 мкм", 78 => "Сито 5 мкм", 79 => "Сито 2 мкм",

                        80 => "Сито менее 2 мкм", 81 => "Сито 1000 мкм", 82 => "Сито 850 мкм", 83 => "Сито 700 мкм", 84 => "Сито 600 мкм", 85 => "Сито 500 мкм",
                        86 => "Сито менее 500 мкм",
                    ]

                ]
            ]
        ]
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

    public function getGrainSeaveSize(): array
    {
        return SELF::SEAVE;
    }

    public function getGrainSeaveValues(int $grainListID): array
    {
        $result = [];
        $query = $this->DB->Query("SELECT *
                                   FROM ZERN
                                   WHERE ID = " . $grainListID
        )->Fetch();

        $result = [
            "name" => $query['NAME'],
            "data" => json_decode($query['DATA'], true),
            "norm1" => unserialize($query['NORM1']),
            "norm2" => unserialize($query['NORM2']),
        ];

        return $result;
    }

    public function update(int $grainListID, array $post): void
    {
        $name = StringHelper::removeSpace($post['grain_list_name'] ?? '');
        $name = $this->DB->ForSql($name);
        $zern = $this->DB->Query("SELECT ID FROM ZERN WHERE NAME LIKE '{$name}'")->Fetch();

        if (empty($zern['ID'])) {
            $data['NAME'] = $name;
        }

        $data['GOST_ID'] = $post['grain_list_gost'] ?? '';
        $data['DATA'] =  json_encode($post['grain'], JSON_UNESCAPED_UNICODE);
        $data['NORM1'] =  serialize($post['NORM1']);
        $data['NORM2'] =  serialize($post['NORM2']);

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
                    $where .= "COALESCE(NULLIF(`NAME`, ''), 'Нет имени') LIKE '%{$filter['search']['material_name']}%' AND ";
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
                                         COALESCE(NULLIF(`NAME`, ''), 'Нет имени') AS `NAME` 
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

    public function addZern(string $name): int
    {
        $name = StringHelper::removeSpace($name);
        $name = $this->DB->ForSql($name);
        $zern = $this->DB->Query("SELECT ID FROM ZERN WHERE NAME LIKE '{$name}'")->Fetch();

        $zernId = (int)$zern['ID'];
        if (!empty($zernId)) {
            return $zernId;
        }

        $sqlData = $this->prepearTableData('ZERN', ['NAME' => $name]);
        $result = $this->DB->Insert('ZERN', $sqlData);

        return intval($result);
    }
}