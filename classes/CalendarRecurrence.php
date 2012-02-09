<?php

abstract class CalendarRecurrence
{

    public $recurType;
    public $month;
    public $day;
    public $year;
    public $weekNum;
    public $dow;

    public $sunday;
    public $monday;
    public $tuesday;
    public $wednesday;
    public $thursday;
    public $friday;
    public $saturday;

    public $rangeStart; //unix timestamp indicating start of rule
    public $rangeEnd; //unix timestamp indicating end of rule

    public $interval; //integer specifying skip intervals (every 2nd week, etc)
    
    const RECUR_TYPE_SINGLE = 1;
    const RECUR_TYPE_WEEKLY = 2;
    const RECUR_TYPE_MONTHLYSTATIC = 4;
    const RECUR_TYPE_MONTHLYRELATIVE = 8;
    const RECUR_TYPE_ANNUALSTATIC = 16;
    const RECUR_TYPE_ANNUALRELATIVE = 32;
    
    const HOUR = 3600;
    const DAY = 86400; //3600 * 24;
    const YEAR = 31536000; //86400 * 365;

    //Instantiate a Recurrence rule from an FETCH_ASSOC'd database row
    public static function createFromRow($row)
    {
        $recur = false;
        $allDays = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');

        switch($row['recurrence_type'])
        {
            case self::RECUR_TYPE_SINGLE:
                $recur = new CalendarSingleOccurrence($row['month'], $row['day'], $row['year'], $row['range_start'], $row['range_end'],  $row['interval']);
            break;
            
            case self::RECUR_TYPE_WEEKLY:
                $days = array();

                foreach($allDays as $index=>$day)
                {
                    if($row[$day])
                    {
                        $days[]=$index;
                    }
                }
                
                $recur = new CalendarWeeklyRecurrence($days, $row['range_start'], $row['range_end'], $row['interval']);
            break;
            
            case self::RECUR_TYPE_MONTHLYSTATIC:
                $recur = new CalendarMonthlyStaticRecurrence($row['day'], $row['range_start'], $row['range_end'], $row['interval']);
            break;
            
            case self::RECUR_TYPE_MONTHLYRELATIVE:
                $days = array();

                foreach($allDays as $index=>$day)
                {
                    if($row[$day])
                    {
                        $days[]=$index;
                    }
                }
                            
                $recur = new CalendarMonthlyRelativeRecurrence($row['week'], $days, $row['range_start'], $row['range_end'], $row['interval']);
            break;
            
            case self::RECUR_TYPE_ANNUALSTATIC:
                $recur = new CalendarAnnualStaticRecurrence($row['month'], $row['day'], $row['range_start'], $row['range_end'], $row['interval']);
            break;

            case self::RECUR_TYPE_ANNUALRELATIVE:
            
                $days = array();

                foreach($allDays as $index=>$day)
                {
                    if($row[$day])
                    {
                        $days[]=$index;
                    }
                }            
                $recur = new CalendarAnnualRelativeRecurrence($row['month'], $row['week'], $days, $row['range_start'], $row['range_end'], $row['interval']);
            break;
        
        }
        
        return $recur;
    }
    
    public function __construct($rangeStart, $rangeEnd, $interval=false)
    {
        if(!is_numeric($rangeStart))
        {
            $rangeStart = strtotime($rangeStart);
        }
        
        if(!is_numeric($rangeEnd))
        {
            $rangeEnd = strtotime($rangeEnd);
        }
        
        $this->rangeStart = $rangeStart;
        $this->rangeEnd = $rangeEnd;    
    
        $this->interval = $interval;
    }
    
    public function toArray($display=true)
    {
        $mapping = array(
            'month'             =>$this->month,
            'day'               =>$this->day,
            'year'              =>$this->year,
            'recurrence_type'   =>$this->recurType,
            'week'              =>intval($this->weekNum),
            'sunday'            =>$this->sunday,
            'monday'            =>$this->monday,
            'tuesday'           =>$this->tuesday,
            'wednesday'         =>$this->wednesday,
            'thursday'          =>$this->thursday,
            'friday'            =>$this->friday,
            'saturday'          =>$this->saturday,
            'range_start'       =>intval($this->rangeStart),
            'range_end'         =>intval($this->rangeEnd),
            'interval'          =>intval($this->interval)
            );
            
        $fields = array();
        
        foreach($mapping as $key=>$val)
        {
            if($val)
            {
                $fields[$key]=$val;
            }
            else
            {
                $fields[$key]=$display ? null : 'NULL';
            }
        }
        
        return $fields;
    
    }
    
    public abstract function occursOnDate($date);

}

?>