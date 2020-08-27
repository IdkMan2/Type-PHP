<?php

return array(
  '#namespace' => 'App\\Bootstrap\\Annotations',
  '#uses' => array (
  'EventListenerPriority' => 'App\\Bootstrap\\Enums\\EventListenerPriority',
  'BadMethodCallException' => 'BadMethodCallException',
  'InvalidArgumentException' => 'InvalidArgumentException',
  'IAnnotation' => 'mindplay\\annotations\\IAnnotation',
),
  '#traitMethodOverrides' => array (
  'App\\Bootstrap\\Annotations\\EventListenerAnnotation' => 
  array (
  ),
),
  'App\\Bootstrap\\Annotations\\EventListenerAnnotation' => array(
    array('#name' => 'usage', '#type' => 'mindplay\\annotations\\UsageAnnotation', 'method'=>true, 'inherited'=>true)
  ),
);

