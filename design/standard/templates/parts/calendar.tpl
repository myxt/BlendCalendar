{* Utility template for rendering 30 boxes views - override to modify *}

{* What is the class called that will be used for the calendar event? Defaults to 'event' *}
{if is_set($event_class_identifier)|not}
	{def $event_class_identifier='event'}
{/if}

{* What is the blendcalendar event field called? Defaults to 'event' *}
{if is_set($event_attribute_identifier)|not}
	{def $event_attribute_identifier='event'}
{/if}

{* What subtree should we query? If unset, will try to use the current node, else '2' *}
{if is_set($subtree)|not}
	{def $subtree=first_set($node.id,$module_result.node_id,2)}
{/if}

{* Should we display the 30 boxes view? Default is true *}
{if is_set($display_boxes)|not}
	{def $display_boxes=true()}
{/if}

{* Should we display the list of events? Default is true *}
{if is_set($display_list)|not}
	{def $display_list=true()}
{/if}

{* Display event names in the date box? Defaults to false *}
{if is_set($display_in_boxes)|not}
	{def $display_in_boxes=false()}
{/if}

{* Display navigation links? Defaults to false *}
{if is_set($display_links)|not}
	{def $display_links=false()}
{/if}

{* What CSS class should be assigned to the calendar box? Defaults to 'calendar 30-boxes' *}
{if is_set($css_class)|not}
	{def $css_class='calendar 30-boxes'}
{/if}

{if is_set($subview_type)|not}
	{def $subview_type='line'}
{/if}

{def 
	$curr_time = currentdate() 
	$show_month = $curr_time|datetime('custom','%n')
	$show_year = $curr_time|datetime('custom','%Y')
	$show_day = $curr_time|datetime('custom','%j')
	$current_day = $curr_time|datetime('custom','%j')
}
{if and(is_set($month), $month)}
	{set $show_month=$month}
{/if}
{if and(is_set($year), $year)}
	{set $show_year=$year}
{/if}
{if and(is_set($day), $day)}
	{set $show_day = $day}
{/if}

{def $range_start = makedate($show_month, 1, $show_year)
	 $days = $range_start|datetime( custom, '%t')
	 $last_day = makedate($show_month, $days, $show_year)
     $range_end = $last_day|sum(86399)

}

{if is_set($event_attribute_id)|not}
	{def $event_class = fetch('content','class',hash('class_id', $event_class_identifier))
		 $event_attribute = $event_class.data_map[$event_attribute_identifier]
		 $event_attribute_id = $event_attribute.id
	}
{/if}


{def $events=fetch('blendcalendar','range',hash(
	'contentclassattribute_id', $event_attribute_id,
	'start_time', $range_start,
	'end_time', $range_end,
	'subtree', $subtree,
	'group_by', 'day'
	))}

{if $display_boxes}

{def    
	$calendar_node = fetch('content','node',hash('node_id',$subtree))
    $url_reload=concat( $calendar_node.url_alias, "/(day)/", $show_day, "/(month)/", $show_month, "/(year)/", $show_year, "/offset/2")
    $url_back=concat( $calendar_node.url_alias,  "/(month)/", sub($show_month, 1), "/(year)/", $show_year)
    $url_forward=concat( $calendar_node.url_alias, "/(month)/", sum($show_month, 1), "/(year)/", $show_year)
}
{if $show_month|eq(1)}
{set $url_back=concat( $calendar_node.url_alias,  "/(month)/", 12, "/(year)/", $show_year|sub(1))}
{/if}
{if $show_month|eq(12)}
{set $url_forward=concat( $calendar_node.url_alias,  "/(month)/", 1, "/(year)/", $show_year|sum(1))}
{/if}
<div class="calendar 30-boxes">
	<table cellspacing="0" class="blendcalendar" cellpadding="0" summary="Event Calendar">
		<thead>
			<tr class="calendar-heading">
				<th class="calendar-heading-prev first-col">
					{if $display_links}
					<a href={$url_back|ezurl} title=" Previous month ">&#8249;&#8249;</a></th>
					{/if}
				<th class="calendar-heading-date" colspan="5">{$range_start|datetime( custom, '%F' )|upfirst()}&nbsp;{$show_year}</th>
				<th class="calendar_heading_next last_col">
					{if $display_links}
					<a href={$url_forward|ezurl} title=" Next Month ">&#8250;&#8250;</a>
					{/if}
				</th>
			</tr>
			<tr class="calendar_heading_days">
				<th class="first_col">{"Sun"|i18n("design/ezwebin/full/event_view_calendar")}</th>
				<th>{"Mon"|i18n("design/ezwebin/full/event_view_calendar")}</th>
				<th>{"Tue"|i18n("design/ezwebin/full/event_view_calendar")}</th>
				<th>{"Wed"|i18n("design/ezwebin/full/event_view_calendar")}</th>
				<th>{"Thu"|i18n("design/ezwebin/full/event_view_calendar")}</th>
				<th>{"Fri"|i18n("design/ezwebin/full/event_view_calendar")}</th>
				<th class="last_col">{"Sat"|i18n("design/ezwebin/full/event_view_calendar")}</th>
			</tr>
		</thead>
		<tbody>
			{def $counter=1 $col_counter=1 $col_end=0 $dayofweek = 0 $day_timestamp=0}
			{while le( $counter, $days )}
				{set $dayofweek = makedate( $show_month, $counter, $show_year )|datetime( custom, '%w' )
					 $col_end = or( eq( $dayofweek, 6 ), eq( $counter, $days ) )}
				{if or( eq( $counter, 1 ), eq( $dayofweek, 0 ) )}
					<tr class="week">
				{/if}			
				{* If the month doesn't start at the start of the week, add in some empty squares *}
				{if and($counter|eq(1), $dayofweek|gt(0))}
					{for 0 to $dayofweek|sub(1) as $i}
					<td class="nonmonth-day">&nbsp;</td>
					{/for}
				{/if}

				<td class="day {if eq($counter, $show_day)}selected-day{/if} {if eq($counter, $current_day)}current-day{/if}">
					<span class="day-number">
				{set $day_timestamp=makedate($show_month, $counter, $show_year)}
				{if is_set($events[$day_timestamp]) }
					<a class="day-link" href={concat( $subtree.url_alias, "/(day)/", $counter, "/(month)/", $show_month, "/(year)/", $show_year)|ezurl}>{$counter}</a>
				{else}
					{$counter}
				{/if}
					</span>
					
					{if and($display_in_boxes, is_set($events[$day_timestamp]))}
					{foreach $events[$day_timestamp] as $event}
					<p class="event">
						<span class="time">{$event.start_time|datetime('custom', '%h:%i %a')}</span> <a href="#">{$event.object.name|wash}</a>
					</p>
					{/foreach}
					{/if}
				</td>
				
				{if and($counter|eq($days), $dayofweek|lt(6))}
					{for $dayofweek|sum(1) to 6 as $i}
					<td class="nonmonth-day">&nbsp;</td>
					{/for}
				{/if}
				
				{if $col_end}
					</tr>
				{/if}
				{set $counter=inc( $counter )}
			{/while}
		
		</tbody>
	</table>
</div>

{/if}

{if $display_list}
<div class="calendar-list">
	{foreach $events as $day=>$items}
	<div class="calendar-day calendar-day-{$day|datetime('custom','%j')}">
		<h4 class="day-name">{$day|datetime('custom','%D, %M %j')}</h4>
		{foreach $items as $item}
		<div class="event">
		{node_view_gui view=$subview_type content_node=$item.object.main_node occur_day=$item.occur_day}
		</div>
		{/foreach}
	</div>
	{/foreach}
</div>
{/if}


