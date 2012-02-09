<?php

/**
 * Represents a single calendar occurrence.
 */
class CalendarSingleOccurrence extends CalendarRecurrence
{
    
    private $singleDate;
    
    public function __construct($month, $day, $year)
    {
        $this->recurType = self::RECUR_TYPE_SINGLE;
        
        $this->month = $month;
        $this->day = $day;
        $this->year = $year;

        $this->singleDate = mktime(0,0,0,$this->month, $this->day, $this->year);
        $this->rangeStart = $this->singleDate;
        $this->rangeEnd = $this->singleDate + self::DAY;
        
    }
    
    public function occursOnDate($date)
    {
        //echo "<pre>$date == $this->singleDate"; echo "</pre>";
        //echo "<pre>"; echo date('Y-m-d H:i', $date) . " == " . date('Y-m-d H:i',$this->singleDate) ; echo "</pre>";
        return $date == $this->singleDate;
    
    }
    
    public function toArray($display = true)
    {
        
        $fields = parent::toArray($display);
        
        if($display)
        {
            $fields['singledate']=date('n/d/Y',$this->singleDate);    
        }
        
        return $fields;
        
    }


}


?>