#!/usr/bin/env php
<?php
require 'autoload.php';

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "Blend Calendar Migration Script\n" .
                                                        "Migrate from Blend GCalendar extension to BlendCalendar extension\n" .
                                                        "\n" .
                                                        "./extension/blendcalendar/bin/php/migratefromgcalendar.php --class-id=20 --old-attribute=event_old --new-attribute=event" ),
                                     'use-session' => false,
                                     'use-modules' => true,
                                     'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "[class-id:][old-attribute:][new-attribute:]",
                                "",
                                array( 'class-id' => '(number) Class ID of the event object',
                                       'old-attribute' => '(string) Class Attrib identifier of the GCalendar event attribute',
                                       'new-attribute' => '(string) Class Attrib identifier of the BlendCalendar event attribute') );
$sys = eZSys::instance();

$script->initialize();

//Retrieve all objects of this class id
$classId = $options['class-id'];
$oldAttribName = $options['old-attribute'];
$newAttribName = $options['new-attribute'];

if (!isset($options['class-id']))
{
    $bc_error['class-id'] = 'Please add the class-id option! ';
}

if (!isset($options['old-attribute']))
{
    $bc_error['old-attribute'] = 'Please add the old-attribute option! ';
}

if (!isset($options['new-attribute']))
{
    $bc_error['new-attribute'] = 'Please add the new-attribute option! ';
}

if ($bc_error)
{
    $out = '';
    
    foreach ($bc_error as $key => $val)
    {
        $out .= ">>>" . $val . "\n\t--" . $key . "\n";
    }
    
    $cli->output($out);    
}


$cli->output('Starting Conversion...');

$objects = eZContentObject::fetchSameClassList($classId);
//array_shift($objects);
//For each object


$tzoffset=(3600 * 2) * -1;
foreach($objects as $object)
{
    $cli->output('Converting "' . $object->attribute('name') . '" - ' . $object->attribute('id') . '/' . $object->attribute('main_node_id'));
    
    $versionObj = $object->createNewVersion();
    
    $dataMap = $versionObj->dataMap();
    
    $version = $versionObj->attribute('version');
    
    $oldAttrib = $dataMap[$oldAttribName];
    $newAttrib = $dataMap[$newAttribName];
    
    //Parse the XML representation of the gcal event
    $xml = $oldAttrib->attribute('data_text');
    
    $timeParams = GCalendar::getTimeArrayFromXML($xml);
    
    //echo "<pre>"; var_dump($timeArray); echo "</pre>";
    //Populate the blendcal event
    $newAttribId = $newAttrib->attribute('id');
    
    $recur = null;
    
    switch($timeParams['recurrence_type'])
    {
        case 'WEEKLY':
            $interval = $timeParams['recurrence_interval'];
            $rangeStart = $timeParams['start_time'];
            $rangeEnd = $timeParams['recur_until_time'];
            
            if($timeParams['end_time'] > $rangeEnd)
            {
                $rangeEnd = $timeParams['end_time'];
            }
            
            $days = translateDays($timeParams['recurrence_days']);
            $recur = new CalendarWeeklyRecurrence($days, $rangeStart, $rangeEnd, $interval);
        break;
        
        case 'YEARLY':            
            $interval = $timeParams['recurrence_interval'];
            $rangeStart = $timeParams['start_time'];
            $rangeEnd = $timeParams['recur_until_time'];
            $month = $timeParams['from_month'];
            $day = $timeParams['from_day'];
            
            if($timeParams['end_time'] > $rangeEnd)
            {
                $rangeEnd = $timeParams['end_time'];
            }
            
            $recur = new CalendarAnnualStaticRecurrence($month, $day, $rangeStart, $rangeEnd, $interval);
        break;
        
        case 'MONTHLY':            
            $interval = $timeParams['recurrence_interval'];
            $rangeStart = $timeParams['start_time'];
            $rangeEnd = $timeParams['recur_until_time'];

            $day = $timeParams['from_day'];
            
            if($timeParams['end_time'] > $rangeEnd)
            {
                $rangeEnd = $timeParams['end_time'];
            }
            
            $recur = new CalendarMonthlyStaticRecurrence($day, $rangeStart, $rangeEnd, $interval);
        break;
                
        
        default: //Single Occurrence
            $date = $timeParams['start_time'];
            
            $recur = new CalendarSingleOccurrence(date('n',$date), date('j',$date), date('Y', $date));
            
    }

    $startTime = false;
    $duration = false;
    
    $startTime = null;
    $endTime = null;
    
    if(!$timeParams['all_day'])
    {
        $tz = date_default_timezone_get();
        date_default_timezone_set('UTC');    
        /*
        if($timeParams['from_time'] == '02:00 am' && $timeParams['to_time'] == '02:00 am' )
        {
            $timeParams['from_time']='12:00 am';
            $timeParams['to_time']='12:00 am';
        }
        */
        $startTime = strtotime($timeParams['from_time']);

        $startTime += $tzoffset;
        
        echo "$timeParams[from_time] = $startTime (" . date('Y-m-d H:i:s', $startTime) . ')';
        //Convert to seconds since midnight
        $startTime = $startTime % 86400;
            
        echo "| $startTime = " . ($startTime/3600) . "\r\n";
            
        if($timeParams['to_time'])
        {
            $endTime = strtotime($timeParams['to_time']);
            
            $endTime += $tzoffset;

            $endTime = $endTime % 86400;
                       
            if($endTime < $startTime) //Assume the end time is on the next day if it's less than start (eg 11pm - 2am)
            {
                $endTime += 86400;
            }
            
            $duration = $endTime - $startTime;
        }
        
        date_default_timezone_set($tz);
        
    }
    else
    {
        $startTime = 0;
        $endTime = 0;
    }
    
    
    $event = new CalendarEvent($startTime, $duration, $recur);
    $event->contentObjectAttributeId=$newAttribId;
    $event->version=$version;
    $event->save();


    $operationResult = eZOperationHandler::execute( 'content', 'publish', array( 'object_id' => $object->attribute( 'id' ), 'version' => $version ) );

//    die();
    //Save and publish the result as a new version 
}

$script->shutdown();

function translateDays($days)
{
    $daycodes = array('SU'=>0,'MO'=>1,'TU'=>2,'WE'=>3,'TH'=>4,'FR'=>5,'SA'=>6);
    
    $result = array();
    
    foreach($days as $day)
    {
        $result[]=$daycodes[$day];
    }
    
    return $result;
}

?>