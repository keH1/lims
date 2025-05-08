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


    /**
     * добавляет рабочих дней к дате
     * @param $date - дата отсчета
     * @param $dayQuantity - количество дней
     * @param bool $isWeekend - учитывать выходные дни?
     * @return string
     */
    public static function addWorkingDays(string $date, int $dayQuantity, bool $isWeekend = true): string
    {
        $currentDate = new DateTime($date);
        $daysAdded = 0;

        while ($daysAdded < $dayQuantity) {
            $currentDate->modify('+1 day');
            $dayOfWeek = (int)$currentDate->format('w');

            if ($isWeekend) {
                if ($dayOfWeek === 0) { // Воскресенье
                    $currentDate->modify('+1 day');
                } elseif ($dayOfWeek === 6) { // Суббота
                    $currentDate->modify('+2 days');
                }
            }

            // Проверяем, что после корректировки мы не попали на выходной
            $newDayOfWeek = (int)$currentDate->format('w');
            if (!$isWeekend || ($newDayOfWeek !== 0 && $newDayOfWeek !== 6)) {
                $daysAdded++;
            }
        }

        return $currentDate->format('Y-m-d');
    }
}