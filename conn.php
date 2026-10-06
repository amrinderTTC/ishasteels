<?php
session_start();
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED & ~E_WARNING);
date_default_timezone_set ("Asia/Kolkata");
$createdby = $_SESSION['uid'];
$createdon = date('Y-m-d H:i:s');
$domaincompany = "Isha Trading";
if($_SERVER['HTTP_HOST']=='localhost' || $_SERVER['HTTP_HOST']=='192.168.1.7' || $_SERVER['HTTP_HOST']=='192.168.1.46' || $_SERVER['HTTP_HOST']=='isha3.test'){
	/* OFFLINE */


	$GLOBALS['conn1']=$conn=mysqli_connect("localhost", "root", "") or die("CanNot connect to DB");
	mysqli_select_db($conn, "ishaerp");

	$mainurl="https://ishaerp.com/";
	$sitepath="https://ishaerp.com/";
	$uploadloc = 'https://ishaerp.com/admin/assets/uploads/';
	
	//smtp settings
	//global $smtpisAuth,$smtphost,$smtpuser,$smtppass,$smtpport,$sentFrom,$replyto;
	
	$smtpisAuth='1'; //if 1 yes;0 no
	$smtphost = '';
	$smtpuser='';
	$smtppass='';
	$smtpport ='25';
	
	// $mailreplyto ='replyto@localhost.com';
	// $mailreplytoname = 'Administrator 2';
	$sentFrom=['admin@localhost.com','Administrator']; // emailaddress and username
	$replyto = ['replyto@localhost.com','Administrator 2'];
	
}else{
    
    $GLOBALS['conn1']=$conn= mysqli_connect("localhost","ishaerp_ishaerp", "Hostisha@147","ishaerp_ttcrobot_ishatrade") or die(mysqli_error()."DB connection Error");
	// use PHPMailer\PHPMailer\PHPMailer;
	// use PHPMailer\PHPMailer\SMTP;
	// require 'vendor/autoload.php';
	/*online*/
	// $GLOBALS['conn1']=$conn= mysqli_connect("localhost", "ttcrobot_userdas", "oFEWM[yt0Zws","ttcrobot_dashmesh") or die(mysqli_error()."DB connection Error");
	// $mainurl="http://biplfoundry.com/erp/";
	// $sitepath="/home2/biplfoundry/public_html/erp/";
	// $uploadloc = '/assets/uploads/';
	
	
	
}
 define('MAINURL',$mainurl);
define('SITEPATH',$sitepath);
include_once "functions.php";

?>