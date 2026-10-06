<?php

//var_dump($_GET);die;
    $perm=check_permission("A");
    $slid=mysqli_real_escape_string($conn, $_GET['slid']);
    if(isset($_GET['slid']) ){
        if(empty($_GET['slid']) && !is_numeric($_GET['slid'])){
            echo '<script>window.location.href="main.php"</script>';
        }
    }
    
    if(isset($_GET['mark']) && $_GET['mark']=='S'){
        $usizesql1 = "UPDATE saleordersizes set completed=1, smarkedcompleted='$createdon' where sosaleid='$slid'";
        $usizeqq = mysqli_query($conn,$usizesql1);
        $usizesql = "UPDATE saleorder set status='1', smarkedcompleted='$createdon' where slid='$slid'";
        echo $usizesql;
    
        $usizeqq = mysqli_query($conn,$usizesql);
        if(mysqli_affected_rows($conn)>0){
            echo '<script>window.location.href="main.php?paction=sale_order_view&msg=Sale order Marked Completed."</script>';
        }
    }
    
    if(isset($_GET['mark']) && $_GET['mark']=='C'){
        $sizecom=mysqli_real_escape_string($conn, $_GET['sizecom']);
        $upd="UPDATE saleordersizes set completed=1, smarkedcompleted='$createdon' where sosize='$sizecom'";
        echo $upd;
        mysqli_query($conn,$upd);
        echo '<script>window.location.href="main.php?paction=sale_order_details&slid='.$slid.'&msg=Size Marked Completed Successfully."</script>';
    }
    
    // if(isset($_GET['delslid']) && is_numeric($_GET['delslid'])){
    //     //first delete items from saleitems
    //     echo $did = $_GET['delslid'];
    //     $desql1 = "delete from dealitems where dealid=$did";
    //     mysqli_query($conn,$desql1); 
    //     $de2sql = "delete from dealorder where slid=$did";
    //     mysqli_query($conn,$de2sql);
    //     echo '<script>window.location.href="main.php?paction=sale_deals_view&msg=Deal Deleted Successfully."</script>';
    // } 
    if($_GET['msg']){
        $msg=$_GET['msg'];
    } 
    if($_GET['errmsg']){
        $errmsg=$_GET['errmsg'];
    }
?> 
<html>
<meta charset="utf-8">
<style>
    .sidebar,.btn{
        display: none;
    }
    .sidebar {
        position: fixed;
        display: none;
        width: 0;
    }
    .header-wrapper{
        background-color: transparent;
    }
    .header{
        display: none;
    }
    .page-wrapper {
    margin-left: 0; 
    } 
</style>
<body onload="window.print()"> 
<script>
	function cnf(id, sizecom){
		let confirm = window.confirm("Do you Wand to Mark. This Size Complete?");
		if(confirm==true){
			window.location.href="main.php?paction=sale_order_details&slid="+id+"&sizecom="+sizecom+"&mark=C";
		}else{
		   // history.go(-1);
		}
	}

	function scnf(sid){
		let confirm = window.confirm("Do you Wand to Mark. This Size Complete?");
		if(confirm==true){
			// alert(confirm)
			window.location.href="main.php?paction=sale_order_details&slid="+sid+"&mark=S";
		}else{
		   // history.go(-1);
		}
	}
	
	</script>
</script>
<?php

$cxsql = "SELECT saleorder.cid, contacts.cid AS contid, contacts.cperson, contacts.countrycode, contacts.contact, contacts.designation, saleorder.slid, contacts.email,
saleorder.createdon, customers.`name` as cust, countries.`name` as countryname, states.`name` as statename, customers.pincode, customers.address,customers.city,ccode.phonecode FROM saleorder
INNER JOIN contacts ON contacts.cid = saleorder.cperson INNER JOIN customers ON customers.cust_id = saleorder.cid INNER JOIN countries ON countries.id = customers.country  
INNER JOIN states ON states.id = customers.state INNER JOIN countries as ccode ON ccode.id = customers.country where saleorder.slid=$slid";

$cxqq = mysqli_query($conn,$cxsql);
$crx = mysqli_fetch_assoc($cxqq);

// $spid = "SELECT saleorder.slid, saleorderproducts.price,saleordersizes.weightintons,saleorder.orderqty,
// sum(saleordersizes.weightintons*saleorderproducts.price) as totalprice
// FROM saleorder
// INNER JOIN saleorderproducts ON saleorderproducts.slid = saleorder.slid
// INNER JOIN saleordersizes ON saleordersizes.sosaleid = saleorder.slid
// where saleorder.slid=$slid";
//$spid;
$spid = "SELECT
saleorder.slid,
saleorder.cid,
saleorder.cperson,
saleorder.`status`,
(select sum(weightintons) from saleordersizes where sosaleid=saleorder.slid) AS orderqty,
(select sum(dispatched) from saleordersizes where sosaleid=saleorder.slid and completed !='1') AS totaldispatched
from saleorder
where slid=$slid";

$spq = mysqli_query($conn,$spid);

$sprw=mysqli_fetch_assoc($spq);

//var_Dump($sprw);
// $mtotal='';
// $mtotal1 = '';
// $mgst='';
// $mgstamt='';
// $mgstamt1='';
// $mgtotal='';
// while($sprw=mysqli_fetch_assoc($spq)){
//     $mtotal+=($sprw['weight']*$sprw['price']);
//     $mtotal1 = ($sprw['weight']*$sprw['price']);
//     if(!empty($sprw['gst'])){
//         $mgstamt=($mtotal1*$sprw['gst'])/100;
//         $mgstamt1 +=$mgstamt;
//     }
// }
// $gtotal = ($mtotal+$mgstamt1);

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
		<!--<ul class="list-inline breadcrumb d-none d-md-flex">-->
		<!--	<li class="breadcrumb-item">Home</li>-->
		<!--	<li class="breadcrumb-item">Manage Orders</li>-->
		<!--	<li class="breadcrumb-item">Sale Order Details</li>-->
		<!--</ul>-->
	</div>
	<div class="row">
		<div class="col-md-6">
			<div class="article">
				<div class="article-heading flex-heading">
					<h5 class="text-center">Order Details</h5>
					<div class="text-right">
					     <a href="main.php?paction=print_order&slid=<?php echo $slid; ?>" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i></a>
						
						<a href="main.php?paction=edit_sale_order&slid=<?php echo $slid; ?>" class="btn btn-basic btn-sm"><i class="bi bi-pencil-square"></i></a>
						<!-- <a href="main.php?paction=sale_order_details&slid=<?php echo $slid; ?>&smcmp=1" class="btn btn-basic btn-sm btn-success"><i class="bi bi-check-lg"></i></a> -->

                        <a href="javascript:scnf(<?php echo $slid;?>)" class="btn btn-basic btn-sm btn-success"><i class="bi bi-check-lg"></i></a>
					</div>
				</div>
				<div class="article-content container-max">
					<div class="table-responsive mt-4">
						<table class="table table-bordered">
							<tr>
								<td>Order No.</td>
								<td><?php echo $crx['slid'];?></td>
							</tr>
							<tr>
								<td>Order Date</td>
								<td><?php echo date('d-m-Y',strtotime($crx['createdon']));?></td>
							</tr>
							<tr>
								<td>Total Order Qty.</td>
								<td> <?php echo round($sprw['orderqty'],2); ?> Tons</td>
							</tr>
							<tr>
								<td>Order Amount</td>
								<td><i class="fa fa-rupee-sign small"></i> <?php echo round($sprw['totalprice'],2); ?></td>
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
					<h5 class="text-center">Ordered Items</h5>
				</div>
				<div class="article-content container-max">
					<div class="table-responsive mt-3 mb-0">
						<table class="table table-bordered table-big">
							<thead>
								<tr>
									<!-- <th style="width: 230px">Item Name</th> -->
									<th>Sizes</th>
								</tr>
							</thead>
							<tbody>
							<?php 
								$qa = "SELECT saleordersizes.sosize, saleordersizes.sogradeid, saleordersizes.soitemid, saleordersizes.sobrandid, saleordersizes.sosizeid,
								saleordersizes.qty, saleordersizes.qtytype, sizes.size, brands.brandname, sizes.stdlength, sizes.lengthtype,saleordersizes.soprid, saleordersizes.sosaleid,
								products.productname,saleordersizes.smarkedcompleted,saleordersizes.completed FROM saleordersizes 
								INNER JOIN sizes ON sizes.sid = saleordersizes.sosizeid INNER JOIN brands ON brands.brid = saleordersizes.sobrandid
								INNER JOIN products ON products.prid = saleordersizes.soprid where sosaleid ='$crx[slid]' order by saleordersizes.sosize,products.productname asc";
	
								$qa = "SELECT saleordersizes.sosize, saleordersizes.sogradeid, saleordersizes.soitemid, saleordersizes.sobrandid, saleordersizes.sosizeid,
								 saleordersizes.qty, saleordersizes.qtytype, sizes.size, brands.brandname, sizes.stdlength, sizes.lengthtype,saleordersizes.soprid, saleordersizes.sosaleid,
								 products.productname,saleordersizes.smarkedcompleted,saleordersizes.completed,saleordersizes.soprice FROM saleordersizes 
								 INNER JOIN sizes ON sizes.sid = saleordersizes.sosizeid INNER JOIN brands ON brands.brid = saleordersizes.sobrandid
								 INNER JOIN products ON products.prid = saleordersizes.soprid where sosaleid ='$crx[slid]' and ((DATE_ADD(DATE_ADD(DATE(saleordersizes.smarkedcompleted), INTERVAL 12 HOUR), INTERVAL 1 DAY) >= NOW() and completed=1) or completed=0)  order by saleordersizes.sosize,products.productname asc";


								/* SELECT * FROM saleordersizes where 
							    sosaleid ='15' and ((DATE_ADD(DATE_ADD(DATE(saleordersizes.smarkedcompleted), INTERVAL 1 DAY), INTERVAL 12 HOUR) >= NOW() and completed=1) or completed=0 )*/
							    $qaq=mysqli_query($conn,$qa);
								//and saleordersizes.completed <>'1'
								//echo $qa;
							    ?>
								<tr>
									<!-- <th>
										<div class="reportThumb my-1 mx-2">
											<?php #if($pq['scnt']==0){ ?>
											<!-- <a href="main.php?paction=add_sale_order&slid=<?php #echo $pq['slid']; ?>&sopid=<?php #echo $pq['sopid']; ?>" data-toggle="tooltip" class="btn action-btn btn-primary no-print" title="" data-original-title="Edit"><i class="bi bi-pencil-square"></i></a> -->
											<?php #}else if($pq['scnt']>0){ ?>
												<!-- <a href="main.php?paction=add_sale_order&slid=<?php #echo $pq['slid']; ?>&prodid=<?php #echo $pq['sopid']; ?>" data-toggle="tooltip" class="btn action-btn btn-danger no-print" title="" data-original-title="Delete"><i class="bi bi-trash"></i></a> -->
											<?php #} ?>
											<!-- <span class="pr-4"><?php #echo ucwords($pq['productname']); ?></span><br>
											<span class="mb-0">Rs <?php #echo $pq['price']; ?>/Tons | <?php #echo ucwords($pq['grade']); ?></span>
										</div>
									</th> -->
									<td style="vertical-align: middle;">
										<div class="row no-gutters">
										<?php     
										// $esql = "SELECT saleordersizes.sosize, saleordersizes.sogradeid, saleordersizes.soitemid, saleordersizes.sobrandid, saleordersizes.sosizeid,
										// saleordersizes.qty, saleordersizes.qtytype, sizes.size, brands.brandname, sizes.stdlength, sizes.lengthtype,saleordersizes.soprid, saleordersizes.sosaleid
										// FROM saleordersizes INNER JOIN sizes ON sizes.sid = saleordersizes.sosizeid INNER JOIN brands ON brands.brid = saleordersizes.sobrandid
										// where sosaleid ='$pq[slid]' and soitemid='$pq[sopid]'  and saleordersizes.completed <>'1'";
										// echo $esql;
										// 	$eqq = mysqli_query($conn,$esql);

										// 	while($erw = mysqli_fetch_assoc($eqq)){
										while($erw = mysqli_fetch_assoc($qaq)){
											?>
											<?php 
											//$hidedate = date('Y-m-d', strtotime($erw['smarkedcompleted'] . ' +1 day'));
											//$hidedate = DATE_ADD(DATE_ADD(DATE($erw['smarkedcompleted']), INTERVAL 12 HOUR), INTERVAL 1 DAY);
											//if($erw['completed']=='1' && ($hidedate==date('Y-m-d') ){ ?>	
												<div class="col-xl-2 col-lg-3 col-md-4 col-4 <?php 
													if($erw['completed']==1){
														echo ' bg-lightgreen ';
													}
													?>">
												
													<div class="reportThumb my-1 mx-2">
														<?php if($erw['completed']=='0'){ ?>
															<a href="javascript:cnf(<?php echo $slid;?>, <?php echo $erw['sosize'];?>)" data-toggle="tooltip" class="btn action-btn btn-success no-print" title="Mark As Complete"><i class="bi bi-check-lg"></i></a>
														<?php } ?>
													
														<span class="pr-4"><strong><?php echo $erw['productname']; ?></strong><?php echo $hidedate; ?></span></br>
														<span class="pr-4"><?php echo $erw['size'].' | Rs.'.$erw['soprice']; ?> 
														</span><br>
														<span class="mb-0"><?php echo ucwords($erw['brandname']); ?> | <?php echo $erw['qty']; 
														echo getorderunits($erw['qtytype']);
														?> </span>
													</div>
												</div>
											
											
											<?php }
											// } ?>
											
										</div>
									</td>
								</tr>
								<?php #} ?>
							</tbody>
						</table>
				   
						<!-- <table class="table table-bordered">
							<thead>
								<tr>
									<th>Item Name</th>
									<th>Size/Dia</th>
									<th>Length</th>
									<th>Length Type</th>
									<th>Grade</th>
									<th>Brand</th>
									<th class="text-center">Qty (Tons)</th>
									<th class="text-center">Price/Ton</th>
									<th class="text-center">Total</th>
									<th class="text-center">GST%</th>
									<th class="text-center">GST Amt</th>
									<th class="text-center">Subtotal</th>
									<th></th>
								</tr>
							</thead>
							<tbody>
								<?php 
								// $spqq = mysqli_query($conn,$spid);
								// $stotal ='';
								// $xstotal = '';
								// $xtotal = '';
								// $xgstamt = '';
								// while($sprw1=mysqli_fetch_assoc($spqq)){ 
								//     $ntotal = ($sprw1['weight']*$sprw1['price']);
								//     $ngstamt2 = '';
								//     if(!empty($sprw1['gst'])){
								//         $ngstamt = ($ntotal*$sprw1['gst'])/100;
								//         $ngstamt2 = $ngstamt;
								//     }else{
								//         $ngstamt = '-';
								//         $ngstamt2 = '0';
								//     }
									
								//    // echo ($ngstamt2.'_'.$ntotal);
								//     $stotal = ($ngstamt2+$ntotal);

								//     $xtotal += $ntotal;
								//     $xgstamt += $ngstamt2;
								//     $xstotal  += $stotal;
									?>
								<tr>
									<td><?php #echo ucwords($sprw1['productname']); ?></td>
									<td><?php #echo ucwords($sprw1['size']); ?></td>
									<td><?php
									// if($sprw1['length']!='0.00'){
									//     echo $sprw1['length']; 
									//     echo ($sprw1['length']=='1'?' Ft.':'Mt.');
									// }else{
									//     echo '-';
									// }
									
									?></td>
									<td><?php #echo (!empty($sprw1['lentyp'])?$sprw1['lentyp']:'-'); ?></td>
									<td><?php #echo ucwords($sprw1['grade']); ?></td>
									<td><?php #echo (!empty($sprw1['brandname'])?ucwords($sprw1['brandname']):'-'); ?></td>
									<td class="text-right"><?php #echo $sprw1['weight']; ?></td>
									<td class="text-right"><?php #echo $sprw1['price']; ?></td>
									<td class="text-right"><?php #echo $ntotal; ?></td>
									<td class="text-right"><?php #echo ($sprw1['gst']!='0'?$sprw1['gst']:'-'); ?></td>
									<td class="text-right"><?php #echo $ngstamt; ?></td>
									<td class="text-right"><?php #echo $stotal; ?></td>
									<td>
										<a href="main.php?paction=Sale_order_details&poid=12&pid=1" class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a>
									</td>
								</tr>
								<?php #} ?>
								<tr>
									<th class="text-right" colspan="8">Total</th>
									<td class="text-right"><?php #echo $xtotal; ?></td>
									<td class="text-right" colspan="2"><?php #echo $xgstamt; ?></td>
									<td class="text-right"><?php #echo $xstotal; ?></td>
									<td class="text-right"></td>
								</tr>
							</tbody>
						</table> -->
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
</div>

<script>
	$(document).on('click','.statusToggle',function(){
		$(this).toggleClass('active inactive');
		$(this).children('.fa').toggleClass('fa-toggle-on fa-toggle-off');
		let id = $(this).data('id');
		let action = "changestatus";
		$.ajax({  
			type:'post',
			url:"Ajax.php",
			data:{uid:id,doAction:action},
			success:function(data){
				console.log(data);
				location.reload(true); 
			}
		});
	});
 </script>  

<script>
     onload="window.print()"
    $(document).ready(function () { 
    window.print(); 
    });
</script>
</html>
