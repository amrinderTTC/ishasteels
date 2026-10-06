<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
require 'vendor/autoload.php';

function SetMenuActiveClass($ar){
	if(in_array($_GET['paction'],$ar)){
		return "active open";
	}
}

function check_permission($typ, $typ1="", $typ2="", $typ3=""){
	//if(isset($_SESSION)){
		if($_SESSION["sid"]!="Admin_loggedin"){
			@header("location:index.php");
			echo "<script>window.location.href='index.php';</script>";
			die();
		}elseif($typ || $typ1 || $typ2 || $typ3){
			if($_SESSION["user_typ"]==$typ || $_SESSION["user_typ"]==$typ1 || $_SESSION["user_typ"]==$typ2 || $_SESSION["user_typ"]==$typ3){
				return 1;
			}else{
				return 0;
			}
		}elseif($typ){
			if($_SESSION["user_typ"]==$typ){
				return 1;
			}else{
				return 0;
			}
		}
	// }else{
	// 	@header("location:index.php");
	// 	echo '<script>window.location.href="index.php"</script>';
	// }
	
}

function getColumn($table, $field, $val, $col, $orderby=""){
	if($orderby!=""){
		$or=" order by ".$orderby;
	}
	$q="select $col from $table where $field='$val' $or";
	$q=mysqli_query($GLOBALS["conn"], $q) or die(mysqli_error($GLOBALS["conn"]));
	if($r=mysqli_fetch_array($q)){
		return $r[0];
	}else{
		return false;
	}
} 

function dateDiffInDays($date1, $date2){ 
    // Calulating the difference in timestamps 
    $diff = strtotime($date2) - strtotime($date1); 
      
    // 1 day = 24 hours 
    // 24 * 60 * 60 = 86400 seconds 
    return round($diff / 86400);
}

function date1($dt,$t=1){
	if(!$dt){
		return;
	}
	if($dt!="0000-00-00 00:00:00"){
		if($t){
			return date("d-m-Y h:i:s a", strtotime($dt));
		}else{
			return date("d-m-Y", strtotime($dt));
		}
	}
}

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz_-ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

// function to check if folder path exists if not then folder will be created on specified path
//@variable folderpath
//

function chkfolderexists($dirpath, $mode=0777) {
	return is_dir($dirpath) || mkdir($dirpath, $mode, true);
}

//function to send mail
function send_mail($mailto,$mailtoname,$subject,$body,$ishtml,$redirect='',$msg=''){

	global $smtpisAuth, $smtphost, $smtpuser, $smtppass, $smtpport, $sentFrom, $replyto;

    $mail = new PHPMailer();
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Host = $smtphost;
    $mail->Port = $smtpport;
	$mail->SMTPAUTH = $smtpisAuth;
	$mail->Username=$smtpuser;
	$mail->Password=$smtppass;
	$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
	$mail->CharSet = PHPMailer::CHARSET_UTF8;

    $mail->setFrom($sentFrom[0], (!empty($sentFrom[1])?$sentFrom[1]:$sentFrom[0]));
	if(!empty($replyto[0])){
		$mail->addReplyTo($replyto[0], (!empty($replyto[1])?$replyto[1]:$replyto[0]));
	}else{
		$mail->addReplyTo($sentFrom[0], (!empty($sentFrom[1])?$sentFrom[1]:$sentFrom[0]));
	}
    
    //Set who the message is to be sent to
    $mail->addAddress($mailto, $mailtoname); // mail sent to ,mail sent to name
    $mail->Subject = $subject;
    if($ishtml==1){
        $mail->msgHTML($body);
        //$mail->AltBody = $body;
    }else{
        $mail->Body = $body;
    }

    if (!$mail->send()) {
        echo 'Mailer Error: ' . $mail->ErrorInfo;
    } else {
        // echo 'Message sent!';
		// echo '<script>site'.$redirect.'?msg=Password Reset Link Sent To Your Email Address';
		if(!empty($redirect)){
			echo '<script>window.location.href="'.$redirect.'"</script>';	
			if(!empty($msg)){
				echo '<script>window.location.href="'.$redirect."?msg=".$msg.'"</script>';	
			}
		}
		//echo '<script>window.location.href="'.$redirect."?msg=".$msg.'"</script>';
    }
}
// string to remove space between string and remove all special charaverts
function cleanstring($string) {
	$string = str_replace(' ', '', $string); // Replaces all spaces with hyphens.
 
	return preg_replace('/[^A-Za-z0-9@.]/', '', $string); // Removes special chars.
 }

 //materail qtytype for gate
 function qty_type($qtyp){
	switch($qtyp){
		case 1:
			echo 'Kg';
			break;
		case 2:
			echo 'Mt';
			break;
		case 3:
			echo 'Pcs.';
			break;
		case 4:
			echo 'Bags';
			break;
	}
 }
 //material type for gate
function materail_type($mtype){
	switch($mtype){
		case 1:
			return  'Wire Rod';
			break;
		case 2:
			return 'Billet';
			break;
		case 3:
			return 'Ingot';
			break;
		case 4:
			return 'Scrap';
			break;
		case 5:
			return 'Sponge Iron';
			break;
		case 6:
			return 'Silico Managese';
			break;
		case 7:
			return 'Store';
			break;
	}
}

function ftmtconvert($amt,$typ){
	if($typ=='2'){ // meter
		return ($amt/3.28083);//actual value 3.280839895 insted of 3.281
	}else if($typ=='1'){// ft
		return ($amt*3.28083);
	}
}
/**
 * $tp current value in weight or pc
 * $amt amount of $tp
 * $lengthtype length type from size table 
 * $mtwt value for mtr from size table
 * $ftwt value for ft from size table
 */
// function wtpcconvert($length,$lengthtyp,$mtwght,$ftwght,$amt,$qtytyp){
// 	if($lengthtyp=='1'){ // value is feet
// 		$unit = $ftwght;
// 	}else if($lengthtyp =='2'){ // value is mt
// 		$unit = $mtwght;
// 	}
	
// 	if($qtytyp=='1'){//value is tons
// 		//$value = $amt/$unit;
// 		$z = ($length*$unit)/1000; // per pc weight in tons
// 		$finalvalue = round($amt/$z,1); 
// 	}else if($qtytyp =='2'){//value in pcs.
// 		//$value = $amt*$unit;
// 		$z = ($length*$unit)/1000; // per pc weight in tons
// 		$finalvalue = round($z*$amt,3);
// 	}
// 	return $finalvalue;
// 	// return $amt.'_'.$qtytyp.'_'.$unit;
// }

/**
 * function to convert weight from quintal to tons
 * @weightinquintal weight in quintals
 */
function quintaltotons($weightinquintal){
    var_Dump($weightinquintal);
	return ($weightinquintal/10); //return weight in tons
}

/**
 * function to calculate per pc weight in tons
 * @length standerd length of pc
 * @lenunit standard length unit
 * @mtweight standard weight per meter
 * @ftweight starndard weight per feet
 */
function calpcwght($length,$lenunit,$mtweight,$ftwegiht){///get weight per pc
	if($lenunit=='1'){//feet
		$pcwght = ((float)$length*(float)$ftwegiht)/1000;
	}else if($lenunit=='2'){ // meter
		$pcwght = ((float)$length*(float)$mtweight)/1000;
	}
	return $pcwght;
}

/**
 * function to calcualte no of pcs from weight given in tons
 * @length standerd length of pc
 * @lenunit standard length unit
 * @mtweight standard weight per meter
 * @ftweight starndard weight per feet
 * @qty quantity to convert
 * @qtunit qty to convert units
 */
// function wtpcconvert($length,$lenunit,$mtweight,$ftweight,$qty,$qtyunit){
// 	$perpcwght=calpcwght($length,$lenunit,$mtweight,$ftweight);
// 	// var_Dump($perpcwght);
// 	// var_Dump($qtyunit);
// 	// var_Dump($qty);
// 	if($qtyunit=='1'){ // weight
// 		$weightton = (float)$qty;
// 		 $pcs = (float)$qty/(float)$perpcwght;

// 	}else if($qtyunit=='2'){ //pcs
// 		$pcs = (float)$qty;
// 		$weightton=(float)$qty*(float)$perpcwght;
// 	}
// 	return array(round($pcs,2),round($weightton,2));
// }
function wtpcconvert($length,$lenunit,$mtweight,$ftweight,$qty,$qtyunit,$bundleweight){
	$perpcwght=calpcwght($length,$lenunit,$mtweight,$ftweight);
		//var_Dump($perpcwght);
		if($qtyunit=='1'){ // weight
			$weightton = (float)$qty;
			if(!empty($bundleweight) && $bundleweight != 0 && $bundleweight != '0'){
				$bundleweightintons = ((float)$bundleweight/1000);
				$pcs=($weightton/$bundleweightintons);//number of bundels
			}else{
				$pcs = (float)$qty/(float)$perpcwght;
			}
		}else if($qtyunit=='2'){ //pcs
			$pcs = (float)$qty;
			if(empty($bundleweight) || is_null($bundleweight) || $bundleweight == 0 || $bundleweight == '0'){
				$weightton = (float)$qty*(float)$perpcwght;
				//$pcs=($weightton/$bundleweightintons);//number of bundels
			}else{
				$weightton = $pcs*((float)$bundleweight/1000);
			}
			//$weightton=(float)$qty*(float)$perpcwght;
		}else if($qtyunit=='4'){ // bundels
			$pcs=(float)$qty;
			$weightton=((float)$bundleweight*$pcs)/1000;
		}else if($qtyunit=='5'){ // feet
			$weightton=((float)$ftweight*(float)$qty)/1000;
			if(!empty($bundleweight) && $bundleweight != 0 && $bundleweight != '0'){
				$bundleweightintons = ((float)$bundleweight/1000);
				$pcs=($weightton/$bundleweightintons);//number of bundels
			}else{
				$pcs=($weightton/$perpcwght);
			}
		}else if($qtyunit=='6'){// meter
			$weightton=((float)$mtweight*(float)$qty)/1000;
			if(!empty($bundleweight) && $bundleweight != 0 && $bundleweight != '0'){
				$bundleweightintons = ((float)$bundleweight/1000);
				$pcs=($weightton/$bundleweightintons);//number of bundels
			}else{
				$pcs=($weightton/$perpcwght);
			}
		}
		return array(round($pcs,2),round($weightton,3));
}

function tonstounits($length,$lenunit,$mtweight,$ftweight,$qty,$qtyunit,$bundleweight){
	$perpcwght=calpcwght($length,$lenunit,$mtweight,$ftweight); //in tons
		//var_Dump($perpcwght);
		if($qtyunit=='1'){ // Tons to  tons
			$value = (float)$qty;
		}else if($qtyunit=='2'){ // Tons to pcs
			$weight = (float)$qty;
			$value = $weight/(float)$perpcwght;
		}else if($qtyunit=='3'){ // Tons to Quintals
			$weight=(float)$qty;
			$value=(float)$weight*10;
		}else if($qtyunit=='4'){ // Tons to  bundels
			$weight=(float)$qty;
			$value= $weight/(float)($bundleweight/1000);
		}else if($qtyunit=='5'){ // Tons to feet
			$weight=(float)$qty;
			$value = $weight/((float)$ftweight/1000);
		}else if($qtyunit=='6'){// Tons To meter
			$weight=(float)$qty;
			$value = $weight/((float)$mtweight/1000);
		}
		return round($value,3);
}


/**
 * Stock section convert weight to pc
 * @wht current weht available in shed
 * @mtwht weight(kg) per meter
 * @ftwht weight(kg) per feet
 * @lengthtyp length per pc meaured feet or meter
 * @length actual length per pc.
 */
 
function wt2pc($mtwht,$ftwht,$lengthtype,$length,$wht){
//	echo "$mtwht-$ftwht-$lengthtype-$length-$wht";
	$wghttn='';
	if($lengthtype=='1'){ // pc in meters
		$wghttn = ($mtwht*$length)/1000;

	}else if($lengthtype=='2'){ // pc in foot
		$wghttn =  ($ftwht*$length)/1000;
	}    
 return ($wht/$wghttn);
}


function removespace($str){
	return str_replace(" ","",$str);
}

/**
 * Function to check if size with properties already exists in size table or not.
 * page add size
 */
function chkproduct($size,$prid,$stdlength,$lengthtype,$grade,$brand){
	$chksql = "select * from sizes where size ='$size' and prid='$prid' and stdlength='$stdlength' and lengthtype='lengthtype' and grade='$grade' and brand='$brand' ";
	echo "$chksql<br>";
	$chkqq = mysqli_query($GLOBALS['conn1'],$chksql) or die(mysqli_error($GLOBALS['conn1']));
	$chkcnt = mysqli_num_rows($chkqq);
	if(empty($chkcnt)){
		return 0;
	}else{
		return $chkcnt;
	}
}

function getorderunits($qtytype){
	switch($qtytype){
		case 1:
			return ' Tons.';
		break;
		case 2:
			return ' Pcs.';
		break;
		case 3:
			return ' Qunital';
		break;
		case 4:
			return ' Bundels';
		break;
		case 5:
			return ' Feet';
		break;
		case 6:
			return ' Mtr.';
		break;
	}
}
?>