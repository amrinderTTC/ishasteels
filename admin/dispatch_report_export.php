<?php
include "../conn.php";

// Excel file name for download 

// $fileName = "deals-entry-report-" . date('Ymd') . ".xlsx";
$fileName = "Dispatch-Report-" . date('Ymd') . ".csv";
 
// Headers for download 
header("Content-Disposition: attachment; filename=\"$fileName\""); 
header("Content-Type: application/vnd.ms-excel");

function filterData(&$str){ 
	$str = preg_replace("/\t/", "\\t", $str); 
	$str = preg_replace("/\r?\n/", "\\n", $str); 
	if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
}

$whr=1;
$whr1=1;
if(isset($_GET['customer']) && !empty($_GET['customer']) && is_numeric($_GET['customer'])){
	$whr1 .= " and customers.cust_id='$_GET[customer]'";
	$whr .= " and dispatch.custid='$_GET[customer]'  ";
}
if(isset($_GET['gid']) && !empty($_GET['gid']) && is_numeric($_GET['gid'])){
	$whr .= " and weight.vhid='$_GET[gid]'";
}
if(isset($_GET['date']) && !empty($_GET['date'])){
	$whr .= " and date(dispatch.createdon)='".$_GET['date']."'  ";
}
if(isset($_GET['orderby']) && !empty($_GET['orderby']) && is_numeric($_GET['orderby'])){
	$oby = $_GET['orderby'];
	if($oby=='1'){// customer
		$orderby = ' order by dispatch.custid desc';
	}else if($oby=='2'){//vehicle no
		$orderby =' Order by dispatch.vehicleid desc ';
	}
}

//$query ="select DISTINCT cust_id,name from customers,saleorder where $whr and customers.cust_id=saleorder.cid and ((DATE(saleorder.`smarkedcompleted`)= Date(NOW()) and saleorder.`status`=1) or saleorder.`status`=0) order by cust_id asc";
$query ="SELECT DISTINCT cust_id, name from customers,saleorder where $whr1 and customers.cust_id=saleorder.cid order by name asc";

$qqq = mysqli_query($conn,$query);
$asd=0;
$asr = 0;
$n = 1;
// print_r($query);
//echo $query;

$flag = false;
$qqq = mysqli_query($conn,$query) or die(mysqli_error($conn));
$cnt=1;
echo "SR NO., Customer Name, Vehicle No, Dated, Dispatch User, Final User, Qty Dispatched (Tons) \n";
while($qrw = mysqli_fetch_assoc($qqq)){
	$query2 = "SELECT
		dispatch.dispatchid,
		customers.`name`,
		dispatch.vehicleid as vhid,
		dispatch.weight AS whtsum,
		dispatch.custid,
		dispatch.createdon,
		gate.vehicleno, admin.name AS user_name, adminf.name AS user_name_f
		FROM dispatch
		INNER JOIN admin ON admin.admin_id = dispatch.createdby
        LEFT JOIN admin as adminf ON adminf.admin_id = dispatch.finalby
		INNER JOIN customers ON customers.cust_id = dispatch.custid
		INNER JOIN gate ON gate.gid = dispatch.vehicleid 
		where $whr and date(gate.chkouton) >= (CURDATE()-INTERVAL 1 DAY)  and dispatch.custid = $qrw[cust_id]
	";

	if(!empty($_GET['orderby'])){
		$query2 .= $orderby;
	}else{
		$query2 .=" order by dispatch.custid asc";
	}
	// echo $query2.'<br><br>';
	$qq2 = mysqli_query($conn,$query2);

	while($rw2 = mysqli_fetch_assoc($qq2)){
		// var_dump($rw2);
		$asd = $rw2['whtsum'];
		
		$data['sr_no']			= $n;
		$data['partyname']		= strtoupper($rw2['name']);
		$data['vehicleno']		= strtoupper($rw2['vehicleno']);
		$data['createdon'] 		= date('d-m-Y',strtotime($rw2['createdon']));
		$data['user_name']		= strtoupper($rw2['user_name']);
		$data['user_name_f']    = strtoupper($rw2['user_name_f']);
		$data['dispatched_qty']	= number_format($asd,3);

		// $asr +=$asd;

		array_walk($data, 'filterData'); 
		// echo implode("\t", array_values($rw2)) . "\n";
		echo implode(",", array_values($data)) . "\n";
		
		$n++; 
	}
}

exit;