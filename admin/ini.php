<?php

 include 'connect.php';//database connetion
//Routes
  
  $css= 'layout/css/';  // css route
  $js='layout/js/';    // js route
  $tep= 'includes/templates/';  // templates route
  $lg='includes/laungages/';//langauges route
  include 'includes/functions/functions.php'; 
  include $lg.'en.php';
  include $tep.'header.php';
 //
  if(!isset($Nonavbar)){
    include $tep.'navbar.php';
  }
