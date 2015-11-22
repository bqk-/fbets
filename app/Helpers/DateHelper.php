<?php namespace App\Helpers;

class DateHelper
{
    public static function sqlDateToStringHuman($date){
        $d = new \DateTime($date);
        $now = new \DateTime('NOW');
        $tomorrow = new \DateTime('NOW +1 day');
        $tomorrow->setTime(23,59,59);
        $yesterday = new \DateTime('NOW -1 day');
        $yesterday->setTime(00,00,1);
        $week = new \DateTime('NOW +6 days');
        $week->setTime(23,59,59);

        if($d->format('m.d.y') == $now->format('m.d.y'))
        {
            return $d->format(trans('general.todayd'));
        }
        else if($d > $now && $d <= $tomorrow)
        {
            return $d->format(trans('general.tomorrowd'));
        }
        else if($d > $now && $d <= $week)
        {
            return $d->format(trans('general.weekd'));
        }
        else if($d < $now && $d >= $yesterday)
        {
            return $d->format(trans('general.yesterdayd'));
        }

        return $d->format(trans('general.date_format'));
    }

    public static function sqlDateToStringOnlyDate($date)
    {
        $d = new \DateTime($date);
        $now = new \DateTime('NOW');
        $tomorrow = new \DateTime('NOW +1 day');
        $tomorrow->setTime(23,59,59);
        if($d->format('m.d.y') == $now->format('m.d.y'))
        {
            return trans('general.date_today');
        }
        else if($d > $now && $d <= $tomorrow)
        {
            return trans('general.date_tomorrow');
        }

        return $d->format(trans('general.date_date'));
    }

    public static function sqlDateToHourOnly($date){
        $d = new \DateTime($date);
        return $d->format(trans('general.date_heure'));
    }

    public static function sqlDateToString($date)
    {
        $d = new \DateTime($date);
        return $d->format(trans('general.date_format'));
    }

    public static function getTimestampFromSqlDate($date)
    {
        $date = new \DateTime($date, new \DateTimeZone('Europe/Paris'));
        return $date->getTimestamp();
    }
}

