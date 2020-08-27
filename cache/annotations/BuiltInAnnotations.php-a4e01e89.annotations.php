<?php

return array(
  '#namespace' => 'App\\Bootstrap\\Traits',
  '#uses' => array (
  'EventListenerAnnotation' => 'App\\Bootstrap\\Annotations\\EventListenerAnnotation',
  'Annotations' => 'mindplay\\annotations\\Annotations',
),
  '#traitMethodOverrides' => array (
  'App\\Bootstrap\\Traits\\BuiltInAnnotations' => 
  array (
  ),
),
);

