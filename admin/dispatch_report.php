<?php
	$perm=check_permission("A","DP");
	$whr = 1;
?>

<link href="assets/css/print.css" type="text/css" rel="stylesheet">
<div class="main-content-inner">
<?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
	<?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
	<?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
	<?php
	if($perm){
	?>
	<div class="page-header no-print">
		<h1 class="page-heading h6 ebold">Dispatch Report</h1>
		<ul class="list-inline breadcrumb d-none d-md-flex">
			<li class="breadcrumb-item">Home</li>
			<li class="breadcrumb-item">Dispatch Report</li>
		</ul>
	</div>
	<div class="article">
		<div class="article-heading flex-heading no-print">
			<h5 class="text-center">Today's Dispatch Report</h5>
			<div class="">
				<a href="main.php" class="btn btn-basic btn-sm">Dashboard</a>
				<button class="btn btn-basic btn-sm" onclick="window.print()"><i class="bi bi-printer"></i>&nbsp;Print</button>
			</div>
		</div>

		<div class="article-content container-max">
			<!--<div class="filter mb-4 no-print">
				<form action="" name="filter" method="get">
					<input type="hidden" name="paction" value="dispatch_report">
					<div class="row">
						<div class="col-lg-3 col-sm-6">
							<div class="form-group">
								<label for="customer">Select Customer</label>
								<select name="customer" class="form-control select2me">
									<option value="">Select From List</option>-->
									<?php
									// $csql="select cust_id,name from customers order by name asc";
									// $cqq = mysqli_query($conn,$csql);
									// while($crw=mysqli_fetch_assoc($cqq)){
									//     echo '<option value="'.$crw['cust_id'].'"'.($crw['cust_id']==$_GET['customer']?'selected':'').'>'.strtoupper($crw['name']).'</option>';
									// }
									?>
								<!--</select>
							</div>
						</div>
						<div class=" col-lg-3 col-sm-6">
							<div class="form-group">
								<label for="gid">Select Vehicle No.</label>
								<select name="gid" class="form-control select2me">
									<option value="">Select From List</option>-->
									<?php
									//$csql="select gid,vehicleno from gate where gatestatus = '2' and date(chkouton)>=(CURDATE()-INTERVAL 1 DAY)";
									
									//$cqq = mysqli_query($conn,$csql);
									// while($crw=mysqli_fetch_assoc($cqq)){
									//     echo '<option value="'.$crw['gid'].'"'.($crw['gid']==$_GET['gid']?'selected':'').'>'.strtoupper($crw['vehicleno']).'</option>';
									// }
									?>
								<!--</select>
							</div>
						</div>
						<div class="col-lg-3 col-sm-6">
							<div class="form-group">
								<label for="orderby">Order By</label>
								<select name="orderby" class="form-control select2me">
									<option value="">Select Order By</option>
									<option value="1" <?php //echo ($_GET['orderby']=='1'?'selected':''); ?>>Customer</option>
									<option value="2" <?php //echo ($_GET['orderby']=='2'?'selected':''); ?>>Vehicle</option>
								</select>
							</div>
						</div>
						<div class="col-xl-2 col-lg-3 col-12">
							<div class="input-group h-100 justify-content-end align-items-center">
								<input type="submit" name="filter" class="btn btn-basic" value="Apply Filter">
							</div>
						</div>
					</div>
				</form>
			</div>-->

			<div class="filter mb-4 no-print">
				<form action="" name="filter" method="get">
					<input type="hidden" name="paction" value="dispatch_report">
					<div class="row">
						<div class="col-xl-3 col-lg-3 col-sm-6">
							<div class="form-group">
								<label for="customer">Select Customer</label>
								<select name="customer" class="form-control select2me">
									<option value="">Select From List</option>
									<?php
									$csql="select cust_id,name from customers order by name asc";
									$cqq = mysqli_query($conn,$csql);
									while($crw=mysqli_fetch_assoc($cqq)){
										echo '<option value="'.$crw['cust_id'].'"'.($crw['cust_id']==$_GET['customer']?'selected':'').'>'.strtoupper($crw['name']).'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-sm-6">
							<div class="form-group">
								<label for="gid">Select Vehicle No.</label>
								<select name="gid" class="form-control select2me">
									<option value="">Select From List</option>
									<?php
									$csql="select gid,vehicleno from gate where gatestatus = '2' and date(chkouton)>=(CURDATE()-INTERVAL 1 DAY)";
									// $csql="select prid, productname from products order by productname asc";
									$cqq = mysqli_query($conn,$csql);
									while($crw=mysqli_fetch_assoc($cqq)){
										echo '<option value="'.$crw['gid'].'"'.($crw['gid']==$_GET['gid']?'selected':'').'>'.strtoupper($crw['vehicleno']).'</option>';
									}
									?>
								</select>
							</div>
						</div>
						<!--<div class="col-xl-2 col-lg-3 col-sm-6">-->
						<!--    <div class="form-group">-->
						<!--        <label for="orderby">Dated</label>-->
						<!--        <input type="date" name="dated" id="dated" class="form-control">-->
						<!--    </div>-->
						<!--</div>-->
						<div class="col-xl-2 col-lg-2 col-sm-6">
							<div class="form-group">
								<label for="orderby">Order By</label>
								<select name="orderby" class="form-control select2me">
									<option value="">Select Order By</option>
									<option value="1" <?php echo ($_GET['orderby']=='1'?'selected':''); ?>>Customer</option>
									<option value="2" <?php echo ($_GET['orderby']=='2'?'selected':''); ?>>Vehicle</option>
								</select>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-sm-6">
							<div class="form-group">
								<label for="date">Date</label>
								<select name="date" class="form-control">
									<option value="">Select Date</option>
									<option value="<?php echo date('Y-m-d',strtotime("-1 days")) ;?>" <?php echo (date('Y-m-d',strtotime("-1 days"))==$_GET['date']?'selected':'');?>><?php echo date('d-m-Y',strtotime("-1 days")) ;?></option>
									<option value="<?php echo date('Y-m-d') ?>" <?php echo (date('Y-m-d')==$_GET['date']?'selected':''); ?>><?php echo date("d-m-Y");?> </option>
								</select>
							</div>
						</div>
						<div class="col-xl-1 col-lg-12">
							<div class="input-group h-100 justify-content-end align-items-center">
								<input type="submit" name="filter" class="btn btn-basic" value="Apply Filter">
							</div>
						</div>
						<div class="col-xl-2 col-lg-12">
							<div class="input-group h-100 justify-content-end- align-items-center ">
								<a href="dispatch_report_export.php?customer=<?php echo "$_GET[customer]&gid=$_GET[gid]&orderby=$_GET[orderby]&date=$_GET[date]";?>" class="btn btn-info"><i class="bi bi-file-earmark-spreadsheet"></i></a>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="table-responsive mt-3 mt-lg-0">
				<table class="table table-bordered table-large">
					<thead>
						<tr>
							<th>Sl No.</th>
							<th>Customer Name</th>
							<th>Vehicle No.</th>
							<th>Dated</th>
							<th>Dispatch User</th>
							<th>Final User</th>
							<th class="text-right">Qty Dispatched (Tons)</th>
							
							<th style="width: 3rem" class="no-print"></th>
						</tr>
					</thead>
					<tbody>
						
						<?php 
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
						$query ="select DISTINCT cust_id,name from customers,saleorder where $whr1 and customers.cust_id=saleorder.cid order by name asc";
						
						$qqq = mysqli_query($conn,$query);
						$asd=0;
						$asr = 0;
						$n = 1;
						
						while($qrw = mysqli_fetch_assoc($qqq)){
							$query2 = "SELECT
								dispatch.dispatchid,
								customers.`name`,
								dispatch.vehicleid as vhid,
								dispatch.weight AS whtsum,
								dispatch.custid,
								dispatch.createdon,
								gate.vehicleno, admin.name AS user_name, adminf.name AS user_name_f
								FROM
								dispatch
								INNER JOIN admin ON admin.admin_id = dispatch.createdby
								LEFT JOIN admin as adminf ON adminf.admin_id = dispatch.finalby
								INNER JOIN customers ON customers.cust_id = dispatch.custid
								INNER JOIN gate ON gate.gid = dispatch.vehicleid 
								where $whr and date(gate.chkouton) >= (CURDATE()-INTERVAL 1 DAY)  and dispatch.custid = $qrw[cust_id] ";

							if(!empty($_GET['orderby'])){
								$query2 .= $orderby;
							}else{
								$query2 .=" order by dispatch.custid asc";
							}
							//echo $query2;
							//echo $query2.'<br>';
							$qq2 = mysqli_query($conn,$query2);
							
							while($rw2 = mysqli_fetch_assoc($qq2)){
								//var_dump($rw2);
								?>
								<tr>
									<td><?php echo $n; //echo $rw2['custid']; ?></td>
									<td><?php echo strtoupper($rw2['name']); ?></td>
									<td><?php echo strtoupper($rw2['vehicleno']); //echo '--'.$rw2['dispatchid']; ?></td>
									<td><?php echo date('d-m-Y',strtotime($rw2['createdon'])) ?></td>
									<td><?php echo strtoupper($rw2['user_name']); ?></td>
									<td><?php echo strtoupper($rw2['user_name_f']); ?></td>
									<td class="text-right">
                                        <?php
    									$asd=$rw2['whtsum'];
                                        echo number_format($asd,3);
                                        $asr +=$asd;
                                        ?>
                                    </td>
									<td class="no-print"><a href="main.php?paction=dispatch_report_detailed&vid=<?php echo $rw2['vhid']; ?>&cust_id=<?php echo $rw2['custid']; ?>&dispid=<?php echo $rw2['dispatchid']; ?>" class="btn action-btn btn-success"><i class="fa fa-eye"></i></a></td>
								</tr>
								<?php  $n++; 
							}
						}
						?>
						<tr>
							<th colspan="6" class="text-right">Total</th>
							<td class="text-right"><?php  echo number_format($asr,3); ?></td>
							<td class="no-print"></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php 
 } ?>
</div>

