    <table class="ezagenda_month_event" cellpadding="0" cellspacing="0" summary="Preview of event">
    <tr>
    <td class="ezagenda_month_label">
        <h2>
        <span class="ezagenda_month_label_date">{$occur_day|datetime(custom,"%j")}</span>
        {$occur_day|datetime(custom,"%M")|extract_left( 3 )}
        </h2>
    </td>
    <td class="ezagenda_month_info">

    <h4><a href={$event.url_alias|ezurl}>{$node.name|wash}</a></h4>

    <p>
    <span class="ezagenda_date">
    {$occur_day|datetime(custom,"%j %M")|shorten( 6 , '')}
    {if and($node.object.data_map.to_time.has_content,  ne( $node.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M"), $node.object.data_map.from_time.content.timestamp|datetime(custom,"%j %M") ))}
        - {$node.object.data_map.to_time.content.timestamp|datetime(custom,"%j %M")|shorten( 6 , '')}
    {/if}
    </span>
    
    {if $node.object.data_map.category.has_content}
    <span class="ezagenda_keyword">
    {attribute_view_gui attribute=$node.object.data_map.category}
    </span>
    {/if}
    </p>
    
    {if $node.object.data_map.text.has_content}
        <div class="attribute-short">{attribute_view_gui attribute=$node.object.data_map.text}</div>
    {/if}

    </td>
    </tr>
    </table>
