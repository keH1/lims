<?php

class Gantt extends Model
{
    /**
     *  здесь собирается коллекция для вьюшки, где главным элементом является проект
     *  и от проекта уже идет список участников
     * @param array $filter
     * @return array
     */
    public function getProjectsInfo(array $filter): array
    {
        $where = "";
        if (!empty(trim($filter['project_filter']))) {
            $where = "WHERE project_name like '%{$filter['project_filter']}%'";
        }
        $sql = "SELECT * FROM gantt_projects $where;";
        $data = $this->DB->Query($sql);

        $result = [];
        while ($row = $data->Fetch()) {
            $projectId = $row['id'];

            $members = $this->getMembers($projectId, $filter);


            $result[] = [
                'id' => $projectId,
                'project_name' => $row['project_name'],
                'color1' => $row['color1'],
                'color2' => $row['color2'],
                'members' => $members,
            ];
        }

        return $result;
    }

    public function getMembers(int $projectId, array $filter): array
    {
        $where = "";
        if (!empty(trim($filter['user_filter']))) {
            $where = " and gu.name like '%{$filter['user_filter']}%'";
        }
        $membersSql = "SELECT distinct gu.*, gup.project_id
                        FROM gantt_user_projects gup
                        JOIN gantt_users gu ON gup.user_id = gu.id
                        WHERE gup.project_id = $projectId $where;";

        $membersData = $this->DB->Query($membersSql);
        $members = [];
        while ($membersRow = $membersData->Fetch()) {
            $members[] = new GanttUser($membersRow);
        }

        return $members;
    }

    public function getAllMembers(): array
    {
        $membersSql = "SELECT distinct gu.*, gup.project_id
                        FROM gantt_user_projects gup
                        JOIN gantt_users gu ON gup.user_id = gu.id;";

        $membersData = $this->DB->Query($membersSql);
        $members = [];
        while ($membersRow = $membersData->Fetch()) {
            $members[] = new GanttUser($membersRow);
        }

        return $members;
    }

    public function prepareDataForSqlQuery(array $data): array
    {
        foreach ($data as $key => $item) {
            if (is_string($item)) {
                $data[$key] = $this->quoteStr($this->DB->ForSql(trim($item)));
            }
        }

        return $data;
    }

    public function createUser(array $data): int
    {
        $data = $this->prepareDataForSqlQuery($data);
        $result = $this->DB->Insert("gantt_users", $data);

        return intval($result);
    }

    public function createProject(array $data): int
    {
        $data = $this->prepareDataForSqlQuery($data);
        $result = $this->DB->Insert("gantt_projects", $data);

        return intval($result);
    }

    public function getUsers(): array
    {
        $query = "SELECT * FROM gantt_users ORDER BY name ASC;";
        $data = $this->DB->Query($query);

        $result = [];
        while ($row = $data->Fetch()) {
            $result[] = $row;
        }

        return $result;
    }

    public function getUser(int $userId, int $projectId = null)
    {
        $query = "SELECT * FROM gantt_users WHERE id = $userId;";
        $user = $this->DB->Query($query)->Fetch();

        if (!$user) {
            return [];
        }

        if (!is_null($projectId)) {
            $user['timelines'] = [];
            $query = "SELECT * FROM gantt_user_projects WHERE user_id = $userId AND project_id = $projectId ORDER BY ID;";
            $data = $this->DB->Query($query);
            while ($row = $data->Fetch()) {
                $user['timelines'][] = $row;
            }
        }

        return $user;
    }

    public function getProject(int $projectId, int $userId = null)
    {
        $query = "SELECT * FROM gantt_projects WHERE id = $projectId;";
        $project = $this->DB->Query($query)->Fetch();

        if (!$project) {
            return [];
        }

        if (!is_null($userId)) {
            $project['timelines'] = [];
            $query = "SELECT * FROM gantt_user_projects WHERE user_id = $userId AND project_id = $projectId ORDER BY ID;";
            $data = $this->DB->Query($query);
            while ($row = $data->Fetch()) {
                $project['timelines'][] = $row;
            }
        }

        return $project;
    }

    public function editUser(array $data, $dates, int $userId): int
    {

        $result = $this->DB->Update("gantt_users", $this->prepareDataForSqlQuery($data), "WHERE id = $userId");

        if (empty($dates)) {
            return intval($result);
        }

        foreach ($dates as $date) {
            $attrs = [
                'start_date' => $date['start_date'],
                'end_date' => empty($date['end_date']) ? null : $date['end_date'],
            ];

            if (new DateTime($attrs['start_date']) > new DateTime($attrs['end_date']) && !is_null($attrs['end_date'])) {
                continue;
            }

            $this->DB->Update("gantt_user_projects", $this->prepareDataForSqlQuery($attrs), "WHERE id = {$date['row_id']}");
        }

        return intval($result);
    }

    public function editProject(array $data, int $projectId, $dates): int
    {
        $result = $this->DB->Update("gantt_projects", $this->prepareDataForSqlQuery($data), "WHERE id = $projectId");

        if (empty($dates)) {
            return intval($result);
        }

        foreach ($dates as $date) {
            $attrs = [
                'start_date' => $date['start_date'],
                'end_date' => empty($date['end_date']) ? null : $date['end_date'],
            ];

            if (new DateTime($attrs['start_date']) > new DateTime($attrs['end_date']) && !is_null($attrs['end_date'])) {
                continue;
            }

            $this->DB->Update("gantt_user_projects", $this->prepareDataForSqlQuery($attrs), "WHERE id = {$date['row_id']}");
        }

        return intval($result);
    }

    private function closePrevProjects(int $userId, string $dateTime)
    {
        $sql = "SELECT * FROM gantt_user_projects WHERE user_id = $userId AND end_date IS NULL;";
        $data = $this->DB->Query($sql);
        while ($row = $data->Fetch()) {
            $row['end_date'] = $dateTime;
            $rowId = $row['id'];
            unset($row['id']);

            $this->DB->Update("gantt_user_projects", $this->prepareDataForSqlQuery($row), "WHERE id={$rowId}");
        }
    }

    public function connectUserToProject(int $userId, int $projectId): int
    {
        $date = new DateTime();
        $timezone = new DateTimeZone('Asia/Yekaterinburg');
        $date->setTimezone($timezone);
        $date = $date->format('Y-m-d');

        $this->closePrevProjects($userId, $date);

        $data = [
            'user_id' => $userId,
            'project_id' => $projectId,
            'start_date' => $date,
        ];
        $result = $this->DB->Insert("gantt_user_projects", $this->prepareDataForSqlQuery($data));

        return intval($result);
    }

    /**
     * Здесь собираю инфу для вьюшки index2
     * @param array $filter
     * @return array
     */
    public function getTableInfo(array $filter): array
    {
        $where = "";
        if (!empty(trim($filter['user_filter']))) {
            $where = "WHERE name like '%{$filter['user_filter']}%'";
        }
        $sql = "SELECT * FROM gantt_users $where;";
        $data = $this->DB->Query($sql);

        $result = [];
        while ($user = $data->Fetch()) {
            $result[] = new GanttMember($user, $filter);
        }

        return $result;
    }
}