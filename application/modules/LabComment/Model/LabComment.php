<?php


class LabComment extends Model
{
    public function add($data)
    {
        $sqlData = $this->prepearTableData('COMMENTS', $data);
        return $this->DB->Insert('COMMENTS', $sqlData);
    }

    public function updateData($data, $id)
    {
        $where = "WHERE ID = {$id}";
        $sqlData = $this->prepearTableData('COMMENTS', $data);
        return $this->DB->Update('COMMENTS', $sqlData, $where);
    }
}