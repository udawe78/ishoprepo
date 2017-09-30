<?php
include_once("classes.php");
$u1=new User('Robert','123');
var_dump($u1);
//$u1->intoDb();
echo '<br/>';
echo '<br/>';
$u2=User::fromDb(17);
var_dump($u2);

?>