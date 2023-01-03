<?php


namespace App\Components;
use \DateTime;

class RODate  {

    public static $luni =
        [
            '',
            'IANUARIE',
            'FEBRUARIE',
            'MARTIE',
            'APRILIE',
            'MAI',
            'IUNIE',
            'IULIE',
            'AUGUST',
            'SEPTEMBRIE',
            'OCTOMBRIE',
            'NOIEMBRIE',
            'DECEMBRIE',
        ];
    public static $lunis =
        [
            '',
            'IAN',
            'FEB',
            'MAR',
            'APR',
            'MAI',
            'IUN',
            'IUL',
            'AUG',
            'SEP',
            'OCT',
            'NOE',
            'DEC',
        ];

    public static function getLuna($luna)
    {
        return $luna <= 12? self::$luni[$luna]: self::$luni[intval(substr($luna, 4, 2))] . ' ' . substr($luna, 0, 4);
    }

    public static function getLunas($luna)
    {
        return $luna <= 12? self::$lunis[$luna]: self::$lunis[intval(substr($luna, 4, 2))] . ' ' . substr($luna, 0, 4);
    }

    public static function MonthShifter(DateTime $aDate, $months)
    {
        $dateA = clone($aDate);
        $dateB = clone($aDate);
        $plusMonths = clone($dateA->modify($months . ' Month'));
        //check whether reversing the month addition gives us the original day back
        if($dateB != $dateA->modify($months*-1 . ' Month')){
            $result = $plusMonths->modify('last day of last month');
        } elseif($aDate == $dateB->modify('last day of this month')){
            $result =  $plusMonths->modify('last day of this month');
        } else {
            $result = $plusMonths;
        }
        return $result;
    }


    public static function  getTextLuni($lunaIni, $lunaFin)
    {
        if (isset($lunaIni) && isset($lunaFin) && $lunaIni != '' && $lunaFin != '' ) {
            if ($lunaIni == $lunaFin)
                return 'luna ' . self::getLuna($lunaIni);

            $anIni = substr($lunaIni, 0, 4);
            $anFin = substr($lunaFin, 0, 4);
            if ($anIni == $anFin)
                return 'lunile ' . self::$luni[intval(substr($lunaIni, 4, 2))] . ' - ' . self::getLuna($lunaFin);
            else
                return 'lunile ' . self::getLuna($lunaIni) . ' - ' . self::getLuna($lunaFin);
        }
        else
            return '';

    }


    public static function  getIntervalLuni($lunaIni, $lunaFin)
    {
        if (isset($lunaIni) && isset($lunaFin) && $lunaIni != '' && $lunaFin != '' ) {
            $anIni = substr($lunaIni, 0, 4);
            $anFin = substr($lunaFin, 0, 4);

            $date1 = DateTime::createFromFormat('Ymd', $lunaIni . '01');
            $date2 = DateTime::createFromFormat('Ymd', $lunaFin . '01');
            $diff = $date1->diff($date2);
            $nrLuni = ' (' . ($diff->y * 12 + $diff->m + 1) . ' luni)';


            if ($lunaIni == $lunaFin)
                return strtolower(self::getLunas($lunaIni));

            if ($anIni == $anFin)
                return strtolower(self::$lunis[intval(substr($lunaIni, 4, 2))] . ' - ' . self::getLunas($lunaFin)) . $nrLuni;
            else
                return strtolower(self::getLunas($lunaIni) . ' - ' . self::getLunas($lunaFin)) . $nrLuni;
        }
        else
            return '';

    }


}
