<?php
$perm = check_permission("A", "DP");
$whr = 1;
?>
<link href="assets/css/print.css" type="text/css" rel="stylesheet">
<div class="main-content-inner">
	<?php
	if ($perm) {
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
				<div class="filter mb-4 no-print">
					<form action="" name="filter" method="get">
						<input type="hidden" name="paction" value="print_dispatch_lists">
						<div class="row">
							<div class="col-xl-3 col-lg-3 col-sm-6">
								<div class="form-group">
									<label for="customer">Select Customer</label>
									<select name="customer" class="form-control select2me">
										<option value="">Select From List</option>
										<?php
										$csql = "select cust_id,name from customers order by name";
										$cqq = mysqli_query($conn, $csql);
										while ($crw = mysqli_fetch_assoc($cqq)) {
											echo '<option value="' . $crw['cust_id'] . '"' . ($crw['cust_id'] == $_GET['customer'] ? 'selected' : '') . '>' . strtoupper($crw['name']) . '</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="col-lg-3 col-sm-4">
								<div class="form-group">
									<label>Select Product</label>
									<select name="product" class="form-control select2me product1">
										<option value="">Select From List</option>
										<?php
										$csql1 = "select prid,productname from products order by productname asc";
										$cqq1 = mysqli_query($conn, $csql1);
										while ($crw1 = mysqli_fetch_assoc($cqq1)) {
											echo '<option value="' . $crw1['prid'] . '"' . ($crw1['prid'] == $_GET['product'] ? 'selected' : '') . '>' . strtoupper($crw1['productname']) . '</option>';
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
								<div class="form-group">
									<label>Date</label>
									<input type="date" name="dt_frm" max="<?php echo date("Y-m-d");?>" value="<?php echo $_GET['dt_frm'];?>" class="form-control" />
								</div>
							</div>
							<div class="col-lg-3 col-sm-4">
								<div class="form-group">
									<label>Date</label>
									<input type="date" name="dt_to" max="<?php echo date("Y-m-d");?>" value="<?php echo $_GET['dt_to'];?>" class="form-control" />
								</div>
							</div>
							<!-- <div class="col-xl-3 col-lg-3 col-sm-6">
								<div class="form-group">
									<label for="gid">Select Vehicle No.</label>
									<select name="gid" class="form-control select2me">
										<option value="">Select From List</option>
										<?php
										// $csql="select gid,vehicleno from gate where gatestatus = '2' and date(chkouton)>=(CURDATE()-INTERVAL 1 DAY)";
										// // $csql="select prid, productname from products order by productname asc";
										// $cqq = mysqli_query($conn,$csql);
										// while($crw=mysqli_fetch_assoc($cqq)){
										//     echo '<option value="'.$crw['gid'].'"'.($crw['gid']==$_GET['gid']?'selected':'').'>'.strtoupper($crw['vehicleno']).'</option>';
										// }
										?>
									</select>
								</div>
							</div> -->
							<!-- <div class="col-xl-2 col-lg-3 col-sm-6">
							   <div class="form-group">
							       <label for="orderby">Dated</label>
							       <input type="date" name="dated" id="dated" class="form-control">
							   </div>
							</div> -->
							<div class="col-xl-2 col-lg-12">
								<div class="input-group h-100 justify-content-end align-items-center">
									<input type="submit" name="filter" class="btn btn-basic" value="Apply Filter">
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
								<th>Product </th>
								<th>Size </th>
								<th>Vehicle No.</th>
								<th>Dated</th>
								<th class="text-right">Qty Dispatched (Tons)</th>

								<th style="width: 3rem" class="no-print"></th>
							</tr>
						</thead>
						<tbody>
							<?php
							$whr = 1;
							$whr1 = 1;
							if (isset($_GET['customer']) && !empty($_GET['customer']) && is_numeric($_GET['customer'])) {
								$whr1 .= " and customers.cust_id='$_GET[customer]'";
								$whr .= " and dispatch.custid='$_GET[customer]'  ";
							}
							if (isset($_GET['product']) && !empty($_GET['product']) && is_numeric($_GET['product'])) {
								$whr .= " and products.prid='$_GET[product]'";
							}
							if (isset($_GET['size']) && !empty($_GET['size']) && is_numeric($_GET['size'])) {
								$whr .= " and sizes.sid='$_GET[size]'";
							}
							if (isset($_GET['orderby']) && !empty($_GET['orderby']) && is_numeric($_GET['orderby'])) {
								$oby = $_GET['orderby'];
								if ($oby == '1') { // customer
									$orderby = ' order by dispatch.custid desc';
								} else if ($oby == '2') { //vehicle no
									$orderby = ' Order by dispatch.vehicleid desc ';
								}
							}
							if (isset($_GET['dt_frm']) && !empty($_GET['dt_frm'])) {
								$whr .= " and DATE(dispatch.createdon) >= '$_GET[dt_frm]'";
							}
							if (isset($_GET['dt_to']) && !empty($_GET['dt_to'])) {
								$whr .= " and DATE(dispatch.createdon) <= '$_GET[dt_to]'";
							}

							//$query ="select DISTINCT cust_id,name from customers,saleorder where $whr and customers.cust_id=saleorder.cid and ((DATE(saleorder.`smarkedcompleted`)= Date(NOW()) and saleorder.`status`=1) or saleorder.`status`=0) order by cust_id asc";
							// $query ="select DISTINCT cust_id,name from customers,saleorder where $whr1 and customers.cust_id=saleorder.cid order by name asc";

							// $qqq = mysqli_query($conn,$query);
							// $asd=0;
							// $asr = 0;
							// $n = 1;

							// while($qrw = mysqli_fetch_assoc($qqq)){
							$query2 = "SELECT
								dispatch.dispatchid,
								customers.`name`,
								dispatch.vehicleid as vhid,
								dispatch.weight AS whtsum,
								dispatch.custid,
								dispatch.createdon,
								products.prid,
								products.productname,
								sizes.sid,
								sizes.size as product_sizes,
								gate.vehicleno
								FROM
								dispatch
								INNER JOIN dispatch_item ON dispatch_item.dispid = dispatch.dispatchid
								INNER JOIN saleordersizes ON saleordersizes.sosize = dispatch_item.sosize 
								INNER JOIN products ON products.prid = saleordersizes.soprid 
								INNER JOIN sizes ON sizes.prid = products.prid 
								INNER JOIN customers ON customers.cust_id = dispatch.custid
								INNER JOIN gate ON gate.gid = dispatch.vehicleid 
								where $whr 
								and DATE(dispatch.createdon) >= ( CURDATE() - INTERVAL 2 DAY )
								group by dispatch.dispatchid 
							"; // limit 100
							//limit 100
							// echo "<pre>";print_r($query2);echo "</pre>";
							// die;
							// if(!empty($_GET['orderby'])){
							//     $query2 .= $orderby;
							// }else{
							$query2 .= "order by customers.name asc limit 500";
							// $query2 .= "order by dispatch.createdon desc limit 500";
							// }
							//echo $query2;
							//echo $query2.'<br>';
							$qq2 = mysqli_query($conn, $query2);
							foreach ($qq2 as $k => $rw2) {
								//while($rw2 = mysqli_fetch_assoc($qq2)){
								// var_dump($rw2);die;
								?>
								<tr>
									<td><?php echo $k + 1; //echo $rw2['custid']; ?></td>
									<td><?php echo strtoupper($rw2['name']); ?></td>
									<td><?php echo strtoupper($rw2['productname']); ?></td>
									<td><?php echo strtoupper($rw2['product_sizes']); ?></td>
									<td><?php echo strtoupper($rw2['vehicleno']); //echo '--'.$rw2['dispatchid']; ?></td>
									<td><?php echo date('d-m-Y', strtotime($rw2['createdon'])) ?></td>
									<td class="text-right">
										<?php
										$asd = $rw2['whtsum'];
										echo number_format($asd, 3);
										$asr += $asd;
										?>
									</td>
									<td class="no-print"><a href="main.php?paction=dispatch_report_detailed&vid=<?php echo $rw2['vhid']; ?>&cust_id=<?php echo $rw2['custid']; ?>&dispid=<?php echo $rw2['dispatchid']; ?>" class="btn action-btn btn-success"><i class="fa fa-eye"></i></a></td>
								</tr>
								<?php  // $n++; 
							}
							?>
							<tr>
								<th colspan="6" class="text-right">Total</th>
								<td class="text-right"><?php echo number_format($asr, 3); ?></td>
								<td class="no-print"></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<?php
	}
	?>
</div>

<script>
	$('.product1').on('change', function() {
		let pid = $(this).val();
		//alert(pid);
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
</script>