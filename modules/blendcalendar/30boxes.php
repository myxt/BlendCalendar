<?php

include_once( "kernel/common/template.php" );
include_once( "lib/ezutils/classes/ezhttptool.php" );

$tpl = templateInit();
$http = new eZHTTPTool();

$tpl->setVariable('contentclassattribute_id', $ContentClassAttributeId);
$tpl->setVariable('month', $Month);
$tpl->setVariable('year', $Year);
$tpl->setVariable('parent_node_id', $Parent);
$tpl->setVariable('subtree', $SubTree);

$content = $tpl->fetch( "design:blendcalendar/30boxes.tpl" );

/*
$Result['content']=$content;
*/

echo $content;

eZExecution::cleanup();
eZExecution::setCleanExit();

exit();
?>