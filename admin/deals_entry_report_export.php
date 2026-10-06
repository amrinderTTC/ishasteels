<?php
include "../conn.php";

// Excel file name for download 

// $fileName = "deals-entry-report-" . date('Ymd') . ".xlsx";
$fileName = "deals-entry-report-" . date('Ymd') . ".csv";
 
// Headers for download 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
header("Content-Type: application/vnd.ms-excel");

function filterData(&$str){ 
	$str = preg_replace("/\t/", "\\t", $str); 
	$str = preg_replace("/\r?\n/", "\\n", $str); 
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}

$frm=($cpage * $noprd) - $noprd;
$whr=1;

if(isset($_GET['user_id']) && !empty($_GET['user_id']) && is_numeric($_GET['user_id'])){
	$whr .= " and so.createdby = '$_GET[user_id]'";
}
if(isset($_GET['dt_frm']) && !empty($_GET['dt_frm'])){
	$whr .= " and date(so.createdon) >= '".$_GET['dt_frm']."'  ";
}
if(isset($_GET['dt_to']) && !empty($_GET['dt_to'])){
	$whr .= " and date(so.createdon) <= '".$_GET['dt_to']."'  ";
}

$query = "SELECT so.slid, so.cid, so.`status`, so.orderqty AS maxorderqty, so.createdby, c.`name` AS partyname, admin.name AS user_name, so.createdon FROM dealorder as so
	INNER JOIN customers as c ON c.cust_id = so.cid
	INNER JOIN admin ON admin.admin_id = so.createdby
	where $whr
	order by so.slid desc
";
// print_r($query);
//echo $query;

$flag = false;
$qqq = mysqli_query($conn,$query) or die(mysqli_error($conn));
$cnt=1;
echo "SR NO., ORDER NO, PARTY, USER, COMPLETED DT, ENTRY DATE \n";
while($rw2 = mysqli_fetch_assoc($qqq)){
	// echo "<pre>"; print_r($rw2);echo "</pre>";
	if($rw2['smarkedcompleted']){
		$complete_dt=date('d-m-Y',strtotime($rw2['smarkedcompleted']));
	}else{
		$complete_dt="-";
	}

	$data['sr_no']		= $cnt;
	$data['slid']		= $rw2['slid'];
	$data['partyname']	= $rw2['partyname'];
	$data['user_name']	= $rw2['user_name'];
	$data['complete_dt']= $complete_dt;
	$data['createdon']	= $rw2['createdon'];

	array_walk($data, 'filterData'); 
	// echo implode("\t", array_values($rw2)) . "\n";
	echo implode(",", array_values($data)) . "\n";

	$cnt++;
}

/* $flag = false; 
foreach($data as $row) { 
	if(!$flag) { 
		// display column names as first row 
		echo implode("\t", array_keys($row)) . "\n"; 
		$flag = true; 
	} 
	// filter data 
	array_walk($row, 'filterData'); 
	echo implode("\t", array_values($row)) . "\n"; 
}  */
 
exit;