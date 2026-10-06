<?php
include_once "../conn.php";

$perm=check_permission("A");
if(@$_GET['per_page']){
	$noprd=@$_GET['per_page'];
}else{
	$noprd=50;
}

$cpage=$_REQUEST['page'];
if ($cpage == 0){
	$cpage=1;
}

$frm=($cpage * $noprd) - $noprd;
$whr = 1;
$prwhr =1;
// if(isset($_GET['customer']) && is_numeric($_GET['customer']) && !empty($_GET['customer'])){
// $custid = mysqli_real_escape_string($conn,$_GET['customer']);
// $gprodid = mysqli_real_escape_string($conn,$_GET['product']);
// $gsizeid = mysqli_real_escape_string($conn,$_GET['size']);
// $whr .= " and customers.cust_id='$custid' ";
// }

// if(isset($_GET['mark1']) && $_GET['mark1']=='C'){
// $sizecom=mysqli_real_escape_string($conn, $_GET['sizecom']);
// $custid = mysqli_real_escape_string($conn, $_GET['custid']);
// $upd="UPDATE saleordersizes set completed='1' , smarkedcompleted='$createdon' where sosize='$sizecom'";
// mysqli_query($conn,$upd);
// echo '<script>window.location.href="main.php?paction=client_report&msg=Size Marked Completed Successfully.#'.$custid.'"</script>';
// }

// if(isset($_GET['mark1']) && $_GET['mark1']=='OC'){
// $orderid =  mysqli_real_escape_string($conn, $_GET['orderid']);
// $custid = mysqli_real_escape_string($conn, $_GET['custid']);
// //first update all items of order.
// //select completed,smarkedcompleted from saleordersizes where sosaleid=10
// $uporderitem = "update saleordersizes set completed='1', smarkedcompleted='$createdon' where sosaleid ='$orderid'";
// //echo $uporderitem;
// mysqli_query($conn,$uporderitem);
// // second update order itself.
// //select status,smarkedcompleted from saleorder where slid=10 
// $uporder = "update saleorder set status='1',smarkedcompleted='$createdon' where slid='$orderid'";
// mysqli_query($conn,$uporder);
// echo '<script>window.location.href="main.php?paction=client_report&msg=Order Marked Completed Successfully.#'.$custid.'"</script>';
// }

// if(isset($_GET['mark']) && $_GET['mark']=='DD'){
// $dealid=mysqli_real_escape_string($conn, $_GET['dealid']);
// $custid = mysqli_real_escape_string($conn, $_GET['custid']);
// $updeal="update dealorder set status='1',markedcompleted='$createdon' where slid='$dealid'";
// mysqli_query($conn,$updeal);
// echo '<script>window.location.href="?paction=client_report&msg=Deal Item  Marked Completed Successfully.#'.$custid.'"</script>';
// }
?>

<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="assets/css/all.css">
<link href="assets/select2/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="assets/css/style.css">
<link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
<script type="text/javascript" src="assets/js/jquery-3.4.1.min.js"></script>
<script type="text/javascript" src="assets/js/bootstrap.js"></script>
<script type="text/javascript" src="assets/js/script.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

<link href="assets/css/print.css" type="text/css" rel="stylesheet">
<style>
hr {
display: none;
}
.d-inline-block{
min-width: 100%;  
}
</style>
<div class="main-content-inner">
<?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
<?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
<?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
<?php
if($perm){
?>
<div class="article">
	<div class="article-heading flex-heading no-print d-none">
		<h5 class="text-center">Customerwise Wise All Pending Orders & Deals</h5>
		<div class="">
			<a href="main.php" class="btn btn-basic btn-sm">Dashboard</a>
			<button class="btn btn-basic btn-sm" onclick="window.print()"><i
					class="bi bi-printer"></i>&nbsp;Print</button>
		</div>
	</div>
<div class="article-content container-max">
<div class="filter mb-4 no-print">
<form action="" name="filter" method="get">
<input type="hidden" name="paction" value="client_report">
<div class="row">
<div class="col-lg-3 col-sm-6">
<div class="form-group mb-sm-0">
<label for="customer">Select Customer</label>
<select name="customer" id="customer" class="form-control select2me">
<option value="">Select From List</option>
<?php
$csql="select cust_id,name from customers order by name asc ";
$cqq = mysqli_query($conn,$csql);
while($crw=mysqli_fetch_assoc($cqq)){
echo '<option value="'.$crw['cust_id'].'"'.($crw['cust_id']==$custid?'selected':'').'>'.strtoupper($crw['name']).'</option>';
}
?>
</select>
</div>
</div>
<div class="col-lg-3 col-sm-4">
<div class="form-group">
<label>Select Product</label>
<select name="product" class="form-control select2me product">
<option value="">Select From List</option>
<?php
$csql="select prid,productname from products order by productname asc";
$cqq = mysqli_query($conn,$csql);
while($crw=mysqli_fetch_assoc($cqq)){
echo '<option value="'.$crw['prid'].'"'.($crw['prid']==$_GET['product']?'selected':'').'>'.strtoupper($crw['productname']).'</option>';
}
?>
</select>
</div>
</div>
<div class="col-lg-3 col-sm-4">
<div class="form-group">
<label>Select Size</label>
<select name="size" class="form-control select2me size2">
<option value="">Select From List</option>
<?php
// $csql="select sid,size from products order by name asc";
// $cqq = mysqli_query($conn,$csql);
// while($crw=mysqli_fetch_assoc($cqq)){
//     echo '<option value="'.$crw['cust_id'].'"'.($crw['cust_id']==$custid?'selected':'').'>'.strtoupper($crw['name']).'</option>';
// }
?>
</select>
</div>
</div>
<div class="col-lg-3 col-sm-4">
<div
class="input-group h-100 justify-content-end justify-content-lg-start align-items-center">
<input type="submit" name="filter" class="btn btn-basic" value="Apply Filter">
<button class="btn btn-danger btn-sm ml-2" onclick="window.print()"><i
					class="bi bi-printer"></i>&nbsp;Print</button>
</div>
</div>
</div>
</form>
</div>

<div class="table-responsive mt-3 mt-lg-0">
<table class="table table-bordered report">
<thead>
<tr>
<th style="width: 12rem">Customer</th>
<th>
<span class="size">Order No. / Product / Size</span><br />
<span class="pending">Ordered / Pending</span><br />
<span class="pending">Rate / Grade</span>
</th>
</tr>
</thead>
<tbody>
<?php 
$ordersql1 = "
                                SELECT cust_id, name
                                FROM (
                                    SELECT DISTINCT customers.cust_id, customers.name
                                    FROM customers
                                    INNER JOIN saleorder ON customers.cust_id = saleorder.cid
                                    WHERE $whr
                                      AND (saleorder.status = 0 
                                           OR (DATE(saleorder.smarkedcompleted) = CURDATE() AND saleorder.status = 1))
                                ) AS t
                                ORDER BY name ASC
                                ";
// "SELECT DISTINCT cust_id,name from customers,saleorder where $whr and customers.cust_id=saleorder.cid and  (saleorder.`status`=0 or (DATE(saleorder.`smarkedcompleted`)= Date(NOW()) and saleorder.`status`=1)) order by name ASC";

$ordersql2 = "
                                SELECT cust_id, name
                                FROM (
                                    SELECT DISTINCT customers.cust_id, customers.name
                                    FROM customers
                                    INNER JOIN dealorder ON customers.cust_id = dealorder.cid
                                    WHERE $whr
                                      AND (dealorder.status = 0 
                                           OR (DATE(dealorder.markedcompleted) = CURDATE() AND dealorder.status = 1))
                                ) AS t
                                ORDER BY name ASC
                                ";

// "SELECT DISTINCT cust_id,name from customers,dealorder where $whr and  customers.cust_id=dealorder.cid  and (dealorder.`status`=0 or (DATE(dealorder.`markedcompleted`)= Date(NOW()) and dealorder.`status`=1)) order by name ASC";
$orderqq1 = mysqli_query($conn,$ordersql1);
$orderqq2 = mysqli_query($conn,$ordersql2);
$a = [];
$b = [];

while($orw1 = mysqli_fetch_assoc($orderqq1)){
if(!in_array($orw1['cust_id'],$a)){
array_push($a, $orw1);
}
}

while($orw2 = mysqli_fetch_assoc($orderqq2)){
if(!in_array($orw2['cust_id'],$a)){
if(!in_array($orw2, $a)){
array_push($a, $orw2);
}

}
}
$key = array_column($a,'name');
array_multisort($key,SORT_ASC,$a);
$total_customers = count($a);
foreach($a as $orw){
$prwhr=1;

$prwhr1=1;

if(isset($_GET['product']) && is_numeric($_GET['product']) && !empty($_GET['product'])){
$productid = mysqli_real_escape_string($conn,$_GET['product']);
$prwhr .="  and products.prid=$productid";
$prwhr1 .="  and products.prid=$productid";
}
if(isset($_GET['size']) && is_numeric($_GET['size']) && !empty($_GET['size'])){
$sizeid = mysqli_real_escape_string($conn,$_GET['size']);
$prwhr1 .=" and sizes.sid=$sizeid ";
}
if(isset($_GET['customer']) && is_numeric($_GET['customer']) && !empty($_GET['customer'])){
$customerid = mysqli_real_escape_string($conn,$_GET['customer']);
$prwhr .=" and dealorder.cid=$customerid";
$prwhr1 .=" and saleorder.cid=$customerid";

}
$sizesql1 = "SELECT saleordersizes.sosize,
saleordersizes.weightintons, 
saleordersizes.dispatched, 
saleordersizes.sosaleid, 
saleordersizes.todispatchwegith,
saleordersizes.todispachpcs, 
saleordersizes.completed,
saleordersizes.soprice,
saleorder.cid,
brands.brandname, 
grade.grade, 
sizes.size,
saleorder.cid,
products.productname,
saleordersizes.qty,
saleordersizes.qtytype,sizes.mtweight,
sizes.ftweight,
sizes.stdlength,
sizes.lengthtype,
sizes.bundleweight,
saleordersizes.smarkedcompleted,
saleordersizes.itemremarks
FROM saleordersizes 
INNER JOIN saleorder ON saleorder.slid = saleordersizes.sosaleid 
INNER JOIN products ON products.prid = saleordersizes.soprid 
INNER JOIN brands ON brands.brid = saleordersizes.sobrandid
INNER JOIN grade ON grade.gid = saleordersizes.sogradeid 
INNER JOIN sizes ON sizes.sid = saleordersizes.sosizeid 
where $prwhr1 and saleorder.cid='$orw[cust_id]' and ((DATE(saleordersizes.`smarkedcompleted`) = date(NOW())  and saleordersizes.`completed`=1) or saleordersizes.`completed`=0) order by saleordersizes.sosize,products.productname asc";

$sizeqq1 = mysqli_query($conn,$sizesql1); 
$sizesqlcnt = mysqli_num_rows($sizeqq1);
//var_Dump('sizesqlcnt:'.$sizesqlcnt);
$dealsql1 ="SELECT
dealitems.ditemid,
dealitems.dealid,
dealitems.itemid,
dealitems.gradeid,
dealitems.price,
dealitems.qty,
dealitems.qtyunit,
dealitems.qtydispatched,
products.productname,
dealorder.cid,
dealorder.slid,
dealorder.`status`,
dealitems.remarks,
grade.grade
FROM
dealitems
INNER JOIN products ON products.prid = dealitems.itemid
INNER JOIN dealorder ON dealorder.slid = dealitems.dealid
INNER JOIN grade ON grade.gid = dealitems.gradeid
where $prwhr and dealorder.cid='$orw[cust_id]'  order by dealitems.ditemid,products.productname asc";
$dealqq1 = mysqli_query($conn,$dealsql1) or die(mysqli_error($conn)); 
$dealsqlcnt = mysqli_num_rows($dealqq1);
if($sizesqlcnt>0 || $dealsqlcnt>0){
?>
<tr id="<?php echo $orw['cust_id']; ?>" class="tabledata">
<td><?php
$cnamesql = "select cust_id,name from customers where cust_id=$orw[cust_id]";
$cnameqq = mysqli_query($conn,$cnamesql);
$cnamerw = mysqli_fetch_assoc($cnameqq);
echo strtoupper( $cnamerw['name']); //strtoupper($orw); ?></td>
<td class="px-0">
<?php 
$dealidsql = "SELECT
dealorder.slid AS dealid,
dealorder.orderqty,
dealorder.`status`,
dealorder.orderremarks,
dispatchedqty as qtydispatched
from dealorder where cid=$orw[cust_id] and ((DATE(dealorder.`markedcompleted`)= DATE(NOW())  and dealorder.`status`=1) or dealorder.`status`=0)";
//echo $dealidsql;

$dealidqq = mysqli_query($conn,$dealidsql) or die(mysqli_error($conn));
$dealcnt = mysqli_num_rows($dealidqq);

if($dealcnt >0){

while($dealidrw=mysqli_fetch_assoc($dealidqq)){

$dealsql ="SELECT
dealitems.ditemid,
dealitems.dealid,
dealitems.itemid,
dealitems.gradeid,
dealitems.price,
dealitems.qty,
dealitems.qtyunit,
dealitems.qtydispatched,
products.productname,
dealorder.cid,
dealorder.slid,
dealorder.`status`,
dealitems.remarks,
grade.grade
FROM
dealitems
INNER JOIN products ON products.prid = dealitems.itemid
INNER JOIN dealorder ON dealorder.slid = dealitems.dealid
INNER JOIN grade ON grade.gid = dealitems.gradeid
where $prwhr and dealorder.cid=$orw[cust_id] and dealid='$dealidrw[dealid]' order by dealitems.ditemid asc";
//$orw[cust_id]
$dealqq = mysqli_query($conn,$dealsql);
$dealcnt = mysqli_num_rows($dealqq);
if($dealcnt>0){
$dealsum="select sum(dispatchqty) as qtydispatched from dealdispatch where dealid='$dealidrw[dealid]'";
$dealsumqq=mysqli_query($conn,$dealsum);
$dealsum=mysqli_fetch_assoc($dealsumqq);
$dealpending=$dealidrw['orderqty']-$dealsum['qtydispatched'];
?>
<div class="container-fluid px-0
<?php
if($dealidrw['status']=='1'){
echo ' bg-lightgreen ';
}else{
echo ' bg-light ';
}
?>">
<ul class="list-unstyled d-inline-block orderrow">
<?php if(!empty($dealidrw['orderremarks'])){ ?>
<div class="d-block w-100 m-1">
<strong>Remarks: </strong><?php echo $dealidrw['orderremarks'];?>
</div>
<?php } ?>
<li class="d-md-flex w-100 px-3 py-1" style="width: 100%">
<div class="d-flex pr-sm-3">
<h6 class="bold mb-0">Deal Qty : </h6>
<p class="mb-md-0"><?php echo $dealidrw['orderqty'];?> Tons</p>
</div>
<div class="d-flex pr-sm-3">
<h6 class="bold mb-0">Dispatched Qty : </h6>
<p class="mb-md-0">
<?php echo (!empty($dealsum['qtydispatched'])?$dealsum['qtydispatched']:'0');?>
Tons</p>
</div>
<div class="d-flex">
<h6 class="bold mb-0">Pending Qty : </h6>
<p class="mb-md-0">
<?php echo $dealpending; #echo ($dealidrw['orderqty']-$dealidrw['qtydispatched']);?>
Tons</p>
</div>
</li>
<?php while($szrw = mysqli_fetch_assoc($dealqq)){
$pending1 = (!empty($szrw['qtydispatched'])?$szrw['qtydispatched']:'0');
?>
<li>
<div class="reportThumb my-1 mx-2">
<?php if(!empty($szrw['remarks'])) { ?><span><strong>Item Remarks : </strong> &nbsp <?php echo $szrw['remarks']; ?> </span><?php } ?>

<span><b><?php echo $szrw['dealid']; ?></b> /
<?php echo ucwords($szrw['productname']); ?></span>
<span class="mb-0"><i
class="fa fa-rupee-sign"></i><?php echo $szrw['price']; ?> /
<?php echo ucwords($szrw['grade']); ?></span>
</div>
</li>
<?php } ?>
<?php
$dispdetails = "select dealdispatchid,dealid,vehicleno,dispatchqty,dealdispatch.createdon from dealdispatch where dealid=$dealidrw[dealid]";
$dispdetailsqq = mysqli_query($conn,$dispdetails);
$ddcnt = mysqli_num_Rows($dispdetailsqq);

if($ddcnt>0){
?>
<div class="d-block w-100">
<table class="table table-bordered">
<tr>
<th>S.No.</th>
<th>V.No.</th>
<th>Qty.</th>
<th>Dated</th>
\															</tr>
<?php  $y=1;
while($ddrw = mysqli_fetch_assoc($dispdetailsqq)){ ?>
<tr>
<td><?php echo $y; ?></td>
<td><?php echo strtoupper($ddrw['vehicleno']); ?></td>
<td><?php echo $ddrw['dispatchqty']; ?></td>
<td><?php
if ($ddrw['createdon'] !='0000-00-00 00:00:00' || empty($ddrw['createdon'])) {
echo date('d-m-Y',strtotime($ddrw['createdon']));
} ?></td>


</tr>
<?php $y++; } ?>
</table>

</div>
<?php } ?>
</ul>
</div>
<?php }
} 
} ?>
<hr>
<?php
$saleordersql = "select * from saleorder where cid='$orw[cust_id]' and ((DATE(saleorder.`smarkedcompleted`)= DATE(NOW()) and saleorder.`status`=1) or saleorder.`status`=0)";
//$orw[cust_id]
$saleorderqq = mysqli_query($conn,$saleordersql);
while($saleorderrw = mysqli_fetch_assoc($saleorderqq)){
$dispsql = "SELECT DISTINCT dispatch_item.dispid,dispatch.weight FROM dispatch_item
INNER JOIN dispatch ON dispatch.dispatchid = dispatch_item.dispid
where date(dispatch_item.createdon)=CURDATE() and weight is null and slid=$saleorderrw[slid] order by dispatch_item.dispid desc";
$dispqq = mysqli_query($conn,$dispsql);
?>
<div class="container-fluid px-2 
<?php
if($saleorderrw['status']=='1'){
echo ' bg-lightgreen ';
}else if(mysqli_num_rows($dispqq)>0 && $saleorderrw['status']!='1'){
echo 'thumb-yellow ';
}else{
echo ' bg-light ';
}
?>
">
<ul class="list-unstyled d-inline-block orderrow">
<?php if(!empty($saleorderrw['remarks'])){
echo '<span  class="d-block w-100 m-1"><strong> Remarks:</strong>'.$saleorderrw['remarks'].'</span>';
} ?>

<?php echo (empty($saleorderrw['smarkedcompleted'])?'':'<strong class="d-block w-100 text-center lead">COMPLETED -'.date('d-m-Y',strtotime($saleorderrw['smarkedcompleted'])).'</strong>'); ?>
<?php 
$prwhr=1;
if(isset($_GET['product']) && is_numeric($_GET['product']) && !empty($_GET['product'])){
$productid = mysqli_real_escape_string($conn,$_GET['product']);
$prwhr .="  and products.prid=$productid";
}
if(isset($_GET['size']) && is_numeric($_GET['size']) && !empty($_GET['size'])){
$sizeid = mysqli_real_escape_string($conn,$_GET['size']);
$prwhr .=" and sizes.sid=$sizeid ";
}

$sizesql = "SELECT saleordersizes.sosize,
saleordersizes.weightintons, 
saleordersizes.dispatched, 
saleordersizes.sosaleid, 
saleordersizes.todispatchwegith,
saleordersizes.todispachpcs, 
saleordersizes.completed,
saleordersizes.soprice,
saleorder.cid,
brands.brandname, 
grade.grade, 
sizes.size,
saleorder.cid,
products.productname,
saleordersizes.qty,
saleordersizes.qtytype,sizes.mtweight,
sizes.ftweight,
sizes.stdlength,
sizes.lengthtype,
sizes.bundleweight,
saleordersizes.smarkedcompleted,
saleordersizes.itemremarks
FROM saleordersizes 
INNER JOIN saleorder ON saleorder.slid = saleordersizes.sosaleid 
INNER JOIN products ON products.prid = saleordersizes.soprid 
INNER JOIN brands ON brands.brid = saleordersizes.sobrandid
INNER JOIN grade ON grade.gid = saleordersizes.sogradeid 
INNER JOIN sizes ON sizes.sid = saleordersizes.sosizeid 
where $prwhr and saleorder.cid='$orw[cust_id]' and ((DATE(saleordersizes.`smarkedcompleted`) = date(NOW())  and saleordersizes.`completed`=1) or saleordersizes.`completed`=0) and saleorder.slid ='$saleorderrw[slid]'  order by saleordersizes.sosize,products.productname asc";
//$orw[cust_id]
//echo $sizesql;
$sizeqq = mysqli_query($conn,$sizesql); ?>
<?php while($szrw = mysqli_fetch_assoc($sizeqq)){

$vhnosql = "SELECT DISTINCT dispatch_item.vid, gate.vehicleno FROM dispatch_item
INNER JOIN gate ON gate.gid = dispatch_item.vid where slid=$szrw[sosaleid] and dispatch_item.sosize=$szrw[sosize]";

$vhnoqq=mysqli_query($conn,$vhnosql);
$vhnocnt=mysqli_num_rows($vhnoqq);

$colorsql="select sosize from dispatch_item where sosize='$szrw[sosize]' and date(dispatchedon)=CURDATE()";
$colorqq = mysqli_query($conn,$colorsql);
$crow = mysqli_num_rows($colorqq);
if(empty($crow)){
$crow=0;
}
?>

<li>
<div class="reportThumb my-2 mx-1 <?php 
if($szrw['completed']==1){
echo ' bg-lightgreen ';
}else if($crow==1){
echo ' bg-lightgreen ';
} ?>">
<?php if(!empty($szrw['itemremarks'])){
echo '<span  class="d-block w-100 m-1"><strong> Remarks:</strong>'.$szrw['itemremarks'].'</span>';
} ?>
<?php echo ($szrw['completed']==1?'<strong class="d-block w-100 text-center">COMPLETED-'.date('d-m-Y',strtotime($szrw['smarkedcompleted'])).'</strong>':'');
if($vhnocnt>0){
echo '<span><strong>Vehicle No. </strong>';
while($vhnorw=mysqli_fetch_assoc($vhnoqq)){
echo $vhnorw['vehicleno'].'-';
}
echo '</span>';
}
?>
<?php if(!empty($szrw['remarks'])) { ?><span><strong>Item Remarks : </strong> &nbsp <?php echo $szrw['remarks']; ?> </span><?php } ?>

<span><b><?php echo $szrw['sosaleid']; ?></b>/<?php echo ucfirst($szrw['productname']); ?>
/ <?php echo $szrw['size']; ?></span>
<span class="mb-0">
<?php echo $szrw['qty']; echo getorderunits($szrw['qtytype']);?> /
<?php echo tonstounits($szrw['stdlength'],$szrw['lengthtype'],$szrw['mtweight'],$szrw['ftweight'],($szrw['weightintons']-$szrw['dispatched']),$szrw['qtytype'],$szrw['bundleweight']); echo getorderunits($szrw['qtytype']);?>
</span>
<span class="mb-0"> <i
class="fa fa-rupee-sign"></i><?php echo $szrw['soprice']; ?> /
<?php echo $szrw['grade']; ?></span>
</div>
</li>
<?php } ?>
</ul>
</div>
<?php } ?>
</td>
</tr>
<?php 
}// check if size or product exists in saleorder or  
}// customer name while loop ends here 
?>
</tbody>
</table>
</div>
	<div class="pagging">
        <div class="right">
            <?php
            // Records per page
            $per_page = isset($_GET['per_page']) ? (int)$_GET['per_page'] : $noprd;
            if ($per_page <= 0) $per_page = $noprd;
    
            // Current page
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            if ($page <= 0) $page = 1;
    
            // Offset
            $start = ($page - 1) * $per_page;
    
            // Keep existing GET parameters
            $customer_param = isset($_GET['customer']) && !empty($_GET['customer']) ? '&customer=' . $_GET['customer'] : '';
            $product_param  = isset($_GET['product']) && !empty($_GET['product']) ? '&product=' . $_GET['product'] : '';
            $size_param     = isset($_GET['size']) && !empty($_GET['size']) ? '&size=' . $_GET['size'] : '';
            $paction        = isset($_GET['paction']) ? $_GET['paction'] : '';
    
            // Total records (make sure this variable already contains total count)
            $total_rows = $total_customers;
            $total_pages = ceil($total_rows / $per_page);
    
            // ===== Pagination Links =====
    
            // First
            if ($page > 1) {
                echo '<a href="?client_report_print.php&page=1'.$customer_param.$product_param.$size_param.'&per_page='.$per_page.'">First</a> ';
            }
    
            // Back
            if ($page > 1) {
                $prev = $page - 1;
                echo '<a href="?client_report_print.php&page='.$prev.$customer_param.$product_param.$size_param.'&per_page='.$per_page.'">Back</a> ';
            }
    
            // Page Numbers
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $page) {
                    echo '<span><strong>'.$i.'</strong></span> ';
                } else {
                    echo '<a href="?client_report_print.php&page='.$i.$customer_param.$product_param.$size_param.'&per_page='.$per_page.'">'.$i.'</a> ';
                }
            }
    
            // Next
            if ($page < $total_pages) {
                $next = $page + 1;
                echo '<a href="?client_report_print.php&page='.$next.$customer_param.$product_param.$size_param.'&per_page='.$per_page.'">Next</a> ';
            }
    
            // Last
            if ($page < $total_pages) {
                echo '<a href="?client_report_print.php&page='.$total_pages.$customer_param.$product_param.$size_param.'&per_page='.$per_page.'">Last</a>';
            }
            ?>
        </div>
    </div>
</div>
</div>
<?php
}
?>
</div>