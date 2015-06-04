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
                                //"&#2862;&#2839;&#2818;&#2867;", // Tuesday
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

        $content='<div class="row">'; // Whole page as row
        $content.=' <div class="col-md-9">'; // Page content
        $content.='     <br/>';
        $content.='  <div id="calendar" class="well">'.
                        '<div class="row">'.
                        $this->_createNavi().
                        '</div>'.

                        '<div class="box-content">'.
                            '<table class="table table-bordered"><thead><tr>'.$this->_createLabels().'</tr></thead></table>';   
        $content.='         <div style="clear:both"></div>';     
        $content.='         <table class="table table-bordered"><tbody>';    
                                 
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
             
        $content.='  </div>';
                 
        $content.='</div>';
        $content.='</div>'; // End of Page content

        $content.=$this->_showHolidays();

        $content.='</div>'; // End of Whole page as row

        $content .= $test;
    
        return $content;   
    }
     
    /********************* PRIVATE **********************/ 
    /**
    * populate the holiday list
    **/
    private function _populateHoliday($month, $year){
        $text == null;

        $db = Holiday::getInstance();
        $holidays = $db->fetchHoliday ($month, $year);
        $holiday = explode("|", $holidays);
        $text .='<div>'.$holidays.'</div>';
        $this->holidayInfo = array();

        foreach ($holiday as $value) {
            if (!empty($value)) {
                $holiday_split = explode(" ", $value);
                $this->holidayInfo[$holiday_split[0]] = $holiday_split[1];
                $text .='<div>'.$this->holidayInfo[$holiday_split[0]].'</div>';
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

        return
            '<div class="col-sm-2" style="display:block;cursor:pointer;text-decoration:none;color:#FFF;">'.
                '<a class="btn btn-info input-block-level" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'"><span class="glyphicon glyphicon-chevron-left"></span>  Prev  </a>'.
            '</div>'.
            '<div class="col-sm-8 text-center">'.
                    '<span class="label label-default">'.date('Y F',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
            '</div>'.
           '<div class="col-sm-2">'.
                '<a class="btn btn-info pull-right" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">  Next  <span class="glyphicon glyphicon-chevron-right"></span></a>'.
            '</div>';
    }

    /**
    * display side bar for holidays
    **/
    private function _showHolidays () {
        $retVal ='<div class="col-md-3">'; // Start of sidebar
        $retVal.='     <br/>';
        $retVal.='     <div class="panel panel-default">';
        $retVal.='         <div class="panel-heading">';
        $retVal.='             <h3 class="panel-title">Holidays List</h3>';
        $retVal.='         </div>';
        $retVal.='     </div>';

        $retVal.='  <ul class="list-group">';

        // Get all the holidays date and name
        foreach( $this->holidayInfo as $key => $val ) {
            $retVal.='<li class="list-group-item"><div class="span1">'.$key.'</div>'.'<div class="span2">'.$val.'</div></li>';
        }

        $retVal.='  </ul>';
        $retVal.='</div>'; // End of sidebar

        return $retVal;

    }
    /**
    * create the li element for ul
    */
    private function _showDay($cellNumber){
        $return == null;
        
        $isHoliday=0;

        /* Satureday or Sunday is a holiday by default */
        $isHoliday = ($cellNumber%7==0? 1:($cellNumber%7==6?1:0));
         
        if($this->currentDay==0){
             
            $firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
                     
            if(intval($cellNumber) == intval($firstDayOfTheWeek)){
                 
                $this->currentDay=1;
                 
            }
        }
         
        if( ($this->currentDay!=0)&&($this->currentDay<=$this->daysInMonth) ){
             
            $this->currentDate = date('Y-m-d',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.($this->currentDay)));
             
            $cellContent = $this->currentDay;

            if (array_key_exists ($cellContent, $this->holidayInfo)) {
                $isHoliday = 1;
            }
             
            $this->currentDay++;
             
        }else{
             
            $this->currentDate =null;
 
            $cellContent=null;
        }
        
        /**
        * Need to change the color of the cell if its a holiday
        */
        if ($isHoliday) {
            $return = '<td id="td-'.$this->currentDate.'" class="danger">'; 
            $return.= $this->oriyaDate->getTarikh ($cellContent);
            $return.= '</td>';   

        } else {
            $return = '<td id="td-'.$this->currentDate.'" class="info">';
            $return.= $this->oriyaDate->getTarikh ($cellContent);
            $return.= '</td>';            
        }

        return $return;
    }
     
    /**
    * create calendar week labels
    */
    private function _createLabels(){  
                 
        $content='';
         
        foreach($this->dayLabels as $index=>$label){
             
            $content.='<th class="text-center">'.$label.'</th>';
 
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
