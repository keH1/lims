<?php

$OUTRANGETIMELINES = [];
$FIRSTDATEINTABLE = null;

if (!function_exists("getArrayDate")) {
    function getArrayDate($startDate): array
    {
        if (empty($startDate)) return [];
        $date = explode(' ', $startDate)[0];
        $splittedDate = explode('-', $date);

        return [
            'year' => $splittedDate[0],
            'month' => $splittedDate[1],
            'day' => $splittedDate[2],
        ];
    }
}

if (!function_exists("isDateInRage")) {
    /**
     * @throws Exception
     */
    function isDateInRage($timeLineStartDateArray, $firstDateInTable, $userId, $projectId, $timeLineCount): bool
    {
        global $OUTRANGETIMELINES, $FIRSTDATEINTABLE;

        $timeLineStartDate = $timeLineStartDateArray['year'] . "-" . $timeLineStartDateArray['month'] . "-" . $timeLineStartDateArray['day'];
        $timeLineStartDate = new DateTime($timeLineStartDate);
        $firstDateInTable = new DateTime($firstDateInTable);
        if (is_null($FIRSTDATEINTABLE)) {
            $FIRSTDATEINTABLE = $firstDateInTable;
        }

        if (in_array([
            'user_id' => $userId,
            'project_id' => $projectId,
            'timeLineStartDateArray' => $timeLineStartDateArray,
//            'firstDateInTable' => $firstDateInTable,
            'timeLineCount' => $timeLineCount,], $OUTRANGETIMELINES)) {
            return false;
        }

        if ($timeLineStartDate >= $FIRSTDATEINTABLE) {
            return false;
        }

        $OUTRANGETIMELINES[] = [
            'user_id' => $userId,
            'project_id' => $projectId,
            'timeLineStartDateArray' => $timeLineStartDateArray,
            'timeLineCount' => $timeLineCount,
        ];

        return true;

    }
}

if (!function_exists("renderTimeLine")) {
    function renderTimeLine($timeLine, $month, $day, $projectId, $timeLineCount, $userId, $color1, $color2): string
    {
        $timeLineHtml = "";
        if (empty($timeLine) || is_null($timeLineCount || $timeLine['project_id'] == $projectId)) {
            return $timeLineHtml;
        }

        $timeLineStartDate = getArrayDate($timeLine['start_date']);
        $isDateInRage = isDateInRage($timeLineStartDate, $month['full_date'], $userId, $projectId, $timeLineCount);
        if (intval($month['month_number']) == intval($timeLineStartDate['month']) &&
            intval($timeLineStartDate['year']) == $month['year'] &&
            intval($timeLineStartDate['day']) == $day || $isDateInRage
        ) {
            $dataAttrs = 'data-timeline_id="' . $timeLine['id'] . '" data-project_id="' . $projectId . '" data-user_id="' . $userId . '" data-timeline_count="' . $timeLineCount . '"';
            $timeLineHtml = '<div ' . $dataAttrs . ' class="timeline table_timeline rounded" style=" background: linear-gradient(to right, ' . $color1 . ', ' . $color2 . ');"> 
                                            <div id="timeline-left-side_' . $userId . '" data-timeline_id="timeline_' . $userId . '" class="left-grab">
                                                    <i style="display: none;" class="fa-solid fa-grip-vertical"></i>
                                            </div>
                                            <div id="timeline-right-side_' . $userId . '" data-timeline_id="timeline_' . $userId . '" class="right-grab">
                                                <i style="display: none;" class="fa-solid fa-grip-vertical"></i>
                                            </div>
                                        </div>';
        }

        return $timeLineHtml;
    }
}

if (!function_exists("renderCells")) {
    function renderCells($calendar, $currentDate, array $timeLine = [],
                         int $userId = null, int $projectId = null,
                         string $color1 = "grey", string $color2 = "grey",
                         int $timeLineCount = null
    ): string
    {
        $result = "";
        foreach ($calendar as $month) {
            foreach ($month['days'] as $day) {
                $result .= '<td class="table_cell" data-day="' . $day . '" data-month="' . $month['month_number'] . '" data-year="' . $month['year'] . '" data-project_id="' . $projectId . '" data-user_id="' . $userId . '" id="' . $month['local_name'] . "_" . $day . '"';

                if ($currentDate['month']['name'] . "_" . $currentDate['day'] == $month['local_name'] . "_" . $day) {
                    $result .= ' style="background: #ffa50087;"';
                }

                $result .= ">";

                $result .= renderTimeLine($timeLine, $month, $day, $projectId, $timeLineCount, $userId, $color1, $color2);


                $result .= "</td>";
            }
        }

        $result .= "</tr>";

        return $result;
    }
}

?>