<?php

return array(
  '#namespace' => 'App',
  '#uses' => array (
  'HTTP' => 'App\\Bootstrap\\HTTP\\Handler',
  'Exception' => 'Exception',
),
  '#traitMethodOverrides' => array (
  'App\\Server' => 
  array (
  ),
),
);

