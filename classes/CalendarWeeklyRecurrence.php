<?php


class CalendarWeeklyRecurrence extends CalendarRecurrence
{

    public $sunday;
    public $monday;
    public $tuesday;
    public $wednesday;
    public $thursday;
    public $friday;
    public $saturday;
    
    public $days;
    
    
    //Provide an array of days
    public function __construct($days, $rangeStart, $rangeEnd, $interval = false)
    {
        $this->recurType = self::RECUR_TYPE_WEEKLY;
        
        $allDays = array('sunday', 'monday','tuesday','wednesday','thursday','friday','saturday');
        
        if($days)
        {
            foreach($days as $dayNum)
            {
                $dayName = $allDays[$dayNum];
                $this->$dayName = 1;
            }
            
            $this->days = $days;
        }
        else
        {
            $this->days=array();            
        }
                
        parent::__construct($rangeStart, $rangeEnd, $interval);
    }
    
    public function occursOnDate($date)
    {
        
        $day = date('w',$date);

        $match = in_array($day, $this->days);
        
        $match = $match && ($this->rangeStart <= $date);
        
        if($this->interval && $this->interval > 1)
        {
            //How many weeks has it been since the start date?
            $week = self::DAY * 7;
            $startWeek = floor($this->rangeStart / $week);
            $thisWeek = floor($date / $week);
            
            $intervalWeek = ($thisWeek - $startWeek) % $this->interval;
            
            $match = $match && ($intervalWeek == 0);
        }
        
        
        if($this->rangeEnd)
        {
            $match = $match && ($this->rangeEnd >= $date);
        }
        
        return $match;
    
    }
    


}


?>