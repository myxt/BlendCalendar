<?php

$FunctionList = array();

$FunctionList['range'] = array( 'name' => 'range',
                                         'operation_types' => array( 'read' ),
                                         'call_method' => array( 'class' => 'BlendCalendarFunctionCollection',
                                                                 'method' => 'getRange' ),
                                         'parameter_type' => 'standard',
                                         'parameters' => array( array( 'name' => 'contentclassattribute_id',
                                                                       'type' => 'integer',
                                                                       'required' => true,
                                                                       'default' => false ),
                                                                array( 'name' => 'start_time',
                                                                       'type' => 'integer',
                                                                       'required' => true,
                                                                       'default' => false ),
                                                                array( 'name' => 'end_time',
                                                                       'type' => 'integer',
                                                                       'required' =>true,
                                                                       'default' => false ),
                                                                array( 'name' => 'filters',
                                                                       'type' => 'array',
                                                                       'required' => false,
                                                                       'default' => null ),                                                                                                                                              
                                                                array( 'name' => 'parent_node_id',
                                                                       'type' => 'integer',
                                                                       'required' => false,
                                                                       'default' => null ),                                                                                                                                              
                                                                array( 'name' => 'subtree',
                                                                       'type' => 'integer',
                                                                       'required' => false,
                                                                       'default' => null ),                                                                                                                                              
                                                                        ),
                                                                array( 'name' => 'group_by',
                                                                       'type' => 'string',
                                                                       'required' => false,
                                                                       'default' => 'linear' )
															);

?>