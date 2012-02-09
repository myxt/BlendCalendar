<?php

/**
 * Represents a once-per-year event.
 */
class CalendarAnnualRelativeRecurrence extends CalendarRecurrence
{
    
    public $sunday;
    public $monday;
    public $tuesday;
    public $wednesday;
    public $thursday;
    public $friday;
    public $saturday;
    
    public $days;
    public $weekNum;
    
    //Provide an array of days
    public function __construct($month, $weekNum, $days, $rangeStart, $rangeEnd, $interval = false)
    {
        $this->recurType = self::RECUR_TYPE_ANNUALRELATIVE;
        
        $allDays = array('sunday', 'monday','tuesday','wednesday','thursday','friday','saturday');
        
        foreach($days as $dayNum)
        {
            $dayName = $allDays[$dayNum];
            $this->$dayName = 1;
        }
        
        $this->month = $month;
        
        $this->days = $days;
        $this->weekNum = $weekNum;
        
        parent::__construct($rangeStart, $rangeEnd, $interval);
    }
    
    public function occursOnDate($date)
    {
        
        $month = date('n',$date);

        $match = ($month == $this->month);
        
        if(!$match)
        {
            return $match;
        }

        $day = date('j',$date);
        $year = date('Y',$date);        
        
        $dow = date('w',$date);

        $match = in_array($dow, $this->days); //Is this the right day
        
        if(!$match)
        {
            return $match; //If it's not, quit now
        }
        
        
        $thisWeekNum = 0;
        
        if($this->weekNum >= 0) //Calc from start of the month
        {
            $monthStart = mktime(0,0,0,$month,1,$year);
            $monthStartWeek = date('W',$monthStart);
            
            $currWeek = date('W',$date);
            
            $offset = 1;
            
            //What day did this month start on?
            $monthFirstDow = date('w', $monthStart);
            
            if($dow < $monthFirstDow)
            {
                $offset = 0;
            }
            
            $thisWeekNum = ($currWeek - $monthStartWeek + $offset);
            
        }        
        else //Calc from end of the month
        {
            //TODO Fix Last X of Month bug
        
            $nextMonthStart = mktime(0,0,0,$month + 1, -1, $year);
            $nextMonthWeek = date('W',$nextMonthStart);

            $currWeek = date('W',$date);

            //What day does next month start on?
            $nextMonthFirstDow = date('w', $nextMonthStart);

            $offset = 0;
            
            if($dow <= $nextMonthFirstDow)
            {
                $offset = -1;
            }
            
            $thisWeekNum = $currWeek - $nextMonthWeek + $offset;
        }
        
        $match = $match && ($thisWeekNum == $this->weekNum);
        
        
        $match = $match && ($this->rangeStart <= $date);
        
        if($this->rangeEnd)
        {
            $match = $match && ($this->rangeEnd >= $date);
        }
        
        if($match && $this->interval && $this->interval > 1)
        {
            //How many years has it been since the start date?
            $startYear = date('Y', $this->rangeStart);
            
            $thisYear = date('Y', $date);
            
            $intervalYear = ($thisYear - $startYear) % $this->interval;
            
            $match = $match && ($intervalYear == 0);
        }         
        
        return $match;
    
    }

}


?>