<?php

include 'holiday.php';
include 'oriyadate.php';

class Calendar {


    /**
     * Constructor
     */
    public function __construct(){
        $this->naviHref = htmlspecialchars($_SERVER['PHP_SELF']);
        $this->oriyaDate = new OriyaDate();
    }

    /********************* PROPERTY ********************/
    //private $dayLabels_english = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
    private $dayLabels = array( "&#2872;&#2891;&#2862;", //Monday
                                "&#2862;&#2839;&#2818;&#2867;", // Tuesday
                                "&#2860;&#2881;&#2855;", // Wednesday
                                "&#2839;&#2881;&#2864;&#2881;", // Thurseday
                                "&#2870;&#2881;&#2837;&#2893;&#2864;", // Friday
                                "&#2870;&#2856;&#2879;", // Satureday
                                "&#2864;&#2860;&#2879;" // Sunday
                                );

    private $currentYear=0;

    private $currentMonth=0;

    private $currentDay=0;

    private $currentDate=null;

    private $daysInMonth=0;

    private $naviHref= null;

    private $holidayInfo = array();

    private $oriyaDate=null;

    /********************* PUBLIC **********************/

    /**
    * print out the calendar
    */
    public function show() {
        $year  == null;

        $month == null;

        $monthTextualFormat == null;

        if(null==$year&&isset($_GET['year'])){

            $year = $_GET['year'];

        }else if(null==$year){

            $year = date("Y",time());

        }

        if(null==$month&&isset($_GET['month'])){

            $month = $_GET['month'];
            $monthTextualFormat = date('F',strtotime($year.'-'.$month.'-1'));

        }else if(null==$month){

            $month = date("m",time());
            $monthTextualFormat = strtolower (date("F",time()));
        }

        $this->currentYear=$year;
        $this->currentMonth=$month;
        $this->daysInMonth=$this->_daysInMonth($month,$year);

        // clear the array each time
        unset($this->holidayInfo);
        $test = $this->_populateHoliday ($monthTextualFormat, $this->currentYear);
        $content='<!-- Start -->'; // Todisplay database went fine

        $content.='<div class="row-fluid">'; // After jumbotron, this creates the calender as a row
        $content.=' <div class="col-md-1"></div>'; // Left side region
        $content.=' <div class="col-md-8">'; // Actual calender
        $content.='  <br/>';
        $content.='  <div id="calendar" class="well">'.
                        '<div id="navigation" class="row">'.
                        $this->_createNavi().
                        '</div>'.

                        //'<div class="box">'.
                            '<table class="table table-bordered" style="height:50%"><thead><tr>'.$this->_createLabels().'</tr></thead>';

        // Can be used later for creating a gap between day name and date table
        //$content.='         </table>';
        //$content.='         <div style="clear:both"></div>';
        //$content.='         <table class="table table-bordered"><tbody>';

        $content.='         <tbody>';
        $weeksInMonth = $this->_weeksInMonth($month,$year);

        // Create weeks in a month
        for( $i=0; $i<$weeksInMonth; $i++ ){
            $content.='         <tr>';
            //Create days in a week
            for($j=1;$j<=7;$j++){
                $content.=$this->_showDay($i*7+$j);
            }

            $content.='         </tr>';
        }

        $content.='         </tbody></table>';

        $content.='     <div style="clear:both"></div>';

        //$content.='  </div>'; // End of Box

        $content.='</div>'; // End of Well
        $content.='</div>'; // End of Actual calender

        $content.=$this->_showHolidays();

        $content.=' <div class="col-md-1"></div>'; // Right side region
        $content.='</div>'; // End of this creates the calender as a row

        $content .= $test;

        return $content;
    }

    /********************* PRIVATE **********************/
    /**
    * populate the holiday list
    **/
    private function _populateHoliday($month, $year){
        $text == "";

        $db = Holiday::getInstance();
        if (!$db) {
            $text .= "Connection failed: " . mysqli_connect_error();
        }

        $holidays = $db->fetchHoliday ($month, $year);
        $holiday = explode("|", $holidays);
        $db->closeConnection();

        //FIXME::Debugging purpose, Donot remove. Useful for future debugging
        //$text .='<div>'.$holidays.'</div>';

        $this->holidayInfo = array();

        foreach ($holiday as $value) {
            if (!empty($value)) {
                $holiday_split = explode(" ", $value);
                $this->holidayInfo[$holiday_split[0]] = $holiday_split[1];

                // FIXME::Debugging purpose, Donot remove. Useful for future debugging
                //$text .='<div>'.$this->holidayInfo[$holiday_split[0]].'</div>';
            }
        }

        return $text;
    }

    /**
    * create navigation
    */
    private function _createNavi(){

        $nextMonth = $this->currentMonth==12?1:intval($this->currentMonth)+1;
        $nextYear = $this->currentMonth==12?intval($this->currentYear)+1:$this->currentYear;
        $preMonth = $this->currentMonth==1?12:intval($this->currentMonth)-1;
        $preYear = $this->currentMonth==1?intval($this->currentYear)-1:$this->currentYear;

        // Previous month and year
        $preMonthOriya = $this->oriyaDate->getMonth(date('n',strtotime($preYear.'-'.$preMonth.'-1')));

        // This month and year
        $thisYearOriya = $this->oriyaDate->getTarikh(date('Y',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')));
        $thisMonthOriya = $this->oriyaDate->getMonth(date('n',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')));

        // Next month and year
        $nextMonthOriya = $this->oriyaDate->getMonth(date('n',strtotime($nextYear.'-'.$nextMonth.'-1')));

        // Previous month button
        $retVal ='<div class="col-sm-2" style="display:block;cursor:pointer;text-decoration:none;color:#FFF;">'.
                //'<a class="btn btn-info input-block-level pull-left" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'"><span class="glyphicon glyphicon-chevron-left"></span>  &#2858;&#2882&#2864;&#2893;&#2860;  </a>'.
                    '<a class="btn btn-info btn-block" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'"><span class="glyphicon glyphicon-chevron-left"></span>  '.$preMonthOriya.'  </a>'.
                '</div>';

        // Middle label
        $retVal .='<div class="col-sm-8 text-center">'.
                      '<a href="#" class="btn btn-success btn-block active" background-color="#cccccc">'.$thisYearOriya.' '.$thisMonthOriya.'</a>'.
                  '</div>';

        // Next Month button
        $retVal .='<div class="col-sm-2" style="display:block;cursor:pointer;text-decoration:none;color:#FFF;">'.
                //'<a class="btn btn-info input-block-level pull-right" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">  &#2858&#2864;  <span class="glyphicon glyphicon-chevron-right"></span></a>'.
                      '<a class="btn btn-info btn-block pull-right" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">  '.$nextMonthOriya.'  <span class="glyphicon glyphicon-chevron-right"></span></a>'.
                  '</div>';

        return $retVal;
    }

    /**
    * display side bar for holidays
    **/
    private function _showHolidays () {
        $retVal ='<div class="col-md-2">'; // Start of sidebar
        $retVal.='     <br/>';
        $retVal.='     <div class="panel panel-default">';
        $retVal.='         <div class="panel-heading text-center" style="background:#FFA6A6">';
        $retVal.='             <h3 id="holidaylist" class="panel-title">&#2858;&#2864;&#2893;&#2860;&#2858&#2864;&#2893;&#2860;&#2878&#2851;&#2880;</h3>';
        $retVal.='         </div>';
        $retVal.='     </div>'; // End of panel default

        $retVal.='      <div class="panel-body">';

        // Replace with image
        //$retVal.='      <div class="holidayImage ImageEmbed ImageEmbed--3by1"></div>';

        /* Get all the holidays date and name and populate the list */

        $retVal.='<ul class="list-group">';
        $retVal.='<li class="list-group-item row">';
        $retVal.='</li>';

        foreach( $this->holidayInfo as $key => $val ) {
            $retVal.='<li class="list-group-item row">';
            $retVal.='  <div class="col-md-3">'.$this->oriyaDate->getTarikh($key).'</div>';
            $retVal.='  <div class="col-md-9">'.$val.'</div>';

            // Need to add semicolon to protect the holiday list data
            $retVal.='</li>';
        }

        $retVal.='  </ul>';


        $retVal.='</div>'; // End of panelbody
        $retVal.='</div>'; // End of sidebar

        return $retVal;
    }

    /**
    * create the li element for ul
    */
    private function _showDay($cellNumber){
        // To create a style for todays date
        $todayStyleStart = " ";
        $todayStyleEnd = " ";

        $return == null;
        $isHoliday=0;
        $isToday=0;

        /* Satureday or Sunday is a holiday by default */
        $isHoliday = ($cellNumber%7==0? 1:0);
        $isSatureday = ($cellNumber%7==6?1:0);

        if($this->currentDay==0){

            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));

            if(intval($cellNumber) == intval($firstDayOfTheWeek)){

                $this->currentDay=1;

            }
        }

        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){

            $this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));

            $cellContent = $this->currentDay;

            /* Check if it is a holiday */
            if (array_key_exists ($cellContent, $this->holidayInfo)) {
                $isHoliday = 1;
            }

            /* Check if it is today */
            $today = date("Y-m-d",time());
            if ($today == $this->currentDate) {
                $todayStyleStart = '<div class="circle">';
                $todayStyleEnd= '</div>';
            }

            $this->currentDay++;

        }else{

            $this->currentDate =null;
            $cellContent=null;
        }

        /**
        * Need to change the color of the cell if its a holiday/Satureday
        */
        if ($isHoliday) {

            $return = '<td id="td-'.$this->currentDate.'" bgcolor="#FFA6A6">'.$todayStyleStart;
            $return.= $this->oriyaDate->getTarikh ($cellContent);
            $return.= $todayStyleEnd.'</td>';

        } else if ($isSatureday) {

            $return = '<td id="td-'.$this->currentDate.'" class="danger ">'.$todayStyleStart;
            $return.= $this->oriyaDate->getTarikh ($cellContent);
            $return.= $todayStyleEnd.'</td>';

        } else {
            $return = '<td id="td-'.$this->currentDate.'" >'.$todayStyleStart;
            $return.= $this->oriyaDate->getTarikh ($cellContent);
            $return.= $todayStyleEnd.'</td>';
        }

        return $return;
    }

    /**
    * create calendar week labels
    */
    private function _createLabels(){

        $content='';

        foreach($this->dayLabels as $index=>$label){

            $content.='<th class="text-center bg-primary">'.$label.'</th>';

        }

        return $content;
    }



    /**
    * calculate number of weeks in a particular month
    */
    private function _weeksInMonth($month=null,$year=null){

        if( null==($year) ) {
            $year =  date("Y",time());
        }

        if(null==($month)) {
            $month = date("m",time());
        }

        // find number of days in this month
        $daysInMonths = $this->_daysInMonth($month,$year);

        $numOfweeks = ($daysInMonths%7==0?0:1) + intval($daysInMonths/7);

        $monthEndingDay= date('N',strtotime($year.'-'.$month.'-'.$daysInMonths));

        $monthStartDay = date('N',strtotime($year.'-'.$month.'-01'));

        if($monthEndingDay<$monthStartDay){
            $numOfweeks++;
        }

        return $numOfweeks;
    }

    /**
    * calculate number of days in a particular month
    */
    private function _daysInMonth($month=null,$year=null){

        if(null==($year))
            $year =  date("Y",time());

        if(null==($month))
            $month = date("m",time());

        return date('t',strtotime($year.'-'.$month.'-01'));
    }

}
