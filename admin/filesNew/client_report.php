<?php
$perm = check_permission("A");
$whr = 1;
$prwhr = 1;
if (isset($_GET['customer']) && is_numeric($_GET['customer']) && !empty($_GET['customer'])) {
	$custid = mysqli_real_escape_string($conn, $_GET['customer']);
	$gprodid = mysqli_real_escape_string($conn, $_GET['product']);
	$gsizeid = mysqli_real_escape_string($conn, $_GET['size']);
	$whr .= " and customers.cust_id='$custid' ";
}



// if(isset($_GET['mark1']) && $_GET['mark1']=='OC'){
// 	$orderid =  mysqli_real_escape_string($conn, $_GET['orderid']);
// 	$custid = mysqli_real_escape_string($conn, $_GET['custid']);
// 	//first update all items of order.
// 	//select completed,smarkedcompleted from saleordersizes where sosaleid=10
// 	$uporderitem = "update saleordersizes set completed='1', smarkedcompleted='$createdon' where sosaleid ='$orderid'";
// 	//echo $uporderitem;
// 	mysqli_query($conn,$uporderitem);
// 	// second update order itself.
// 	//select status,smarkedcompleted from saleorder where slid=10 
// 	$uporder = "update saleorder set status='1',smarkedcompleted='$createdon' where slid='$orderid'";
// 	mysqli_query($conn,$uporder);
// 	echo '<script>window.location.href="main.php?paction=client_report&msg=Order Marked Completed Successfully.#'.$custid.'"</script>';
// }

if (isset($_GET['mark']) && $_GET['mark'] == 'DD') {
	$dealid = mysqli_real_escape_string($conn, $_GET['dealid']);
	$custid = mysqli_real_escape_string($conn, $_GET['custid']);
	$updeal = "update dealorder set status='1',markedcompleted='$createdon' where slid='$dealid'";
	mysqli_query($conn, $updeal);
	echo '<script>window.location.href="?paction=client_report&msg=Deal Item  Marked Completed Successfully.#' . $custid . '"</script>';
}

if ($_POST['doAction'] == 'adddealdispatch') {
	//var_dump($_POST);
	$dealid = mysqli_real_escape_string($conn, $_POST['dealid']);
	$vehicleNo = mysqli_real_escape_string($conn, $_POST['vehicleNo']);
	$dispatchqty = mysqli_real_escape_string($conn, $_POST['dispatch']);

	$dipsdetails = "select orderqty,dispatchedqty from dealorder where slid='$dealid'";
	//echo $dipsdetails;
	$dqq = mysqli_query($conn, $dipsdetails);
	$drw = mysqli_fetch_assoc($dqq);

	$qtydispatched = (empty($drw['dispatchedqty']) ? 0 : $drw['dispatchedqty']);
	$newdispatchedqty = ($qtydispatched + $dispatchqty);

	//$dispatch="insert into dealdispatch set dealid = '$dealid', vehicleno='$vehicleNo', dispatchqty='$dispatchqty'";
	$dispatch = "insert into dealdispatch set dealid = '$dealid', vehicleno='$vehicleNo', dispatchqty='$dispatchqty',createdon='$createdon',createdby='$createdby'";
	//echo $dispatch.'<br>';
	$dispatchqq = mysqli_query($conn, $dispatch);
	if (mysqli_insert_id($conn) > 0) {
		$dealitemupdate = "update dealorder set dispatchedqty='$newdispatchedqty' where slid='$dealid'";
		//echo $dealitemupdate.'<br>';
		$dealitemqq = mysqli_query($conn, $dealitemupdate);
		//echo '<script>window.location.href="main.php?paction=client_report&msg=Deal Item Dispatched Successfully.&custid='+$custid+'"</script>';
		echo '<script>window.location.href="main.php?paction=client_report&msg=Deal Item Dispatched Successfully."</script>';
	}
}

if ($_GET['msg']) {
	$msg = $_GET['msg'];
}

if ($_GET['errmsg']) {
	$errmsg = $_GET['errmsg'];
}

?>
<script>
	// function cnf(id, sizecom, custid) {
	// 	let confirm = window.confirm("Do you Wand to Mark. This Size Complete?");
	// 	if (confirm == true) {

	// 		window.location.href = "main.php?paction=client_report&slid=" + id + "&sizecom=" + sizecom +
	// 			"&mark1=C&custid=" + custid;
	// 	} else {
	// 		// history.go(-1);
	// 	}
	// }

	// function cnforder(orderid, custid) {
	// 	let confirm = window.confirm("Do you Wand to Mark. This Order Complete?");
	// 	if (confirm == true) {
	// 		window.location.href = "main.php?paction=client_report&orderid=" + orderid + "&mark1=OC&custid=" + custid;
	// 	} else {
	// 		// history.go(-1);
	// 	}
	// }

	// function cnf2(dealid, custid) {
	// 	let confirm = window.confirm("Do you Wand to Mark. This Size Complete?");
	// 	if (confirm == true) {
	// 		window.location.href = "main.php?paction=client_report&dealid=" + dealid + "&mark=DD&custid=" + custid;
	// 	} else {
	// 		// history.go(-1);
	// 	}
	// }
</script>
<link href="assets/css/print.css" type="text/css" rel="stylesheet">
<style>
	hr {
		display: none;
	}

	.d-inline-block {
		min-width: 100%;
	}
</style>
<div class="main-content-inner">
	<?php if (!$perm) {
		$errmsg = "You are not authorized to view this section.";
	} ?>
	<?php if ($errmsg) { ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg; ?></div><?php } ?>
	<?php if ($msg) { ?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg ?></div><?php } ?>
	<?php
	if ($perm) {
	?>
		<div class="page-header no-print">
			<h1 class="page-heading h6 ebold">Pending Orders</h1>
			<ul class="list-inline breadcrumb d-none d-md-flex">
				<li class="breadcrumb-item">Home</li>
				<li class="breadcrumb-item">Clientwise Report</li>
			</ul>
		</div>

		<div class="article">
			<div class="article-heading flex-heading no-print">
				<h5 class="text-center">Customerwise Pending Orders & Deals</h5>
				<div class="">
					<a href="main.php" class="btn btn-basic btn-sm">Dashboard</a>
					<button class="btn btn-basic btn-sm" onclick="window.print()"><i class="bi bi-printer"></i>&nbsp;Print</button>
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
										$csql = "select cust_id,name from customers order by name asc ";
										$cqq = mysqli_query($conn, $csql);
										while ($crw = mysqli_fetch_assoc($cqq)) {
											echo '<option value="' . $crw['cust_id'] . '"' . ($crw['cust_id'] == $custid ? 'selected' : '') . '>' . strtoupper($crw['name']) . '</option>';
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
										$csql = "select prid,productname from products order by productname asc";
										$cqq = mysqli_query($conn, $csql);
										while ($crw = mysqli_fetch_assoc($cqq)) {
											echo '<option value="' . $crw['prid'] . '"' . ($crw['prid'] == $gprodid ? 'selected' : '') . '>' . strtoupper($crw['productname']) . '</option>';
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
										$csql = "select sid,size from sizes";
										$cqq = mysqli_query($conn, $csql);
										while ($crw = mysqli_fetch_assoc($cqq)) {
											echo '<option value="' . $crw['sid'] . '"' . ($crw['sid'] == $gsizeid ? 'selected' : '') . '>' . strtoupper($crw['size']) . '</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-lg-3 col-sm-4">
								<div class="input-group h-100 justify-content-end justify-content-lg-start align-items-center">
									<input type="submit" name="filter" class="btn btn-basic" value="Apply Filter">
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
							$ordersql1 = "SELECT DISTINCT cust_id,name from customers,saleorder where $whr and customers.cust_id=saleorder.cid and  (saleorder.`status`=0 or (DATE(saleorder.`smarkedcompleted`)= Date(NOW()) and saleorder.`status`=1)) order by name ASC";
							$ordersql2 = "SELECT DISTINCT cust_id,name from customers,dealorder where $whr and  customers.cust_id=dealorder.cid  and (dealorder.`status`=0 or (DATE(dealorder.`markedcompleted`)= Date(NOW()) and dealorder.`status`=1)) order by name ASC";
							$orderqq1 = mysqli_query($conn, $ordersql1);
							$orderqq2 = mysqli_query($conn, $ordersql2);
							$a = [];
							$b = [];

							while ($orw1 = mysqli_fetch_assoc($orderqq1)) {
								if (!in_array($orw1['cust_id'], $a)) {
									array_push($a, $orw1);
								}
							}

							while ($orw2 = mysqli_fetch_assoc($orderqq2)) {
								if (!in_array($orw2['cust_id'], $a)) {
									if (!in_array($orw2, $a)) {
										array_push($a, $orw2);
									}
								}
							}
							$key = array_column($a, 'name');
							array_multisort($key, SORT_ASC, $a);
							foreach ($a as $orw) {
								$prwhr = 1;

								$prwhr1 = 1;

								if (isset($_GET['product']) && is_numeric($_GET['product']) && !empty($_GET['product'])) {
									$productid = mysqli_real_escape_string($conn, $_GET['product']);
									$prwhr .= "  and products.prid=$productid";
									$prwhr1 .= "  and products.prid=$productid";
								}
								if (isset($_GET['size']) && is_numeric($_GET['size']) && !empty($_GET['size'])) {
									$sizeid = mysqli_real_escape_string($conn, $_GET['size']);
									$prwhr1 .= " and sizes.sid=$sizeid ";
								}
								if (isset($_GET['customer']) && is_numeric($_GET['customer']) && !empty($_GET['customer'])) {
									$customerid = mysqli_real_escape_string($conn, $_GET['customer']);
									$prwhr .= " and dealorder.cid=$customerid";
									$prwhr1 .= " and saleorder.cid=$customerid";
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

								$sizeqq1 = mysqli_query($conn, $sizesql1);
								$sizesqlcnt = mysqli_num_rows($sizeqq1);
								//var_Dump('sizesqlcnt:'.$sizesqlcnt);
								$dealsql1 = "SELECT
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
								$dealqq1 = mysqli_query($conn, $dealsql1) or die(mysqli_error($conn));
								$dealsqlcnt = mysqli_num_rows($dealqq1);
								if ($sizesqlcnt > 0 || $dealsqlcnt > 0) {
							?>
									<tr id="<?php echo $orw['cust_id']; ?>" class="tabledata">
										<td><?php
											$cnamesql = "select cust_id,name from customers where cust_id=$orw[cust_id]";
											$cnameqq = mysqli_query($conn, $cnamesql);
											$cnamerw = mysqli_fetch_assoc($cnameqq);
											echo strtoupper($cnamerw['name']); //strtoupper($orw); 
											?></td>
										<td class="px-0">
											<!-- deals for current client starts here -->
											<?php
											$dealidsql = "SELECT
												dealorder.slid AS dealid,
												dealorder.orderqty,
												dealorder.`status`,
												dealorder.orderremarks,
												dispatchedqty as qtydispatched
												from dealorder where cid=$orw[cust_id] and ((DATE(dealorder.`markedcompleted`)= DATE(NOW())  and dealorder.`status`=1) or dealorder.`status`=0)";
											//echo $dealidsql;

											$dealidqq = mysqli_query($conn, $dealidsql) or die(mysqli_error($conn));
											$dealcnt = mysqli_num_rows($dealidqq);

											if ($dealcnt > 0) {

												while ($dealidrw = mysqli_fetch_assoc($dealidqq)) {

													$dealsql = "SELECT
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
													$dealqq = mysqli_query($conn, $dealsql);
													$dealcnt = mysqli_num_rows($dealqq);
													if ($dealcnt > 0) {
														$dealsum = "select sum(dispatchqty) as qtydispatched from dealdispatch where dealid='$dealidrw[dealid]'";
														$dealsumqq = mysqli_query($conn, $dealsum);
														$dealsum = mysqli_fetch_assoc($dealsumqq);
														$dealpending = $dealidrw['orderqty'] - $dealsum['qtydispatched'];
											?>
														<div class="container-fluid px-0
												<?php
														if ($dealidrw['status'] == '1') {
															echo ' bg-lightgreen ';
														} else {
															echo ' bg-light ';
														}
												?>">
															<ul class="list-unstyled d-inline-block orderrow">
																<?php if (!empty($dealidrw['orderremarks'])) { ?>
																	<div class="d-block w-100 m-1">
																		<strong>Remarks: </strong><?php echo $dealidrw['orderremarks']; ?>
																	</div>
																<?php } ?>
																<li class="d-md-flex w-100 px-3 py-1" style="width: 100%">
																	<div class="d-flex pr-sm-3">
																		<h6 class="bold mb-0">Deal Qty : </h6>
																		<p class="mb-md-0"><?php echo $dealidrw['orderqty']; ?> Tons</p>
																	</div>
																	<div class="d-flex pr-sm-3">
																		<h6 class="bold mb-0">Dispatched Qty : </h6>
																		<p class="mb-md-0">
																			<?php echo (!empty($dealsum['qtydispatched']) ? $dealsum['qtydispatched'] : '0'); ?>
																			Tons</p>
																	</div>
																	<div class="d-flex">
																		<h6 class="bold mb-0">Pending Qty : </h6>
																		<p class="mb-md-0">
																			<?php echo $dealpending; #echo ($dealidrw['orderqty']-$dealidrw['qtydispatched']);
																			?>
																			Tons</p>
																	</div>
																	<?php if ($dealidrw['status'] == '0') { ?>
																		<div class="d-flex ml-md-2">

																			<form id="cnf2">
																				<input type="hidden" name="cust_id" id="cust_id" value="<?php echo $dealidrw['cust_id'] ?>">
																				<input type="hidden" name="dealid" id="dealid" value="<?php echo $dealidrw['dealid'] ?>">
																				<button type="button" class="btn action-btn btn-success no-print" title="Mark As Complete"><i class="bi bi-check-lg"></i>
																				</button>
																			</form> &nbsp
																			<a href="javascript: adddispatch(<?php echo $dealidrw['dealid'] ?>, <?php echo $dealpending; ?>)" class="btn action-btn btn-primary no-print mr-4" title="add new dispatch"><i class="bi bi-pencil-square"></i></a>
																		</div>
																	<?php } ?>
																</li>
																<?php while ($szrw = mysqli_fetch_assoc($dealqq)) {
																	$pending1 = (!empty($szrw['qtydispatched']) ? $szrw['qtydispatched'] : '0');
																?>
																	<li>
																		<div class="reportThumb my-1 mx-2">
																			<!-- <a href="javascript: cnf2(<?php #echo $szrw['dealid'] 
																											?>, <?php #echo ($szrw['ditemid']); 
																																			?>)" class="btn action-btn btn-success no-print" title="Mark As Complete"><i class="bi bi-check-lg"></i></a> -->
																			<!-- <a href="javascript: adddispatch(<?php #echo $szrw['dealid'] 
																													?>,<?php #echo ($szrw['ditemid']); 
																																					?>, <?php #echo $pending1; 
																																														?>)" class="btn action-btn btn-primary no-print mr-4" title="add new dispatch"><i class="bi bi-pencil-square"></i></a> -->

																			<span><b><?php echo $szrw['dealid']; ?></b> /
																				<?php echo ucwords($szrw['productname']); ?></span>
																			<span class="mb-0"><i class="fa fa-rupee-sign"></i><?php echo $szrw['price']; ?> /
																				<?php echo ucwords($szrw['grade']); ?></span>
																			<!-- <span class="mb-0">Dispatched: <?php #echo $pending1; 
																												?> Tons</span> -->
																		</div>
																	</li>
																<?php } ?>
																<!-- Dispatch vechile history start -->
																<?php
																$dispdetails = "select dealdispatchid,dealid,vehicleno,dispatchqty,dealdispatch.createdon from dealdispatch where dealid=$dealidrw[dealid]";
																$dispdetailsqq = mysqli_query($conn, $dispdetails);
																$ddcnt = mysqli_num_Rows($dispdetailsqq);

																if ($ddcnt > 0) {
																?>
																	<div class="d-block w-100">
																		<table class="table table-bordered">
																			<tr>
																				<th>S.No.</th>
																				<th>V.No.</th>
																				<th>Qty.</th>
																				<th>Dated</th>
																				<th>Action</th>
																			</tr>
																			<?php
																			$y = 1;
																			while ($ddrw = mysqli_fetch_assoc($dispdetailsqq)) { ?>
																				<tr class="tr_<?php echo $ddrw['dealdispatchid']; ?>">
																					<td><?php echo $y; ?></td>
																					<td class="vehicleno_val"><?php echo strtoupper($ddrw['vehicleno']); ?></td>
																					<td class="dispatchqty_val"><?php echo $ddrw['dispatchqty']; ?></td>
																					<td><?php
																						if ($ddrw['createdon'] != '0000-00-00 00:00:00' || empty($ddrw['createdon'])) {
																							echo date('d-m-Y', strtotime($ddrw['createdon']));
																						}
																						?>
																					</td>
																					<td>
																						<a href="javascript: editdispatch(<?php echo $dealidrw['dealid'] ?>, <?php echo $dealpending; ?>,<?php echo $ddrw['dealdispatchid']; ?>,'<?php echo $ddrw['vehicleno']; ?>', 'dispatchqty_val')" class="btn action-btn btn-primary no-print mr-4" title="add new dispatch"><i class="bi bi-pencil-square"></i></a>
																						<!-- <a href="javascript: editdispatch(<?php echo $dealidrw['dealid'] ?>, <?php echo $dealpending; ?>,<?php echo $ddrw['dealdispatchid']; ?>,'<?php echo $ddrw['vehicleno']; ?>',<?php echo $ddrw['dispatchqty']; ?>)" class="btn action-btn btn-primary no-print mr-4" title="add new dispatch"><i class="bi bi-pencil-square"></i></a> -->
																					</td>
																				</tr>
																			<?php
																				$y++;
																			}
																			?>
																		</table>

																	</div>
																<?php } ?>
																<!-- Dispatch vechile history ends -->
															</ul>
														</div>
											<?php }
												}
											} ?>
											<!-- deals for current client ends here -->
											<!-- Put break line between deals and orders items for current client -->
											<hr>
											<!-- order for current client starts here -->
											<?php
											$saleordersql = "select * from saleorder where cid='$orw[cust_id]' and ((DATE(saleorder.`smarkedcompleted`)= DATE(NOW()) and saleorder.`status`=1) or saleorder.`status`=0)";
											//$orw[cust_id]
											$saleorderqq = mysqli_query($conn, $saleordersql);
											while ($saleorderrw = mysqli_fetch_assoc($saleorderqq)) {
												$dispsql = "SELECT DISTINCT dispatch_item.dispid,dispatch.weight FROM dispatch_item
													INNER JOIN dispatch ON dispatch.dispatchid = dispatch_item.dispid
													where date(dispatch_item.createdon)=CURDATE() and weight is null and slid=$saleorderrw[slid] order by dispatch_item.dispid asc";
												$dispqq = mysqli_query($conn, $dispsql);
											?>
												<div class="container-fluid px-2 
													<?php
													if ($saleorderrw['status'] == '1') {
														echo ' bg-lightgreen ';
													} else if (mysqli_num_rows($dispqq) > 0 && $saleorderrw['status'] != '1') {
														echo 'thumb-yellow ';
													} else {
														echo ' bg-light ';
													}
													?>
													">
													<ul class="list-unstyled d-inline-block orderrow">
														<!-- Loop this with new order -->
														<?php if (!empty($saleorderrw['remarks'])) {
															echo '<span  class="d-block w-100 m-1"><strong> Remarks:</strong>' . $saleorderrw['remarks'] . '</span>';
														} ?>
														<?php if ($saleorderrw['status'] == '0') { ?>
															<div class="d-flex ml-md-2">
																<strong>Mark Order Complete: </strong><a href="javascript: cnforder(<?php echo $saleorderrw['slid']; ?>,<?php echo $orw['cust_id']; ?>)" class="btn action-btn btn-success no-print" title="Mark As Complete"><i class="bi bi-check-lg"></i></a> &nbsp
															</div>
														<?php } ?>
														<?php echo (empty($saleorderrw['smarkedcompleted']) ? '' : '<strong class="d-block w-100 text-center lead">COMPLETED -' . date('d-m-Y', strtotime($saleorderrw['smarkedcompleted'])) . '</strong>'); ?>
														<?php
														$prwhr = 1;
														if (isset($_GET['product']) && is_numeric($_GET['product']) && !empty($_GET['product'])) {
															$productid = mysqli_real_escape_string($conn, $_GET['product']);
															$prwhr .= "  and products.prid=$productid";
														}
														if (isset($_GET['size']) && is_numeric($_GET['size']) && !empty($_GET['size'])) {
															$sizeid = mysqli_real_escape_string($conn, $_GET['size']);
															$prwhr .= " and sizes.sid=$sizeid ";
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
														$sizeqq = mysqli_query($conn, $sizesql); ?>
														<?php
														while ($szrw = mysqli_fetch_assoc($sizeqq)) {

															$vhnosql = "SELECT DISTINCT dispatch_item.vid, gate.vehicleno FROM dispatch_item
													INNER JOIN gate ON gate.gid = dispatch_item.vid where slid=$szrw[sosaleid] and dispatch_item.sosize=$szrw[sosize]";

															$vhnoqq = mysqli_query($conn, $vhnosql);
															$vhnocnt = mysqli_num_rows($vhnoqq);

															$colorsql = "select sosize from dispatch_item where sosize='$szrw[sosize]' and date(dispatchedon)=CURDATE()";
															$colorqq = mysqli_query($conn, $colorsql);
															$crow = mysqli_num_rows($colorqq);
															if (empty($crow)) {
																$crow = 0;
															}
														?>

															<li>
																<!-- Repeat this with new Item in Order -->
																<div class="reportThumb my-2 mx-1 <?php
																									if ($szrw['completed'] == 1) {
																										echo ' bg-lightgreen ';
																									} else if ($crow == 1) {
																										echo ' bg-lightgreen ';
																									} ?>">
																	<?php if (!empty($szrw['itemremarks'])) {
																		echo '<span  class="d-block w-100 m-1"><strong> Remarks:</strong>' . $szrw['itemremarks'] . '</span>';
																	} ?>
																	<?php echo ($szrw['completed'] == 1 ? '<strong class="d-block w-100 text-center">COMPLETED-' . date('d-m-Y', strtotime($szrw['smarkedcompleted'])) . '</strong>' : '');
																	if ($vhnocnt > 0) {
																		echo '<span><strong>Vehicle No. </strong>';
																		while ($vhnorw = mysqli_fetch_assoc($vhnoqq)) {
																			echo $vhnorw['vehicleno'] . '-';
																		}
																		echo '</span>';
																	}
																	?>
																	<?php if ($szrw['completed'] == '0') { ?>
																		<form class="cnf">
																			<input type="hidden" name="cnf" value="3" id="cnf">

																			<input type="hidden" name="sosaleid" value="<?php echo $szrw['sosaleid']; ?>" id="sosaleid">
																			<input type="hidden" name="cust_id" value="<?php echo $orw['cust_id']; ?>" id="cust_id">
																			<input type="hidden" name="sosize" value="<?php echo $szrw['sosize']; ?>" id="sosize">
																			<button type="button" class="btn cnf action-btn btn-success no-print" title="Mark As Complete"><i class="bi bi-check-lg"></i></button>
																		</form>
																		<!-- <a href="javascript:cnf(<?php
																										// echo $szrw['sosaleid']; 
																										?> <?php
																		// echo $szrw['sosize'];
																?><?php
																		//  echo $orw['cust_id']; 
																?>)"
																data-toggle="tooltip" class="btn action-btn btn-success no-print"
																title="Mark As Complete"><i class="bi bi-check-lg"></i></a> -->
																	<?php } ?>
																	<span class="<?php if ($szrw['completed'] == '0') {
																						echo "pr-5";
																					} ?>"><b><?php echo $szrw['sosaleid']; ?></b>/<?php echo ucfirst($szrw['productname']); ?>
																		/ <?php echo $szrw['size']; ?></span>
																	<span class="mb-0">
																		<?php echo $szrw['qty'];
																		echo getorderunits($szrw['qtytype']); ?> /
																		<?php echo tonstounits($szrw['stdlength'], $szrw['lengthtype'], $szrw['mtweight'], $szrw['ftweight'], ($szrw['weightintons'] - $szrw['dispatched']), $szrw['qtytype'], $szrw['bundleweight']);
																		echo getorderunits($szrw['qtytype']); ?>
																	</span>
																	<span class="mb-0"> <i class="fa fa-rupee-sign"></i><?php echo $szrw['soprice']; ?> /
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
								} // check if size or product exists in saleorder or  
							} // customer name while loop ends here 
							?>
						</tbody>
					</table>
				</div>

				<!-- Pagging -->
				<div class="pagging">
					<div class="right">
						<?php
						/* include('ps_pagination.php');
                        $pager = new PS_Pagination($conn, $sql, $noprd, 6, "paction=$_GET[paction]");
                        $pager->setDebug(false);
                        $pager->total_rows=$result;
                        $rs = $pager->paginate();
                        echo $pager->renderFirst();
                        echo $pager->renderPrev("Back");
                        echo $pager->renderNav('<span>', '</span>');
                        echo $pager->renderNext("Next");
                        echo $pager->renderLast(); */
						?>
					</div>
				</div>
				<!-- End Pagging -->
			</div>
		</div>
	<?php
	}
	?>
</div>

<div class="modal" id="editdispatchtodeals">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title bold">Add New Dispatch To Deal</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<h6 class="bold">Pending Qty : <span id="pendingQty">0</span> Tons</h6>

				<form class="mt-4" method="post" id="updt_form">
					<!-- <input type="hidden" name="doAction" value="editdealdispatch"> -->
					<input type="hidden" name="dealid" id="dealid" required>
					<input type="hidden" name="pendingqty" id="pendingqty" required>
					<input type="hidden" name="dealdispatchid" id="dealdispatchid" required>
					<input type="hidden" name="olddispatchvalue" id="olddispatchvalue" required>

					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label for="vehicleNo">Vehicle No.</label>
								<input type="text" name="vehicleNo" id="vehicleNo" class="form-control" required>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label for="vehicleNo">Qty Dispatched (Tons)</label>
								<input type="number" step="0.001" name="dispatch" id="dispatch" class="form-control" required>
							</div>
						</div>
					</div>
					<div class="text-right">
						<input type="submit" name="dispatchupdate" class="btn btn-basic" value="Update">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="adddispatchtodeals">
	<div class="modal-dialog modal-lg modal-dialog-centered">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title bold">Add New Dispatch To Deal</h5>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<h6 class="bold">Pending Qty : <span id="pendingQty1">0</span> Tons</h6>
				<form action="" class="mt-4" method="post" id="adddispatchtodeals1">
					<input type="hidden" id="adddealdispatch" name="adddealdispatch" value="adddealdispatch">
					<input type="hidden" name="dealid" id="dealid1" required>
					<!-- <input type="hidden" name="dealitemid" id="dealitemid" required> -->
					<div class="row">
						<div class="col-lg-6">
							<div class="form-group">
								<label for="vehicleNo">Vehicle No.</label>
								<input type="text" name="vehicleNo" id="vehicleNo1" class="form-control" required>
							</div>
						</div>
						<div class="col-lg-6">
							<div class="form-group">
								<label for="dispatch1">Qty Dispatched (Tons)</label>
								<input type="number" step="0.001" name="dispatch" id="dispatch1" class="form-control" required>
							</div>
						</div>
					</div>
					<div class="text-right">
						<input type="submit" name="dispatchsubmit" id="dispatchupdate" class="btn btn-basic" value="Dispatch">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<script>
	function editdispatch(dealid, pendingqty, dealdispatchid, vehicleNo, dispatch) {
		var tr = $('.tr_' + dealdispatchid);
		vehicleno_val = tr.find('.vehicleno_val').text();
		dispatchqty_val = tr.find('.dispatchqty_val').text();
		// console.log(dispatchqty_val, $('#dispatch').val());

		$('#pendingQty').text(pendingqty);
		$('#pendingqty').val(pendingqty);
		$('#dealid').val(dealid);
		$('#dispatch').val(dispatchqty_val);
		// $('#dispatch').val(dispatch);
		// $('#vehicleNo').val(vehicleNo);
		$('#vehicleNo').val(vehicleno_val);
		$('#dealdispatchid').val(dealdispatchid); //value to be changed dont delete
		$('#olddispatchvalue').val(dispatchqty_val); //current value before changes dont delete
		$('#editdispatchtodeals').modal();


		// $('#dispatch').trigger("change");
	}

	function adddispatch(dealid, pendingqty) {
		console.log(dealid);
		console.log(pendingqty);
		$('#pendingQty1').text(pendingqty);
		$('#dealid1').val(dealid);
		$('#adddispatchtodeals').modal();
	}
	// function adddispatch(dealid, pendingqty){
	//     console.log(dealid);console.log(pendingqty);
	// // function adddispatch(dealid, dealitemid, pendingqty){
	//     $('#pendingQty').text(pendingqty);
	//     $('#dealid').val(dealid);
	//     // $('#dealitemid').val(dealitemid);
	//     $('#adddispatchtodeals').modal();
	// }

	$('.product').on('change', function() {
		let pid = $(this).val();
		console.log(pid);
		$.ajax({
			type: 'post',
			url: "Ajax.php",
			data: {
				pid: pid,
				doAction: 'getsizesforproductid'
			},
			success: function(data) {
				console.log(data);
				let di4 = JSON.parse(data);
				console.log(di4);
				html4 = '<option value="">Select from List</option>';
				for (d = 0; d < di4.length; d++) {
					html4 += '<option value="' + di4[d]['sid'] + '">' + di4[d]['size'] +
						'</option>';
				}
				console.log(html4);
				$('.size2').html(html4);

			}
		});
	});

	$(document).on('click', '.statusToggle', function() {
		$(this).toggleClass('active inactive');
		$(this).children('.fa').toggleClass('fa-toggle-on fa-toggle-off');
		let id = $(this).data('id');
		let action = "changestatus";
		$.ajax({
			type: 'post',
			url: "Ajax.php",
			data: {
				uid: id,
				doAction: action
			},
			success: function(data) {
				console.log(data);
				location.reload(true);
			}
		});
	});
</script>

<script>
	$(document).ready(function() {
		//$(document).on('click', '.cnf', function (e) {
		$("#cnf").submit(function(e) {
			///alert('dddd');
			e.preventDefault();
			var sosaleid = $('#sosaleid').val();
			var cust_id = $('#cust_id').val();
			var sosize = $('#sosize').val();
			var option = '';
			$.ajax({
				type: "POST",
				url: "ajax_update.php",
				data: $(this).serialize(), // serializes the form's elements. 
				success: function(result) {
					//result=$.parseJSON(result);
					//console.log(result);
					if (result.success == 1) {
						$('#adddispatchtodeals').modal('hide');

						console.log(result);
						alert("Record saved.");
					}
					//$('#broker1').val(result.id)
				}
			});
			$('.fade').hide();
			return false;
		});
	});

	$(document).ready(function() {
		$("#updt_form").submit(function(e) {
			e.preventDefault();
			var dealid = $('#dealid').val();
			var dealdispatchid = $('#dealdispatchid').val();
			// alert(dealid);
			// alert('.tr_'+dealdispatchid);
			var tr = $('.tr_' + dealdispatchid);
			// v=tr.find('.vehicleno_val').text("sssssssssss");
			// console.log(v,tr);
			var cpendingqty = $('#pendingqty').val();
			var dealdispatchid = $('#dealdispatchid').val();
			var olddispatchvalue = $('#olddispatchvalue').val();
			var vehicleNo = $('#vehicleNo').val();
			var dispatch = $('#dispatch').val();
			var option = '';
			$.ajax({
				type: "POST",
				url: "ajax_update.php",
				data: $(this).serialize(), // serializes the form's elements.

				// url: `{{ url('') }}/dealid=${dealid}&cpendingqty=${cpendingqty}&dealdispatchid=${dealdispatchid}&olddispatchvalue=${olddispatchvalue}&vehicleNo=${vehicleNo}&dispatch=${dispatch}`,
				success: function(result) {
					result = $.parseJSON(result);
					// console.log(result);
					if (result.success == 1) {
						$('#editdispatchtodeals').modal('hide');
						tr.find('.vehicleno_val').text(result.vehicleNo);
						tr.find('.dispatchqty_val').text(result.dispatchqty);

						alert("Record saved.");
					}
					// $('#broker1').val(result.id)
				}
			});
			$('.fade').hide();
			return false;
		});
	});
</script>