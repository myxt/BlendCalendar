<?php
$Module = array( 'name' => 'BlendCalendar',
                 'variable_params' => true );

$ViewList = array();


$ViewList['30boxes'] = array(
    'functions' => array('results'),
    'script' => '30boxes.php',
    'params'=> array(
        'ContentClassAttributeId',
        'Year',
        'Month'
    ),
    'unordered_params' => array(
        'subtree'=>'SubTree',
        'parent'=>'Parent'
        ) );

$FunctionList = array();
$FunctionList['results'] = array( );

?>