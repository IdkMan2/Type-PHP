<?php

return array(
  '#namespace' => 'App',
  '#uses' => array (
  'AppEnabledEvent' => 'App\\Bootstrap\\Events\\AppEnabledEvent',
  'MySqlDB' => 'App\\Bootstrap\\MySQL\\MySqlDB',
  'Log' => 'App\\Bootstrap\\Providers\\Log',
  'ApplicationBase' => 'App\\Bootstrap\\Traits\\ApplicationBase',
  'BuiltInAnnotations' => 'App\\Bootstrap\\Traits\\BuiltInAnnotations',
  'IocContainer' => 'App\\Bootstrap\\Traits\\IocContainer',
  'EventsManager' => 'App\\Bootstrap\\Traits\\EventsManager',
  'Env' => 'App\\Bootstrap\\Utils\\Env',
  'Path' => 'App\\Bootstrap\\Utils\\Path',
  'Exception' => 'Exception',
  'AnnotationCache' => 'mindplay\\annotations\\AnnotationCache',
  'Annotations' => 'mindplay\\annotations\\Annotations',
  'Debugger' => 'Tracy\\Debugger',
),
  '#traitMethodOverrides' => array (
  'App\\Application' => 
  array (
  ),
),
  'App\\Application::appEnableEventListener' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'AppEnabledEvent', 'name' => 'event'),
    array('#name' => 'EventListener', '#type' => 'App\\Bootstrap\\Annotations\\EventListenerAnnotation', 'priority'=>'NORMAL')
  ),
);

