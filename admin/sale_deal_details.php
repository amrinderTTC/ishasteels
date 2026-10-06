<script></script>
<?php
$perm=check_permission("A","OF");
$slid =  mysqli_real_escape_string($conn, $_GET['slid']);
if(isset($_GET['slid']) ){
	if(empty($_GET['slid']) && !is_numeric($_GET['slid'])){
		echo '<script>window.location.href="main.php"</script>';
	}
}

if(isset($_GET['mark']) && $_GET['mark']=='S'){
    $usizesql = "update dealorder set status='1' where slid='$slid'";
    $usizeqq = mysqli_query($conn,$usizesql);
    if(mysqli_affected_rows($conn)>0){
        echo '<script>window.location.href="main.php?paction=sale_deals_view&msg=Sale deal Marked Completed."</script>';
    }
}

// if(isset($_GET['mark']) && $_GET['mark']=='C'){
// 	$sizecom =  mysqli_real_escape_string($conn, $_GET['sizecom']);
// 	$upd = "UPDATE dealitems set completed='1' where ditemid='$sizecom'";
// 	mysqli_query($conn,$upd);
// 	echo '<script>window.location.href="main.php?paction=sale_deals_view&slid='.$slid.'&msg=Size Marked Completed Successfully."</script>';
// }

?>
<script>
	function cnf(id, sizecom){
		let confirm = window.confirm("Do you Wand to Mark. This Size Complete?");
		if(confirm==true){
			
			window.location.href="main.php?paction=sale_deal_details&slid="+id+"&sizecom="+sizecom+"&mark=C";
		}else{
		   
		}
	}

	function scnf(sid){
		let confirm = window.confirm("Do you Wand to Mark. This Size Complete?");
		if(confirm==true){
			// alert(confirm)
			window.location.href="main.php?paction=sale_deal_details&slid="+sid+"&mark=S";
		}else{
		   // history.go(-1);
		}
	}
	
	</script>
</script>
<?php

// $cxsql = "SELECT dealorder.cid, contacts.cid AS contid, contacts.cperson, contacts.countrycode, contacts.contact, contacts.designation, dealorder.slid, contacts.email,
// dealorder.createdon, customers.`name` as cust, countries.`name` as countryname, states.`name` as statename, customers.pincode, customers.address,customers.city,
// ccode.phonecode,
// (select sum(qty) from dealitems  where dealid=dealorder.slid group by dealid) as totalqty,
// (select sum(qtydispatched) from dealitems  where dealid=dealorder.slid group by dealid) as dispatchedqty
// FROM dealorder
// INNER JOIN contacts ON contacts.cid = dealorder.cperson INNER JOIN customers ON customers.cust_id = dealorder.cid 
// INNER JOIN countries ON countries.id = customers.country  
// INNER JOIN states ON states.id = customers.state 
// INNER JOIN countries as ccode ON ccode.id = customers.country 
// where dealorder.slid=$slid";

$cxsql = "SELECT dealorder.cid,dealorder.orderremarks, contacts.cid AS contid, contacts.cperson, contacts.countrycode, contacts.contact, contacts.designation, dealorder.slid, contacts.email,
dealorder.createdon, customers.`name` as cust, countries.`name` as countryname, states.`name` as statename, customers.pincode, customers.address,customers.city,
ccode.phonecode,dealorder.orderqty  as totalqty,dealorder.`status`,dealorder.markedcompleted,
(select sum(qtydispatched) from dealitems  where dealid=dealorder.slid group by dealid) as dispatchedqty
FROM dealorder
INNER JOIN contacts ON contacts.cid = dealorder.cperson INNER JOIN customers ON customers.cust_id = dealorder.cid 
INNER JOIN countries ON countries.id = customers.country  
INNER JOIN states ON states.id = customers.state 
INNER JOIN countries as ccode ON ccode.id = customers.country 
where dealorder.slid=$slid";

$cxqq = mysqli_query($conn,$cxsql);
$crx = mysqli_fetch_assoc($cxqq);

$spid = "SELECT
dealitems.ditemid,
dealitems.dealid,
products.productname,
grade.grade,
dealitems.price,
dealitems.qty,
dealitems.remarks,
dealitems.qtyunit,
dealitems.qtydispatched
FROM
dealitems
INNER JOIN products ON products.prid = dealitems.itemid
INNER JOIN grade ON grade.gid = dealitems.gradeid
where dealid=$slid order by dealitems.ditemid,products.productname asc";

$spq = mysqli_query($conn,$spid);
//$sprw=mysqli_fetch_assoc($spq);


//var_dump($sprw);
if($_GET['msg']){
	$msg=$_GET['msg'];
}
if($_GET['errmsg']){
	$errmsg=$_GET['errmsg'];
}
?>

<div class="main-content-inner">
<?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
	<?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
	<?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
	<?php
	if($perm){
	?>
	<div class="page-header">
		<h1 class="page-heading h6 ebold">Sale Order Details</h1>
		<ul class="list-inline breadcrumb d-none d-md-flex">
			<li class="breadcrumb-item">Home</li>
			<li class="breadcrumb-item">Manage Orders</li>
			<li class="breadcrumb-item">Sale Order Details</li>
		</ul>
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="article">
				<div class="article-heading flex-heading">
					<h5 class="text-center">Order Details</h5>
					<?php if($crx['status']=='0'){?>
					<div class="text-right">
					     <a href="main.php?paction=print_deal&slid=<?php echo $slid; ?>" class="btn btn-danger btn-sm"><i class="bi bi-file-pdf"></i></a>
					     <?php if(check_permission("A")){ ?> 
						<a href="main.php?paction=edit_deal_order&slid=<?php echo $slid; ?>" class="btn btn-basic btn-sm"><i class="bi bi-pencil-square"></i></a>
						<!-- <a href="main.php?paction=sale_order_details&slid=<?php echo $slid; ?>&smcmp=1" class="btn btn-basic btn-sm btn-success"><i class="bi bi-check-lg"></i></a> -->

                        <a href="javascript:scnf(<?php echo $slid;?>)" class="btn btn-basic btn-sm btn-success"><i class="bi bi-check-lg"></i></a>
                        <?php } ?>
					</div>
					<?php } ?>
				</div>
				<div class="article-content container-max">
					<div class="table-responsive mt-4">
						<table class="table table-bordered">
							<tr>
								<td>Deal No.</td>
								<td><?php echo $crx['slid'];?></td>
							</tr>
							<tr>
								<td>Deal Date</td>
								<td><?php echo date('d-m-Y',strtotime($crx['createdon']));?></td>
							</tr>
							<tr>
								<td>Deal Qty.</td>
								<td> <?php echo round($crx['totalqty'],2); ?> Tons</td>
							</tr>
							<tr>
								<td>Total Dispatch</td>
								<td> <?php 
								$dealsum="select sum(dispatchqty) as qtydispatched from dealdispatch where dealid='$slid'";
								$dealsumqq=mysqli_query($conn,$dealsum);
								$dealsum=mysqli_fetch_assoc($dealsumqq);
								$dealpending=$dealidrw['orderqty']-$dealsum['qtydispatched'];
								echo $dealpending;
								//echo round($crx['dispatchedqty'],2); ?> Tons</td>
							</tr>
							<tr>
								<td>Pending Qty</td>
								<td><?php 
								
								echo ($crx['totalqty']-$crx['dispatchedqty']); ?> Tons</td>
							</tr>
							<tr>
								<td>Deal Remarks</td>
								<td><?php echo ($crx['orderremarks']); ?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-6 mt-4 mt-md-0">
			<div class="article">
				<div class="article-heading flex-heading">
					<h5 class="text-center">Party Details</h5>
				</div>
				<div class="article-content container-max">
					<h6 class="semi mb-1"><?php echo $crx['cust'];?></h6>
					<p class="address small"><?php echo $crx['address'];?>, <?php echo $crx['city'];?> - <?php echo $crx['pincode'];?>,<br><?php echo $crx['statename'];?>,<?php echo $crx['countryname'];?></p>
					<div class="table-responsive mt-3 mb-0">
						<table class="table table-bordered">
							<tr>
								<td>Contact Person</td>
								<td><?php echo ucwords($crx['cperson']);?></td>
							</tr>
							<tr>
								<td>Designation</td>
								<td><?php echo ucwords($crx['designation']);?></td>
							</tr>
							<tr>
								<td>Contact No.</td>
								<td>+<?php echo ucwords($crx['phonecode']);?> <?php echo ucwords($crx['contact']);?></td>
							</tr>
							<tr>
								<td>Email</td>
								<td><?php echo $crx['email'];?></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-12 mt-4">
			<div class="article">
				<div class="article-heading flex-heading">
					<h5 class="text-center">Items In List</h5>
				</div>
				<div class="article-content container-max">
					<div class="table-responsive mt-3 mb-0">
						<table class="table table-bordered table-big">
							<thead>
								<tr>
									<th style="width: 150px">Sr.No.</th>
									<th>Item Name</th>
									<th>Grade</th>
									<th class="text-right">Rate</th>
									<th class="text-right">Remarks</th>
								</tr>
							</thead>
							<tbody>
								<?php 
								$y=1;
								while($sprw=mysqli_fetch_assoc($spq)){
									?>
									<tr>
										<td><?php echo $y; ?></td>
										<td style="vertical-align: middle;"><?php echo ucwords($sprw['productname']);?></td>
										<td style="vertical-align: middle;"><?php echo ucwords($sprw['grade']); ?></td>
										<td class="text-right"><?php echo $sprw['price']; ?></td>
										<td class="text-right"><?php echo $sprw['remarks']; ?></td>
									</tr>
								<?php $y++;
								} ?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
</div>

<script>
</script>
