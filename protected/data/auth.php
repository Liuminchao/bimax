<?php

$operation=require(dirname(__FILE__).'/operation.php');
$task=require(dirname(__FILE__).'/task.php');
$role=require(dirname(__FILE__).'/role.php');
//return array_merge($operation,$task,$role);
 return $operation+$task;