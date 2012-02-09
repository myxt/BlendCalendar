<?php

/**
 * Represents a once-per-year event.
 */
class CalendarAnnualStaticRecurrence extends CalendarRecurrence
{
    
    
    public function __construct($month, $day, $rangeStart, $rangeEnd, $interval)
    {
        $this->recurType = self::RECUR_TYPE_ANNUALSTATIC;
        $this->month = $month;
        $this->day = $day;
        
        parent::__construct($rangeStart, $rangeEnd, $interval);
        
    }
    
    public function occursOnDate($date)
    {
        $month = date('m',$date);
        $day = date('d',$date);
        
        $match = ($month == $this->month && $day == $this->day);
        
        $match = $match && ($this->rangeStart <= $date);
        
        if($this->rangeEnd)
        {
            $match = $match && ($this->rangeEnd >= $date);
        }
        
        if($match && $this->interval && $this->interval > 1)
        {
            //How many months has it been since the start date?
            $startYear = date('Y', $this->rangeStart);
            
            $thisYear = date('Y', $date);
            
            $intervalYear = ($thisYear - $startYear) % $this->interval;
            
            $match = $match && ($intervalYear == 0);
        }          
        
        return $match;        
    
    }

}


?>