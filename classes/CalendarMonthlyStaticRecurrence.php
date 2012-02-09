<?php


class CalendarMonthlyStaticRecurrence extends CalendarRecurrence
{

    public $day;
    
    //Provide the integer date on which the event recurs
    public function __construct($day, $rangeStart, $rangeEnd, $interval = false)
    {
        $this->recurType = self::RECUR_TYPE_MONTHLYSTATIC;
        $this->day = $day;
        
        parent::__construct($rangeStart, $rangeEnd, $interval);
    }

    public function occursOnDate($date)
    {
    //echo "[d:" . date('n/j/Y',$date) . "=" . $this->day . "]";
        $testDay = date('j',$date);
        $match = $this->day == $testDay;
        
        $match = $match && ($this->rangeStart <= $date);
        
        if($this->interval && $this->interval > 1)
        {
            //How many months has it been since the start date?
            $startMonth = date('n', $this->rangeStart);
            $startYear = date('Y', $this->rangeStart);
            
            $startMonth = $startMonth + ($startYear * 12);
            
            $thisMonth = date('n', $date);
            $thisYear = date('Y', $date);
            
            $thisMonth = $thisMonth + ($thisYear * 12);

            $intervalMonth = ($thisMonth - $startMonth) % $this->interval;
            
            $match = $match && ($intervalMonth == 0);
        }
        
        
        if($this->rangeEnd)
        {
            $match = $match && ($this->rangeEnd >= $date);
        }        
        //var_dump($match); echo "<br />";
        
        return $match;
    }
    
/*
    public function toArray($display = true)
    {
        
        $fields = parent::toArray($display);
        
        if($display)
        {
            $fields['singledate']=date('n/d/Y',$this->singleDate);    
        }
        
        return $fields;
        
    }
*/
}


?>