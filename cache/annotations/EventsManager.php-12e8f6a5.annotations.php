<?php

return array(
  '#namespace' => 'App\\Bootstrap\\Traits',
  '#uses' => array (
  'EventListenerAnnotation' => 'App\\Bootstrap\\Annotations\\EventListenerAnnotation',
  'ListenerRegisterExceptionReason' => 'App\\Bootstrap\\Enums\\ListenerRegisterExceptionReason',
  'Event' => 'App\\Bootstrap\\Events\\Event',
  'ListenerRegisterException' => 'App\\Bootstrap\\Exceptions\\ListenerRegisterException',
  'ReflectException' => 'App\\Bootstrap\\Exceptions\\ReflectException',
  'ReflectClass' => 'App\\Bootstrap\\Reflect\\ReflectClass',
  'ReflectMethod' => 'App\\Bootstrap\\Reflect\\ReflectMethod',
),
  '#traitMethodOverrides' => array (
  'App\\Bootstrap\\Traits\\EventsManager' => 
  array (
  ),
),
  'App\\Bootstrap\\Traits\\EventsManager::registerListener' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'object', 'name' => 'listener')
  ),
  'App\\Bootstrap\\Traits\\EventsManager::selectListeners' => array(
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'object', 'name' => 'listener'),
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'array')
  ),
  'App\\Bootstrap\\Traits\\EventsManager::retrieveEventClassName' => array(
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'ReflectMethod[]'),
    array('#name' => 'var', '#type' => 'mindplay\\annotations\\standard\\VarAnnotation', 'type' => 'EventListenerAnnotation'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'object', 'name' => 'listener'),
    array('#name' => 'param', '#type' => 'mindplay\\annotations\\standard\\ParamAnnotation', 'type' => 'string', 'name' => 'methodName'),
    array('#name' => 'return', '#type' => 'mindplay\\annotations\\standard\\ReturnAnnotation', 'type' => 'string')
  ),
);

