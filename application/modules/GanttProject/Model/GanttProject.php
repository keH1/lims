<?php

class GanttProject extends Model implements JsonSerializable
{

    private int $id;
    private string $projectName;
    private string $color1;
    private string $color2;

    private array $timeLines;
    private int $memberId;

    public function __construct(array $data, $userId)
    {
        parent::__construct();

        $this->id = $data['id'];
        $this->projectName = $data['project_name'];
        $this->color1 = $data['color1'];
        $this->color2 = $data['color2'];
        $this->memberId = $userId;

        $this->timeLines = $this->calculateTimelines();
    }

    public function calculateTimelines(): array
    {
        $sql = "SELECT * FROM gantt_user_projects WHERE user_id = $this->memberId and project_id = $this->id;";

        $data = $this->DB->Query($sql);

        $result = [];
        while ($timeLine = $data->Fetch()) {
            $result[] = $timeLine;
        }

        return $result;
    }

    public function getTimeLines(): array
    {
        return $this->timeLines;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->projectName;
    }

    public function getColor1()
    {
        return $this->color1;
    }

    public function getColor2()
    {
        return $this->color2;
    }

    public function jsonSerialize()
    {
        // TODO: Implement jsonSerialize() method.
    }
}