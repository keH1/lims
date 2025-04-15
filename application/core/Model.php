<?php

/**
 * Ядро - модель
 * Class Model
 */
class Model
{
    /** @var CDatabase $DB */
    protected $DB;
    protected string $DBName;
    protected int $dayInYear = 360;

    protected array $filtersForGetListDefault = [
        'having' => "1 ", //Затычка, что бы не было пустого HAVING в SQL запросе
        'limit' => "",
        'order' => "id DESC", // Запрос к БД ORDER BY 'order' Задается значение по умолчанию
        'idWhichFilter' => '>0',
        'dateStart' => "'0000-00-00'",
        'dateEnd' => "'  2222-12-12'"
    ];


    /**
     * @param $str
     * @return string
     */
    protected function quoteStr($str)
    {
        return "'{$str}'";
    }

    public function sanitize($value) {
        return $this->DB->ForSql(trim(strip_tags($value)));
    }

    public function prepareFilter(array $postData): array
    {
        $filter = [
            'paginate' => [
                'length' => (isset($postData['length']) && is_numeric($postData['length'])) ? (int)$postData['length'] : 10,
                'start'  => (isset($postData['start']) && is_numeric($postData['start'])) ? (int)$postData['start'] : 0,
            ],
            'search' => [],
            'order'  => []
        ];

        $columns = isset($postData['columns']) && is_array($postData['columns']) ? $postData['columns'] : [];
        $order = isset($postData['order']) && is_array($postData['order']) ? $postData['order'] : [];

        if (!empty($columns)) {
            foreach ($columns as $column) {
                if (isset($column['search']['value']) && $column['search']['value'] !== '') {
                    $filter['search'][$column['data']] = $this->sanitize($column['search']['value']);
                }
            }
        }

        if (!empty($order) && isset($columns[$order[0]['column']])) {
            $filter['order']['by'] = $this->sanitize($columns[$order[0]['column']]['data']);
            $filter['order']['dir'] = (strtolower($order[0]['dir']) === 'asc') ? 'asc' : 'desc';
        }

        if (!empty($postData['dateStart'])) {
            $startTimestamp = strtotime(trim(strip_tags($postData['dateStart'])));
            if ($startTimestamp) {
                $filter['search']['dateStart'] = $this->DB->ForSql(date('Y-m-d', $startTimestamp) . ' 00:00:00');
            }
        }

        if (!empty($postData['dateEnd'])) {
            $endTimestamp = strtotime(trim(strip_tags($postData['dateEnd'])));
            if ($endTimestamp) {
                $filter['search']['dateEnd'] = $this->DB->ForSql(date('Y-m-d', $endTimestamp) . ' 23:59:59');
            }
        } else if (!empty($postData['dateStart'])) {
            // Если дата окончания не указана, но дата старта есть
            $startTimestamp = strtotime(trim(strip_tags($postData['dateStart'])));
            if ($startTimestamp) {
                $filter['search']['dateEnd'] = $this->DB->ForSql(date('Y-m-d 23:59:59', $startTimestamp));
            }
        }

        $ignoredParams = ['draw', 'columns', 'order', 'start', 'length', 'search', 'dateStart', 'dateEnd'];

        foreach ($postData as $param => $data) {
            if ($data == '' || in_array($param, $ignoredParams, true)) {
                continue;
            }

            if (is_array($data)) {
                $filter['search'][$param] = array_map($this->sanitize, $data);
                continue;
            }

            if (!empty($data)) {
                $filter['search'][$param] = $this->sanitize($data);
            }
        }

        return $filter;
    }

    /**
     * @param string $rawValue Дата
     * @param array  $formats  Допустимые форматы даты
     * @return DateTime|false
     */
    protected function parseDateValue($rawValue, array $formats = [])
    {
        if (empty($rawValue)) {
            return false;
        }

        $defaultFormats = [
            'Y-m-d H:i:s',
            'Y-m-d',
            'Y-m-d\TH:i:s',
            'Y-m-d\TH:i',
            'd.m.Y',
            'm/d/Y',
        ];
        $formats = !empty($formats) ? $formats : $defaultFormats;

        foreach ($formats as $format) {
            $dateTime = DateTime::createFromFormat($format, $rawValue);

            if ($dateTime && $dateTime->format($format) === $rawValue) {
                return $dateTime;
            }
        }
        return false;
    }

    /**
     * Получает список имен полей таблицы БД
     * @param $tableName - название таблицы БД
     * @return array
     */
    protected function getColumnsByTable($tableName)
    {
        $dbName = $this->DB->DBName;

        $sql = $this->DB->Query(
            "SELECT `COLUMN_NAME` 
                FROM `INFORMATION_SCHEMA`.`COLUMNS` 
                WHERE `TABLE_SCHEMA`='{$dbName}' AND `TABLE_NAME`='{$tableName}'");

        $result = [];

        while ($row = $sql->Fetch()) {
            $result[] = $row['COLUMN_NAME'];
        }

        return $result;
    }

    protected function getColumnsMetadata(string $tableName): array
    {
        $dbName = $this->DB->DBName;

        $sql = $this->DB->Query(
            "SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE 
         FROM INFORMATION_SCHEMA.COLUMNS 
         WHERE TABLE_SCHEMA='{$dbName}' AND TABLE_NAME='{$tableName}'"
        );

        $result = [];

        while ($row = $sql->Fetch()) {
            $result[$row['COLUMN_NAME']] = [
                'type'     => $row['DATA_TYPE'],
                'nullable' => ($row['IS_NULLABLE'] === 'YES')
            ];
        }

        return $result;
    }

    /**
     * @param $table
     * @param $data
     * @return array
     */
    protected function prepearTableData($table, $data)
    {
        $columns = $this->getColumnsMetadata($table);

        $sqlData = [];

        foreach ($columns as $column => $columnInfo) {
            if ( $column == 'id' ) {
                continue;
            }
            if (isset($data[$column])) {
                $rawValue = trim(strip_tags($data[$column]));

                $type = $columnInfo['type'];
                $nullable = $columnInfo['nullable'];

                switch ($type) {
                    case 'int':
                    case 'tinyint':
                    case 'smallint':
                    case 'mediumint':
                    case 'bigint':
                        if (is_numeric($rawValue)) {
                            $value = intval($rawValue);
                        } else {
                            $value = $nullable ? "NULL" : 0;
                        }
                        $sqlData[$column] = $value;
                        break;

                    case 'decimal':
                    case 'float':
                    case 'double':
                        if (is_numeric($rawValue)) {
                            $value = floatval($rawValue);
                        } else {
                            $value = $nullable ? "NULL" : 0.0;
                        }
                        $sqlData[$column] = $value;
                        break;

                    case 'date':
                        if ($rawValue === '') {
                            $value = "NULL";
                        } else {
                            $dateObj = $this->parseDateValue($rawValue, ['Y-m-d H:i:s', 'Y-m-d', 'Y-m-d\TH:i:s', 'Y-m-d\TH:i']);
                            $value = $dateObj ? $this->quoteStr($this->DB->ForSql($dateObj->format('Y-m-d'))) : "NULL";
                        }
                        $sqlData[$column] = $value;
                        break;

                    case 'datetime':
                    case 'timestamp':
                        if ($rawValue === '') {
                            $value = "NULL";
                        } else {
                            $dateObj = $this->parseDateValue($rawValue, ['Y-m-d H:i:s', 'Y-m-d', 'Y-m-d\TH:i:s', 'Y-m-d\TH:i']);
                            if ($dateObj) {
                                if ($dateObj->format('H:i:s') === '00:00:00' && strpos($rawValue, ':') === false) {
                                    $formatted = $dateObj->format('Y-m-d') . ' 00:00:00';
                                } else {
                                    $formatted = $dateObj->format('Y-m-d H:i:s');
                                }
                                $value = $this->quoteStr($this->DB->ForSql($formatted));
                            } else {
                                $value = "NULL";
                            }
                        }
                        $sqlData[$column] = $value;
                        break;

                    default:
                        $sqlData[$column] = $this->quoteStr($this->DB->ForSql($rawValue));
                }
            }
        }

        return $sqlData;
    }

    public function __construct()
    {
        global $DB;
        $this->DB = $DB;
        $this->DBName = $this->DB->DBName;
    }


    /**
     * @param $path
     * @param $fileName
     * @return string
     */
    public function getBase64EncodeFile($path, $fileName)
    {
        $file = file_get_contents($path . $fileName);

        return base64_encode($file);
    }


    /**
     * @note получает имена файлов из директории
     * @param string $dir
     * @param array $skip
     * @return array
     */
    public function getFilesFromDir(string $dir, array $skip = [])
    {
        $result = [];

        $files = scandir($dir);
        $skipAll = array_merge($skip, ['.', '..']);
        foreach ($files as $file) {
            if (!in_array($file, $skipAll)) {
                $result[] = $file;
            }
        }

        return $result;
    }

    public function insertToSQL(array $data, string $nameTable = null, $userID = ""): int
    {
        if ($nameTable == null) {
            $nameTable = array_key_first($data);
            $dataAdd = $data[$nameTable];
        } else $dataAdd = $data;

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $nameTable)) {
            return false;
        }

        if ($userID) {
            $dataAdd['global_assigned'] = (int)$userID;
        } else {
            $dataAdd['global_assigned'] = (int)$_SESSION['SESS_AUTH']['USER_ID'];
        }

        $dataAdd['global_entry_date'] = date("Y-m-d H:i:s");
        $this->checkAndAddGlobal($nameTable, $dataAdd);

        $dataAdd = $this->prepearTableData($nameTable, $dataAdd);
        return $this->DB->Insert($nameTable, $dataAdd);
    }

    private function checkAndAddGlobal(string $nameTable): void
    {
        $globalName = ['global_assigned' => 'int(11)', 'global_entry_date' => 'datetime'];

        $columnsName = $this->getColumnsNameFromSQL($nameTable);

        foreach ($globalName as $key => $item) {
            if (!in_array($key, $columnsName)) {
                $this->DB->Query("ALTER TABLE $nameTable
                                    ADD $key $item NOT NULL;");
            }
        }
    }

    private function getColumnsNameFromSQL(string $nameTable): array
    {
        $dbName = $this->DB->DBName;
        $requestColumnsName = "
                SELECT COLUMN_NAME 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA= '{$dbName}' AND TABLE_NAME='{$nameTable}'
                ";
        $columnsName = $this->requestFromSQL($requestColumnsName);

        for ($i = 0; $i < count($columnsName); $i++) {
            $columnsName[$i] = $columnsName[$i]['COLUMN_NAME'];
        }
        return $columnsName;
    }

    private function checkAndAddGlobalV2(string $nameTable): void
    {
        $columnsName = $this->getColumnsNameFromSQL($nameTable);

        foreach ($this->globalNameV2 as $key => $item) {
            if (!in_array($key, $columnsName)) {
                $this->DB->Query("ALTER TABLE $nameTable
                                    ADD $key $item NOT NULL;");
            }
        }
    }

    private array $oldColumns = ['global_assigned' => 'int(11)', 'global_entry_date' => 'datetime', 'id' => 'int(11)'];
    private string $tableNameAdForHistory = '_history';
    private string $columnNameAdForHistory = '_old';

    public function saveFile($dir, $fileName, $tmpName)
    {

        if ( !is_dir($dir) ) {
            $mkdirResult = mkdir($dir, 0766, true);

            if ( !$mkdirResult ) {
                return [
                    'success' => false,
                    'error' => "Ошибка! Не удалось создать папку. {$dir}",
                ];
            }
        }

        $uploadfile = $dir."/".$fileName;

        if (!move_uploaded_file($tmpName, $uploadfile) ) {
            return [
                'success' => false,
                'error' => "Ошибка! Не удалось загрузить файл на сервер! tpmName: {$tmpName}, uploadfile: {$uploadfile}",
            ];
        } else {
            return [
                'success' => true,
                'data' => $fileName,
                'upload' => $uploadfile
            ];
        }
    }

    public function checkTypeFile($file, $types)
	{
		if (!in_array($file, $types)) {
			return false;
		}

		return true;
	}

	public function byteFileToServer($byteFile, $localPath)
    {
        $bin = base64_decode($byteFile, true);
        file_put_contents($_SERVER["DOCUMENT_ROOT"] . $localPath, $bin);
    }

	/**
	 * @param $text
	 * @param bool $exit
	 */
	public function pre($text, $exit = true) {
		if ($_SESSION['SESS_AUTH']['USER_ID'] == 1) {
			echo '<pre>';
			print_r($text);
			if ($exit) {
				exit();
			}
		}
	}

    protected function requestFromSQL(string $request): array
    {
        if ($request == "") {
            throw new InvalidArgumentException("Запрос к БД не может быть пустым");
        }
        $response = [];
        $requestFromSQL = $this->DB->Query($request);
        while ($row = $requestFromSQL->Fetch()) {
            $response[] = $row;
        }
        return $response;
    }

    protected function transformFilter(array $filter, string $typeTransform): array
    {
        $transformedFilter = [];
        if ($typeTransform == "having" || $typeTransform == "havingDateId") {
            if (!empty($filter['search'])) {
                $having = '1';
                foreach ($filter['search'] as $key => $item) {
                    $having .= " AND {$key} COLLATE utf8mb3_unicode_ci LIKE '%{$item}%'";
                }
                $transformedFilter['having'] = $having;
            }
            if ($typeTransform == "havingDateId") {

                $transformedFilter['dateStart'] = "'{$filter['dateStart']}-01' ";
                $transformedFilter['dateEnd'] = "LAST_DAY('{$filter['dateEnd']}-01') ";

                if ($filter['idWhichFilter'] == -1) {
                    $transformedFilter['idWhichFilter'] = '>0';
                } elseif ($filter['idWhichFilter'] > -1) {
                    $transformedFilter['idWhichFilter'] = '="' . $filter['idWhichFilter'] . '"';
                } else {
                    throw new InvalidArgumentException("Неизвестный аргумент idWhichFilter {$filter['idWhichFilter']} в функции transformFilter");
                }
            }
        } elseif ($typeTransform == "orderLimit") {
            if (!empty($filter['order'])) {
                $filter['order']['by'] = $this->changeNameForFormat($filter['order']['by']);
                $transformedFilter['order'] = "{$filter['order']['by']} {$filter['order']['dir']} ";
            }
            if ($filter['paginate']['length'] == -1) {
                $transformedFilter['limit'] = "";
            } else {
                $transformedFilter['limit'] = "LIMIT {$filter['paginate']['start']}, {$filter['paginate']['length']}";
            }
        } else {
            throw new InvalidArgumentException("Неизвестный аргумент $typeTransform в функции transformFilter");
        }
        return $transformedFilter;
    }

    protected function changeNameForFormat(string $data): string
    {
        $settings = ["dateformat"];
        $arrayData = explode("_", $data);

        if (end($arrayData) == $settings[0]) {
            $deleted = array_pop($arrayData);
            $data = implode("_", $arrayData);
        }

        return $data;
    }

    /**
     * Получает максимальное значение по указанным полям в таблице
     * 
     * @param string $table Имя таблицы
     * @param string|array $fields Имя поля или массив полей
     * @return string|int|float|double|decimal|null Максимальное значение (строка для дат, число для числовых полей) или null, если данных нет
     */
    public function getMaxValueByFields(string $table, $fields): mixed
    {
        $fields = is_array($fields) ? $fields : [$fields];

        $sql = $this->DB->Query("
            SELECT TABLE_NAME 
            FROM INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_SCHEMA = '{$this->DBName}'
              AND TABLE_NAME = '{$table}'
        ")->Fetch();

        if (!$sql) {
            throw new InvalidArgumentException("Таблица {$table} не существует в базе данных {$this->DBName}");
        }

        $maxValues = [];
        $fieldTypes = [];

        foreach ($fields as $field) {
            $sql = $this->DB->Query("
                SELECT COLUMN_NAME, DATA_TYPE 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = '{$this->DBName}'
                  AND TABLE_NAME = '{$table}' 
                  AND COLUMN_NAME = '{$field}'
            ")->Fetch();

            if (!$sql) {
                throw new InvalidArgumentException("Поле {$field} не существует в таблице {$table}");
            }

            $invalidTypes = ['tinyint', 'boolean', 'text', 'blob', 'json'];
            if (in_array(strtolower($sql['DATA_TYPE']), $invalidTypes)) {
                throw new InvalidArgumentException("Тип поля '{$sql['DATA_TYPE']}' не подходит для оператора MAX");
            }

            $maxValue = $this->DB->Query("
                SELECT MAX(`{$field}`) as max_value
                FROM `{$table}`
            ")->Fetch()['max_value'];

            if ($maxValue) {
                $maxValues[$field] = $maxValue;
                $fieldTypes[$field] = strtolower($sql['DATA_TYPE']);
            }
        }

        if (empty($maxValues)) {
            return null;
        }

        $maxField = array_keys($maxValues, max($maxValues))[0];
        $maxValue = $maxValues[$maxField];
        $maxFieldType = $fieldTypes[$maxField];

        switch ($maxFieldType) {
            case 'int':
                return (int)$maxValue;
            case 'float':
            case 'double':
            case 'decimal':
                return (float)$maxValue;
            default:
                return $maxValue;
        }
    }

    public function postToFilter(array $post): array
    {

        $filter = [
            'paginate' => [
                'length' => (isset($post['length']) && is_numeric($post['length'])) ? (int)$post['length'] : 10,
                'start'  => (isset($post['start']) && is_numeric($post['start'])) ? (int)$post['start'] : 0,
            ],
            'search' => [],
        ];

        foreach ($post['columns'] as $column) {
            if ($column['search']['value'] != '') {
                $filter['search'][$column['data']] = $this->sanitize($column['search']['value']);
            }
        }

        if (isset($post['order']) && !empty($post['columns'])) {
            $filter['order']['by'] = $this->sanitize($post['columns'][$post['order'][0]['column']]['data']);
            $filter['order']['dir'] = $this->sanitize($post['order'][0]['dir']);
        }

        $filter['idWhichFilter'] = $this->sanitize($post['idWhichFilter']);
        $filter['dateStart'] = $this->sanitize($post['dateStart']);
        $filter['dateEnd'] = $this->sanitize($post['dateEnd']);

        return $filter;
    }
}