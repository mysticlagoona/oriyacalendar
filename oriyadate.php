<?php


// Two options for connecting to the database:
class OriyaDate {
    private $oriyaletter = array ("&#2918;", // 0
                                "&#2919;", // 1
                                "&#2920;", // 2
                                "&#2921;", // 3
                                "&#2922;", // 4
                                "&#2923;", // 5
                                "&#2924;", // 6
                                "&#2925;", // 7
                                "&#2926;", // 8
                                "&#2927;", // 9
                                );

    private $oriyamonth = array (" ", // Nothing as 0 month
                                 "&#2844&#2878;&#2856&#2881;&#2911&#2878;&#2864&#2880;", //january
                                 "&#2859&#2887;&#2860&#2883;&#2911&#2878;&#2864&#2880;", //february
                                 "&#2862&#2878;&#2864;&#2893;&#2842;&#2893;&#2842;", //march
                                 "&#2831&#2858;&#2893;&#2864;&#2879;&#2866;", //april
                                 "&#2862&#2823;", // may
                                 "&#2844;&#2881&#2856;", // june
                                 "&#2844;&#2881;&#2866&#2878&#2823;", //july
                                 "&#2821&#2839&#2871;&#2893;&#2847;", // august
                                 "&#2872&#2887;&#2858;&#2893;&#2847&#2887;&#2862;&#2893;&#2860;&#2864;", // september
                                 "&#2821&#2837;&#2893;&#2847;&#2891&#2860&#2864;", //october
                                 "&#2856&#2861;&#2887&#2862;&#2893;&#2860;&#2864;", // november
                                 "&#2849;&#2879&#2872&#2887;&#2862;&#2893;&#2860;&#2864;", // december
                                );




    /**
     * Constructor
     */
    public function __construct(){
    }

    /*
    Get an instance of the Database
    @return Instance
    */

    public function getTarikh($date) {
        $ret = "";
        if (empty($date)) {
            return $ret;
        }

        $items = strval($date);
        $dateString = str_split($items);

        foreach ($dateString as $value) {
            if ($value == 0 or !empty($value)) {
                 $ret .= $this->oriyaletter[intval($value)];
            }
        }

        return $ret;
    }

    public function getMonth($month) {
        $ret = "";
        if (empty($month)) {
            return $ret;
        }

        $ret .= $this->oriyamonth[intval($month)];

        return $ret;
    }
}

?>
