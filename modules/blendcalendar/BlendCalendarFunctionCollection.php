<?php

class BlendCalendarFunctionCollection
{

    function getRange($contentClassAttributeId, $startTime, $endTime, $filters = array(), $parentNodeId=false, $subTree=false, $groupBy=false)
    {
        //echo "S: $startTime - E: $endTime";
        if($startTime && !is_numeric($startTime))
        {
            $startTime = strtotime($startTime);
        }
        
        if($endTime && !is_numeric($endTime))
        {
            $endTime = strtotime($endTime);
        }
        else
        {
            $endTime = strtotime(date('n/t/Y', $startTime)); //End of the current month
        }
        
        $resultType = CalendarEvent::FETCH_DAYS;
        
        if ($groupBy == 'day') {
        	$resultType = CalendarEvent::FETCH_DAYS;
        }

        $events = CalendarEvent::getEventsInRange(
        	$contentClassAttributeId, 
        	$startTime, 
        	$endTime, 
        	$filters, 
        	$parentNodeId, 
        	$subTree, 
        	$resultType
		);
        
        return array('result'=>$events);
    }

}

?>