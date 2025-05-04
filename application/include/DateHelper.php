<?php

class DateHelper {

    public static function getBetweenDates($startDate, $endDate, $period = 86400, $isWeekend = true): array
    {
        $rangArray = [];

        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        for (
            $currentDate = $startDate;
            $currentDate <= $endDate;
            $currentDate += ($period)
        ) {

            $date = date('Y-m-d', $currentDate);

            $dayIndex = date("w", $currentDate);

            if ($isWeekend || ($dayIndex != 6 && $dayIndex != 0)) {
                $rangArray[] = $date;
            }
        }

        return $rangArray;
    }

    public static function setPointFormat($date)
    {
        $dateArr = explode("-", $date);
        return $dateArr[2] . "." . $dateArr[1] . "." . $dateArr[0];
    }

    public static function addWorkingDays($date, $dayQuantity)
    {
        $year = intval(date("Y", strtotime($date)));

        for ($i = 0; $i < $dayQuantity; $i++) {
            if ($date == $year . "-12-31") {
                $year++;
            }

            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        }

        return $date;
    }
}