<?php

include 'holiday.php';

class Calendar {  
     

    /**
     * Constructor
     */
    public function __construct(){     
        $this->naviHref = htmlspecialchars($_SERVER['PHP_SELF']);
    }
     
    /********************* PROPERTY ********************/  
    private $dayLabels = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun");
     
    private $currentYear=0;
     
    private $currentMonth=0;
     
    private $currentDay=0;
     
    private $currentDate=null;
     
    private $daysInMonth=0;
     
    private $naviHref= null;

    private $holidayInfo = array();
     
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

        $content='<div id="calendar">'.
                        '<div class="box">'.
                        $this->_createNavi().
                        '</div>'.
                        '<div class="box-content">'.
                                '<ul class="label">'.$this->_createLabels().'</ul>';   
                                $content.='<div class="clear"></div>';     
                                $content.='<ul class="dates">';    
                                 
                                $weeksInMonth = $this->_weeksInMonth($month,$year);
                                // Create weeks in a month
                                for( $i=0; $i<$weeksInMonth; $i++ ){
                                     
                                    //Create days in a week
                                    for($j=1;$j<=7;$j++){
                                        $content.=$this->_showDay($i*7+$j);
                                    }
                                }
                                 
                                $content.='</ul>';
                                 
                                $content.='<div class="clear"></div>';     
             
                        $content.='</div>';
                 
        $content.='</div>';

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
    * create the li element for ul
    */
    private function _showDay($cellNumber){
        $return == null;
        
        $isHoliday=0;
         
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
            $return = '<li id="li-'.$this->currentDate.'" class=" holiday "'.
                  ($cellContent==null?'mask':'').'">'; 

            //$return.= '<table border=1><tr><td>'.$cellContent.'</td></tr><tr><td></td></tr></table>';
            $return.= $cellContent;
            $return.= '</li>';   

        } else {
            $return = '<li id="li-'.$this->currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':($cellNumber%7==6?' end ':' '))).
                  ($cellContent==null?'mask':'').'">';

            //$return.= '<table border=1><tr><td>'.$cellContent.'</td></tr><tr><td></td></tr></table>';
            $return.= $cellContent;

            $return.= '</li>';            
        }

        return $return;
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
            '<div class="header">'.
                '<a class="prev" href="'.$this->naviHref.'?month='.sprintf('%02d',$preMonth).'&year='.$preYear.'">Prev</a>'.
                    '<span class="title">'.date('Y F',strtotime($this->currentYear.'-'.$this->currentMonth.'-1')).'</span>'.
                '<a class="next" href="'.$this->naviHref.'?month='.sprintf("%02d", $nextMonth).'&year='.$nextYear.'">Next</a>'.
            '</div>';
    }
         
    /**
    * create calendar week labels
    */
    private function _createLabels(){  
                 
        $content='';
         
        foreach($this->dayLabels as $index=>$label){
             
            $content.='<li class="'.($label==6?'end title':'start title').' title">'.$label.'</li>';
 
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
