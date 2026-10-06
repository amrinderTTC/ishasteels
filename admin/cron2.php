<?php
// the message
$msg = "Today total Dispatched is 10000 eg";

// use wordwrap() if lines are longer than 70 characters
$msg = wordwrap($msg,70);

// send email
mail("amit.angel.verma@gmail.com","Isha steel Reports",$msg);
?>