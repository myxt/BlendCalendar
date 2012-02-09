<?php

/*!
  \class   blendcalendartype blendcalendartype.php
  \ingroup eZDatatype
  \brief   Handles the datatype gcalendar. By using gcalendar you can ...
  \version 1.0
  \date    Monday September 21 2009 01:14:46 pm
  \author  Joe Kepley

  

*/

include_once( "kernel/classes/ezdatatype.php" );

//require_once( 'extension/gcalendar/datatypes/gcalendar/gcalendar.php' );


class BlendCalendarType extends eZDataType
{

    const DATA_TYPE_STRING = "blendcalendar";
    /*!
      Constructor
    */
    function BlendCalendarType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, "Blend Calendar Event" );
    }
    
    /* **** CLASS SETTINGS **** */
    
    /* No default value for class settings
    function initializeClassAttribute( $classAttribute )
    {
    }
    */
    
    /* FetchClassAttributeHTTPInput handles storage - no extra processing needed
    function storeClassAttribute( $contentClassAttribute, $version )
    {
    }    
    */
    
    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
    }    
    
        
    
    /* **** OBJECT SETTINGS **** */

    /*!
     Validates input on content object level
     \return eZInputValidator::STATE_ACCEPTED or eZInputValidator::STATE_INVALID if
             the values are accepted or not
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $id = $contentObjectAttribute->attribute( 'id' );
        
        $recurType = $http->postVariable($base . '_recurtype_' . $id);
        $singleDate = $http->postVariable($base . '_singledate_' . $id);
        $weekdays = $http->postVariable($base . '_weekdays_' . $id);

        $monthRecurType = $http->postVariable($base . '_monthrecurtype_' . $id);
        $monthStaticRecurDate = $http->postVariable($base . '_monthstaticrecurdate_' . $id);
        $monthRelativeWeek = $http->postVariable($base . '_monthrelativeweek_' . $id);
        $monthRelativeDay = $http->postVariable($base . '_monthrelativeday_' . $id);

        $yearRecurType = $http->postVariable($base . '_yearrecurtype_' . $id);
        $yearStaticRecurMonth = $http->postVariable($base . '_yearstaticrecurmonth_' . $id);
        $yearStaticRecurDate = $http->postVariable($base . '_yearstaticrecurdate_' . $id);
        $yearRelativeWeek = $http->postVariable($base . '_yearrelativeweek_' . $id);
        $yearRelativeDay = $http->postVariable($base . '_yearrelativeday_' . $id);
        $yearRelativeMonth = $http->postVariable($base . '_yearrelativemonth_' . $id);

        $rangeStart = $http->postVariable($base . '_rangestart_' . $id);
        $rangeEndType = $http->postVariable($base . '_rangeendtype_' . $id);
        $rangeEnd = $http->postVariable($base . '_rangeend_' . $id);
        
        $timeStart = $http->postVariable($base . '_timestart_' . $id);
        $timeEnd = $http->postVariable($base . '_timeend_' . $id);
        $allDay = $http->postVariable($base . '_allday_' . $id);

        $interval = $http->postVariable($base . '_interval_' . $id);
        

        
        switch($recurType)
        {
            case 'ONCE':
                if(!strtotime($singleDate))
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         "Please enter a valid occurrence date." ) );
        
                    return eZInputValidator::STATE_INVALID;        
                }            
            break;
            case 'WEEK':
                if(!$weekdays)
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         "Please select at least one day of the week." ) );
        
                    return eZInputValidator::STATE_INVALID;        
                
                }
            break;

        }
                    
        if($recurType != 'ONCE')
        {
            $fromTimestamp = strtotime($rangeStart);
            $toTimestamp = strtotime($rangeEnd);
            
            if($fromTimestamp > $toTimestamp && $rangeEndType != 'NULL')
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                     "The End Date must be before the Start Date." ) );
    
                return eZInputValidator::STATE_INVALID;        
            }
        }               
        return eZInputValidator::STATE_ACCEPTED;

    }

    /*!
     Retrieves HTTP Variables from edit form, saves data to DB
     \return true if fetching of class attributes are successfull, false if not
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {

        $id = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObjectAttribute->attribute('version');
        
        $recurType = $http->postVariable($base . '_recurtype_' . $id);
        $singleDate = $http->postVariable($base . '_singledate_' . $id);
        $weekdays = $http->postVariable($base . '_weekdays_' . $id);

        $monthRecurType = $http->postVariable($base . '_monthrecurtype_' . $id);
        $monthStaticRecurDate = $http->postVariable($base . '_monthstaticrecurdate_' . $id);
        $monthRelativeWeek = $http->postVariable($base . '_monthrelativeweek_' . $id);
        $monthRelativeDay = $http->postVariable($base . '_monthrelativeday_' . $id);

        $yearRecurType = $http->postVariable($base . '_yearrecurtype_' . $id);
        $yearStaticRecurMonth = $http->postVariable($base . '_yearstaticrecurmonth_' . $id);
        $yearStaticRecurDate = $http->postVariable($base . '_yearstaticrecurdate_' . $id);
        $yearRelativeWeek = $http->postVariable($base . '_yearrelativeweek_' . $id);
        $yearRelativeDay = $http->postVariable($base . '_yearrelativeday_' . $id);
        $yearRelativeMonth = $http->postVariable($base . '_yearrelativemonth_' . $id);

        $rangeStart = $http->postVariable($base . '_rangestart_' . $id);
        $rangeEndType = $http->postVariable($base . '_rangeendtype_' . $id);
        $rangeEnd = $http->postVariable($base . '_rangeend_' . $id);
        
        $timeStart = $http->postVariable($base . '_timestart_' . $id);
        $timeEnd = $http->postVariable($base . '_timeend_' . $id);
        $allDay = $http->postVariable($base . '_allday_' . $id);

        $interval = $http->postVariable($base . '_interval_' . $id);
        
        $startTime = null;
        $endTime = null;
        $duration = null;
        
        //echo "<pre>"; var_dump($_POST); echo "</pre>";
        
        if(!$allDay)
        {
            $tz = date_default_timezone_get();
            date_default_timezone_set('UTC');    
        
            $startTime = strtotime($timeStart);
            
            //Convert to seconds since midnight
            $startTime = $startTime % 86400;
            
            if($timeEnd)
            {
                $endTime = strtotime($timeEnd);
                
                $endTime = $endTime % 86400;

                if($endTime < $startTime) //Assume the end time is on the next day if it's less than start (eg 11pm - 2am)
                {
                    $endTime += 86400;
                }
                
                $duration = $endTime - $startTime;
            }
            
            date_default_timezone_set($tz);
            
        }
        
        if($recurType == 'MONTH')
        {
            $recurType = $monthRecurType;
        }
        
        if($recurType == 'YEAR')
        {
            $recurType = $yearRecurType;
        }
        
        $recur = null;
        //echo "<pre>"; var_dump($recurType); echo "</pre>";
        switch ($recurType)
        {
            case 'ONCE':
                $singleDate = strtotime($singleDate);
                
                $year = date('Y', $singleDate);
                $month = date('m', $singleDate);
                $day = date('d', $singleDate);
                
                $recur = new CalendarSingleOccurrence($month, $day, $year);
                
            break;
            
            case 'WEEK':
            
                $recur = new CalendarWeeklyRecurrence($weekdays, $rangeStart, $rangeEnd, $interval);
            
            break;
            
            case 'MONTHSTATIC':
            
                $recur = new CalendarMonthlyStaticRecurrence($monthStaticRecurDate, $rangeStart, $rangeEnd, $interval);
            
            break;
            
            case 'MONTHRELATIVE':
                $recur = new CalendarMonthlyRelativeRecurrence($monthRelativeWeek, array($monthRelativeDay), $rangeStart, $rangeEnd, $interval);
            break;

            case 'YEARSTATIC':
                $recur = new CalendarAnnualStaticRecurrence($yearStaticRecurMonth, $yearStaticRecurDate, $rangeStart, $rangeEnd, $interval);
            break;
                    
            case 'YEARRELATIVE':
                $recur = new CalendarAnnualRelativeRecurrence($yearRelativeMonth, $yearRelativeWeek, array($yearRelativeDay), $rangeStart, $rangeEnd, $interval);
            
            break;
            

        }
        
        
        $event = CalendarEvent::load($id, $version);

        if(!$event)
        {
            $event = new CalendarEvent($startTime, $duration, $recur);
            $event->contentObjectAttributeId = $id;
            $event->version = $version;
        }
        else
        {
            $event->startTime = $startTime;
            $event->duration = $duration;
            $event->recurrence = $recur;
            $event->version = $version;
        }
        
        //echo "<pre>"; var_dump($event); echo "</pre>";
        $event->save();
        
    
        return true;
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $id = $contentObjectAttribute->attribute( "id" );
        $version = $contentObjectAttribute->attribute( "version" );

        $eventObj = CalendarEvent::load($id, $version, false);
        
        if(!$eventObj)
        {
            $now = time();
            $hour = $now - ($now % CalendarRecurrence::HOUR);
            $recur = new CalendarSingleOccurrence(date('n',$now), date('j',$now), date('Y',$now));
            $eventObj = new CalendarEvent($hour, CalendarRecurrence::HOUR, $recur);
        }
        
        $event = $eventObj->toArray();

        return $event;
        
    }

    function deleteStoredObjectAttribute( $contentObjectAttribute, $version = null )
    {
        if($version == null)
        {
            $id = $contentObjectAttribute->attribute( "id" );
            
            $event = CalendarEvent::destroy($id, $version);
            
            
        }
    }    

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return "";
    }

    /*!
     Returns the value as it will be shown if this attribute is used in the object name pattern.
    */
    function title( $contentObjectAttribute, $name = null )
    {
        return "";
    }

    /*!
     \return true if the datatype can be indexed
    */
    function isIndexable()
    {
        return true;
    }

}

eZDataType::register( BlendCalendarType::DATA_TYPE_STRING, "BlendCalendarType" );
?>
