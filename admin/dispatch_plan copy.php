<?php
//,(select tokenid from gate where gid=dispatch.vehicleid) as token
$perm = check_permission("A");
$vid = mysqli_real_escape_string($conn, $_GET['vid']);

if (isset($_GET['vid']) && !empty($_GET['vid']) && is_numeric($_GET['vid'])) {
	if (isset($_GET['custid']) && !empty($_GET['custid']) && is_numeric($_GET['custid'])) {
		if (isset($_GET['ddispid']) && !empty($_GET['ddispid']) && is_numeric($_GET['ddispid'])) {
			//delete dispatch slip if weight is empty in dispath table for the selected dispatch id  and also 
			$deldispatch = "DELETE from dispatch where vehicleid='$vid' and custid='$_GET[custid]' and dispatchid='$_GET[ddispid]' and weight is null or weight='0'";
			mysqli_query($conn, $deldispatch);
			if (mysqli_affected_rows($conn) > 0) {
				// echo '<br><br>';
				//and all dispatch items from the same dispatch id
				$deldispatchitems = "delete from dispatch_item where dispid = '$_GET[ddispid]'";
				mysqli_query($conn, $deldispatchitems);
				echo '<script>window.location.href="main.php?paction=dispatch_plan&vid=' . $vid . '&msg=Dispatch Slip Deleted Successfully"</script>';
			}
		}
	}

	$vsql = "SELECT gate.gid,gate.tokenid, gate.cid,gate.initialweight, gate.initialweightslip, gate.vehicleno, gate.vehicletype, gate.transport, gate.drivername, gate.drivermobile, gate.efrom, gate.gatestatus, gate.chkinon, gate.chkinby,
    	gate.dslip, gate.chkouton, gate.chkoutby, gate.createdon, gate.createdby, gate.modifiedon, gate.modifiedby, vehicletype.vtname FROM gate
	    INNER JOIN vehicletype ON vehicletype.vtid = gate.vehicletype where gid=$vid
    ";
	$vsqlq = mysqli_query($conn, $vsql) or die(mysqli_error($conn));
	$vew = mysqli_fetch_assoc($vsqlq);

	if (isset($_GET['dispcm']) && !empty($_GET['dispcm']) && is_numeric($_GET['dispcm']) && $_GET['dispcm'] == '1') {
		$updategate = "update gate set dispatchmarkedcompleted='1' where gid='$vid'";
		$updategateqq = mysqli_query($conn, $updategate);
		echo '<script>window.location.href="main.php?paction=dispatch_plan&vid=' . $vid . '&msg=All Dispatch Completed Successfully."</script>';
	}

	if ($_POST['doAction'] == 'finalvalues') {
		//var_Dump($_POST);
		$loadedweight = (float)$_POST['loadedweight1'];
		$saleodrid = $_POST['saleorderid']; // array
		$saleodritem = $_POST['saleorderitem']; // array
		$dispatched = $_POST['dispatched']; // array
		$dispitemid = $_POST['dispitemid']; // array
		$actualWeight = $_POST['actualWeight']; // array
		$actualpcs = $_POST['actualpcs']; //array
		$dispatchBundles = $_POST['dispatchBundles']; // array
		$dispatchPcs = $_POST['dispatchPcs'];
		$actualweightsum = (float)array_sum($actualWeight);
		$cashdiscount = mysqli_real_escape_string($conn, $_POST['cashdiscount']);
		$laborchr = mysqli_real_escape_string($conn, $_POST['laborchr']);
		$otherchr = mysqli_real_escape_string($conn, $_POST['otherchr']);
		$tcs = mysqli_real_escape_string($conn, $_POST['tcs']);


		// var_Dump(round($loadedweight,3));
		// var_Dump(round($actualweightsum,3));
		if (round($loadedweight, 3) == round($actualweightsum, 3)) {
			//echo 'success';
			//echo "newstage";
			$updatedisid = "UPDATE dispatch set cashdiscount='$cashdiscount',
                laborchr='$laborchr',
                otherchr='$otherchr',
                tcs='$tcs' where dispatchid='$_POST[dispatchid]'
            ";
			//echo $updatedisid;
			mysqli_query($conn, $updatedisid);
			for ($f = 0; $f < count($dispitemid); $f++) {
				$dispid = $dispitemid[$f];
				$finalweight = $actualWeight[$f];
				$finalBundles = $dispatchBundles[$f];
				$fianlpcs = $dispatchPcs[$f];
				$actualpc = $actualpcs[$f];
				$bundleweight = $bundleweight[$f];
				if (!empty($finalweight)) {
					$updatedisp = "UPDATE dispatch_item set finalweight='" . ($finalweight / 1000) . "',actualpcs='$actualpc',dispatchedon='$createdon'";

					if (empty($bundleweight)) {
						$updatedisp .= ", bundleweight='0'";
					} else {
						$updatedisp .= ", bundleweight='1'";
					}
					$updatedisp .= " where dispitemid='$dispid'";
					//echo $updatedisp.'<br><br><br>';
					mysqli_query($conn, $updatedisp);
					$newdis = ($dispatched[$f] + ($finalweight / 1000));

					$updatesaleitem = "UPDATE saleordersizes set dispatched='$newdis' where sosaleid='$saleodrid[$f]' and sosize='$saleodritem[$f]'";
					mysqli_query($conn, $updatesaleitem);

					$current_sizestock_sql = "SELECT saleordersizes.sosize, sizes.currentstock, sizes.sid FROM saleordersizes 
                        INNER JOIN sizes ON sizes.sid = saleordersizes.sosizeid 
                        where sosize =$saleodritem[$f] 
                    ";
                    $current_sizestock_qq = mysqli_query($conn, $current_sizestock_sql);
                    $cstock = mysqli_fetch_assoc($current_sizestock_qq);

                    $newstock = ($cstock['currentstock'] - ($finalweight / 1000));
                    $updatestock = "UPDATE sizes set currentstock='$newstock' where sizes.sid = $cstock[sid]";
					mysqli_query($conn, $updatestock);
				} else {
					//only delete id dispatch item qty is 0 or empty
					$deldisp = "DELETE from dispatch_item where dispitemid='$dispid'";
					//echo $deldisp.'<br><br><br>';
					mysqli_query($conn, $deldisp);
				}
				// $_POST = array();
			}
            echo '<script>window.location.href=main.php?paction=dispatch_plan&vid=' . $vid . 'msg=Dispacth slip Completed."</script>';
            die();
		} else {
			//echo 'fail';
			echo '<script>window.location.href="main.php?paction=dispatch_plan&vid=' . $vid . '&errmsg=Actual Weight Total Inserted Is Not Equal To Total Weight."</script>';
		}
	}
} else {
	echo '<script>window.location.href="main.php"</script>';
}

if ($_GET['msg']) {
	$msg = $_GET['msg'];
}
if ($_GET['errmsg']) {
	$errmsg = $_GET['errmsg'];
}
?>
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
			<h1 class="page-heading h6 ebold">Dispatch Plan</h1>
			<ul class="list-inline breadcrumb d-none d-md-flex">
				<li class="breadcrumb-item">Home</li>
				<li class="breadcrumb-item">Admin</li>
				<li class="breadcrumb-item">Dispatch Plan</li>
			</ul>
		</div>
		<div class="article">
			<div class="article-heading flex-heading">
				<h5 class="text-center">Dispatch Plan</h5>
				<!-- only if first weight is done and last weight is not down only then allow slip to create -->
				<?php
				#if($wrows=='0' && $wwcnt>0){
				if (!empty($vew['initialweight'])) {
					$alldispatchedsql = "SELECT * from dispatch_item where vid=$vid and finalweight is null";
					$alldispatchedqq = mysqli_query($conn, $alldispatchedsql);
					$dispcnt = mysqli_num_rows($alldispatchedqq);

					$dispcntsql2 = "SELECT * from dispatch where vehicleid=$vid";
					$disp2qq = mysqli_query($conn, $dispcntsql2);
					$disp2 = mysqli_num_rows($disp2qq);

					$dis3 = "select dispatchmarkedcompleted from gate where gid=$vid";
					$dis3qq = mysqli_query($conn, $dis3);
					$dis3rw = mysqli_fetch_assoc($dis3qq)['dispatchmarkedcompleted'];

					if (empty($dis3rw)) {
						if ($dis3rw == '0') {
				?>
							<a href="main.php?paction=dispatch_slip_add&vid=<?php echo $vid; ?>" class="btn btn-basic btn-sm">New Dispatch Slip</a>
				<?php
						}
					}
				}
				?>
			</div>
			<div class="article-content container-max">
				<div class="table-responsive">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th>Token No.</th>
								<th>Vehicle No.</th>
								<th>Entry Type</th>
								<th>Vehicle Type</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo $vew['tokenid']; ?></td>
								<td><?php echo $vew['vehicleno']; ?></td>
								<td>Material Out</td>
								<td><?php echo $vew['vtname']; ?></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="text-right">
					<?php
					echo $disp2;
					if ($disp2 > '0') {
						$gstatsql = "SELECT dispatchmarkedcompleted from gate where gid=$vid";
						$gsatqq = mysqli_query($conn, $gstatsql);
						$gstat = mysqli_fetch_assoc($gsatqq)['dispatchmarkedcompleted'];
						// var_Dump($dispcnt);
						// var_dump($gstat);
						if ($dispcnt == '0') {
							if ($gstat == '0') {
					?>
								<a href="main.php?paction=dispatch_plan&vid=<?php echo $vid; ?>&dispcm=1" class="btn btn-basic btn-sm">Dispatch Complete</a>
					<?php
							}
						}
					}
					?>
				</div>
			</div>
		</div>
		<?php

		// get list of dispatch planned for vechile token
		$vtsql = "SELECT * from dispatch where vehicleid=$vid";
		$vtqq = mysqli_query($conn, $vtsql);
		if (mysqli_num_rows($vtqq) > 0) {
			while ($vtrw = mysqli_fetch_assoc($vtqq)) {
				?>
				<div class="article mt-4">
					<div class="article-heading d-block d-md-flex flex-heading">
						<h6 class="bold mb-0">
							Dispatch List No. <?php echo $vtrw['dispatchid']; ?>
							<?php
							if (empty($vtrw['weight'])) {
								?>
								<a href="main.php?paction=dispatch_slip_edit&vid=<?php echo $vid; ?>&custid=<?php echo $vtrw['custid']; ?>&dispid=<?php echo $vtrw['dispatchid']; ?>" class="btn action-btn btn-primary ml-3 no-print"><i class="bi bi-pencil-square"></i></a>
								<a href="main.php?paction=dispatch_slip_print&vid=<?php echo $vid; ?>&custid=<?php echo $vtrw['custid']; ?>&dispid=<?php echo $vtrw['dispatchid']; ?>" class="btn action-btn btn-success no-print"><i class="bi bi-printer"></i></a>
								<a href="main.php?paction=dispatch_plan&&vid=<?php echo $vid; ?>&custid=<?php echo $vtrw['custid']; ?>&ddispid=<?php echo $vtrw['dispatchid']; ?>" class="btn action-btn btn-danger no-print"><i class="bi bi-trash"></i></a>
								<?php
							} else {
								?>
								<a href="main.php?paction=dispatch_slip_print_customer&vid=<?php echo $vid; ?>&custid=<?php echo $vtrw['custid']; ?>&dispid=<?php echo $vtrw['dispatchid']; ?>" class="btn action-btn btn-success no-print"><i class="bi bi-printer"></i></a>
								<?php
							}
							?>
						</h6>
						<h6 class="bold mb-0">Total Weight: <?php echo ($vtrw['weight'] * 1000); ?> Kg </h6>
					</div>

					<form action="" method="post" autocomplete="off" id="finalSubmit">
						<input type="hidden" name="doAction" value="finalvalues">
						<input type="hidden" name="paction" value="dispatch_plan">
						<input type="hidden" name="vid" value="<?php echo $vid; ?>">
						<input type="hidden" name="loadedweight1" class="materialWeight" value="<?php echo ($vtrw['weight'] * 1000); ?>"> <!-- difference of empty and loaded weight for current dispatchlist -->
						<div class="article-content container-max">
							<h6 class="bold mb-3">Paty Name :
								<?php $csql = "select name from customers where cust_id='$vtrw[custid]' limit 1";
								// echo $csql;
								$cqq = mysqli_query($conn, $csql);
								$crw = mysqli_fetch_assoc($cqq);
								echo ucwords($crw['name']);
								//var_Dump($crw);
								?>
							</h6>
							<div class="table-responsive">
								<table class="table table-bordered table-xl">
									<thead>
										<tr>
											<th rowspan="2">Order No.</th>
											<th rowspan="2">Product</th>
											<th rowspan="2"> Size/Dia(mm)</th>
											<th rowspan="2">Grade</th>
											<th rowspan="2">Brand</th>
											<th rowspan="2">Price(per Ton)</th>
											<th class="text-center" colspan="2">Planned</th>
											<th class="text-center" colspan="2">Actual</th>
										</tr>
										<tr>
											<th class="text-right">Weight(Tons)</th>
											<th class="text-right">Bundle/Pcs</th>
											<th class="text-right">Weight(Tons)</th>
											<th class="text-right">Bundle/Pcs</th>
										</tr>
									</thead>
									<tbody>
										<?php
										$query1 = "SELECT
								dispatch_item.dispitemid,
								dispatch_item.dispid,
								dispatch_item.vid,
								dispatch_item.customer,
								dispatch_item.slid,
								dispatch_item.sopid,
								dispatch_item.sosize,
								dispatch_item.prodid,
								dispatch_item.gradeid,
								dispatch_item.sizeid,
								dispatch_item.brandid,
								dispatch_item.weighttodispatch,
								dispatch_item.pctodispatch,
								dispatch_item.actualweight,
								dispatch_item.actualpcs,
								dispatch_item.finalweighttodispatch,
								dispatch_item.finalweight,
								dispatch_item.finalbundles,
								dispatch_item.finalpcs,
								dispatch_item.finalpctodispatch,
								dispatch_item.completed,
								dispatch_item.createdon,
								dispatch_item.createdby,dispatch_item.bundleweight as isbundle,
								grade.grade,
								brands.brandname,
								products.productname,
								sizes.size,
								sizes.mtweight,
								sizes.ftweight,
								sizes.weighttype,
								sizes.stdlength,
								sizes.lengthtype,
								sizes.bundleweight,
								sizes.currentstock,
								saleordersizes.soprice
								FROM
								dispatch_item
								INNER JOIN grade ON grade.gid = dispatch_item.gradeid
								INNER JOIN brands ON brands.brid = dispatch_item.brandid
								INNER JOIN products ON products.prid = dispatch_item.sopid
								INNER JOIN sizes ON sizes.sid = dispatch_item.sizeid
								INNER JOIN saleordersizes ON saleordersizes.sosize = dispatch_item.sosize
								where dispid = $vtrw[dispatchid] and vid=$_GET[vid] and customer=$vtrw[custid]";
										//print_r($query1);
										//$disqq = mysqli_query($conn, $dissql);
										$totaltod = 0;

										$qq = mysqli_query($conn, $query1);
										$re = mysqli_num_rows($qq);

										if ($re > 0) {
											$pwsum = 0; // panned weight sum
											$pcsum = 0; // planned pc sum
											$bundlesum = 0; // qty planned bundle sum
											$bundelpcssum = 0; // qty planned pcs in bundle sum
											$disbundlesum = 0; // qty dispatched bundle sum
											$disbundelpcssum = 0; // qty dispatched pcs in bundle sum
											$status = 0;
											$actualweightsum2 = 0;
											while ($rew =  mysqli_fetch_assoc($qq)) {
												$tsum += $rew['weighttodispatch'];
												?>
												<tr>
													<td>
														<?php echo $rew['slid']; ?>
														<input type="hidden" name="dispatchid" value="<?php echo $vtrw['dispatchid'] ?>"><!-- dispatch id -->
														<input type="hidden" name="dispitemid[]" value="<?php echo $rew['dispitemid']; ?>"><!-- dispatch item table row id -->
														<input type="hidden" name="saleorderid[]" value="<?php echo $rew['slid']; ?>">
														<input type="hidden" name="saleorderitem[]" value="<?php echo $rew['sosize']; ?>"><!-- saleordersizes table row id-->
														<input type="hidden" name="dispatched[]" value="<?php echo $rew['dispatched']; ?>">
													</td>
													<td><?php echo $rew['productname']; ?></td>
													<td><?php echo $rew['size']; ?></td>
													<td><?php echo $rew['grade']; ?></td>
													<td><?php echo (!empty($rew['brandname']) ? $rew['brandname'] : '-'); ?></td>
													<td><?php echo $rew['soprice']; ?></td>
													<td class="text-right">
														<?php echo ($rew['weighttodispatch'] * 1000);
														$pwsum += ($rew['weighttodispatch'] * 1000); ?> Kg.
													</td>
													<td class="text-right">
														<?php
														echo $rew['pctodispatch'];
														$pcsum += $rew['pctodispatch'];
														if ($rew['bundleweight'] == '0') {
															echo ' Pcs.';
														} else {
															echo ' Bundles';
														}
														?>
													</td>
													<td class="text-right" style="width: 9rem">
														<?php
														if (empty($rew['finalweight'])) {
															?>
															<input type="number" min="0" step="0.001" name="actualWeight[]" class="form-control actualWeight" <?php echo (empty($vtrw['weight']) ? 'disabled' : ''); ?>>
															<input type="hidden" name="sizeid[]" class="sizeid" value="<?php echo $rew['sizeid']; ?>"> <!-- sizes table row id -->
															<input type="hidden" name="sosize[]" class="sosize" value="<?php echo $rew['sosize']; ?>"><!-- saleordersizes table row id-->
															<?php
														} else {
															$status = 1;
															echo ($rew['finalweight'] * 1000) . ' Kgs';
															$actualweightsum2 += $rew['finalweight'];
														}
														$a = wtpcconvert($rew['stdlength'], $rew['lengthtype'], $rew['mtweight'], $rew['ftweight'], $rew['finalweight'], 1, $rew['bundleweight']);
														?>
													</td>
													<td class="pieces" style="width: 8rem; text-align:right" class="text-right">
														<?php
														if (empty($rew['finalweight'])) { ?>
															<input type="number" step="0.01" name="actualpcs[]" class="form-control actualpcs" value="<?php echo round($a[0], 2); ?>" <?php echo (empty($vtrw['weight']) ? 'disabled' : ''); ?> required>
															<input type="hidden" name="bundleweight[]" value="<?php echo $rew['bundleweight']; ?>">
														<?php
														} else {
															echo round($a[0], 2);
														}
														?>
													</td>
												</tr>
										<?php
											}
										}
										?>
										<tr>
											<th colspan="6" class="text-right">Total</th>
											<td colspan="" class="text-right"><?php echo ($pwsum);  ?>Kg.</td>
											<td colspan=""></td>
											<td colspan="" class="text-right">Total: <span class="total"><?php echo ($actualweightsum2 * 1000); ?></span> Kg.<br />Pending: <input type="hidden" name="pending" id="pending"><span class="pending"><?php echo (($vtrw['weight'] - $actualweightsum2) * 1000); ?></span> Kg.</td>
											<td colspan=""></td>
										</tr>
										<tr>
											<td class="text-right" style="width: 8rem" colspan="9">Cash Discount %</td>
											<td class="text-right" style="width: 8rem">
												<?php if (!empty($vtrw['weight']) && !empty($vtrw['cashdiscount'])) {
													echo $vtrw['cashdiscount'];
												} else { ?>
													<input type="number" step="0.01" name="cashdiscount" class="form-control " value="" <?php echo (empty($vtrw['weight']) ? 'disabled' : ''); ?>>
											</td>
										<?php } ?>
										</td>
										</tr>
										<tr>
											<td class="text-right" style="width: 8rem" colspan="9">Labor(Rs) / Tons</td>
											<td class="text-right" style="width: 8rem">
												<?php if (!empty($vtrw['weight']) && !empty($vtrw['laborchr'])) {
													echo $vtrw['laborchr'];
												} else { ?>
													<input type="number" step="0.01" name="laborchr" class="form-control " value="" <?php echo (empty($vtrw['weight']) ? 'disabled' : ''); ?>>
												<?php } ?>
											</td>
										</tr>
										<tr>
											<td class="text-right" style="width: 8rem" colspan="9">Other(Rs)</td>
											<td class="text-right" style="width: 8rem">
												<?php if (!empty($vtrw['weight']) && !empty($vtrw['otherchr'])) {
													echo $vtrw['otherchr'];
												} else { ?>
													<input type="number" step="0.01" name="otherchr" class="form-control " value="" <?php echo (empty($vtrw['weight']) ? 'disabled' : ''); ?>>
												<?php } ?>
											</td>
										</tr>
										<tr>
											<td class="text-right" style="width: 8rem" colspan="9">TCS(Rs)</td>
											<td class="text-right" style="width: 8rem">
												<?php
												if (!empty($vtrw['weight']) && !empty($vtrw['tcs'])) {
													echo $vtrw['tcs'];
												} else {
												    ?>
                                                    <input type="number" step="0.01" name="tcs" class="form-control " value="" <?php echo (empty($vtrw['weight']) ? 'disabled' : ''); ?>>
                                                    <?php
                                                }
                                                ?>
										    </td>
										</tr>
									</tbody>
								</table>
							</div>
							<?php
							// var_dump($vtrw['weight']);
							if (!empty($vtrw['weight']) && $status != 1) {
							    ?>
								<div class="mt-2 text-right"><button class="btn btn-basic" name="submit" value="Dispatch Submit" disabled>Dispatch Submit</button></div>
							    <?php
							}
							?>
						</div>
					</form>
				</div>
				<?php
			}
		}
	}
	?>
</div>

<div class="modal fade" id="weighSlipView">
	<div class="modal-dialog modal-dialog-centered modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Weighment Slip</h4>
				<button type="button" class="close" data-dismiss="modal"><i class="bi bi-x"></i></button>
			</div>
			<div class="modal-body text-center">
				<img class="img-fluid" src="assets/images/vital-steel-bars-llp-logo.png">
			</div>
		</div>
	</div>
</div>

<script>
	$(document).on('click', '.weighSlipView', function(e) {
		e.preventDefault();
		let src = $(this).attr('href');
		let slipViewModal = $('#weighSlipView');
		slipViewModal.find('img').attr('src', src);
		slipViewModal.modal();
	});

	$(document).on('change', '.actualWeight', function(e) {
		e.preventDefault();
		let awght = parseFloat($(this).val(), 2) / 1000;
		console.log(awght);
		let sizid = $(this).siblings('.sizeid').val();
		let that = $(this);
		let tr = that.closest('tr');
		if (awght <= 0 || awght == '') {
			//tr.find('.fsizes').text('');
			tr.find('.actualpcs').val('');
		} else {
			$.ajax({
				type: "post",
				url: "Ajax.php",
				data: {
					doAction: 'getpcsfromsizeidweight',
					disizeid: sizid,
					disweight: awght
				},
				success: function(data) {
					//that.siblings('.sizeid').val();
					let dd = JSON.parse(data);
					//console.log(dd[0].toFixed(2));
					//tr.find('.fsizes').text(dd[0].toFixed(2));
					tr.find('.actualpcs').val(dd[0].toFixed(2));
					let totalweight = 0.00;
					let actualWeight = tr.closest('tbody').find('.actualWeight');
					actualWeight.each(function() {
						let wt = parseFloat(($(this).val() == '') ? 0 : $(this).val());
						totalweight += wt;
					});
					console.log(totalweight);
					let article = tr.closest('.article');
					let materialWeight = article.find('.materialWeight').val();
					console.log(materialWeight);
					article.find('.total').text(totalweight.toFixed(2));
					let pending = (parseFloat(materialWeight) - totalweight).toFixed(2)
					article.find('.pending').text(pending);
					article.find('#pending').val(pending);
					if (pending < 0) {
						alert('Total Dispatch Weight is above Total Material Weight of this Slip');
						that.val('').trigger('change');
					}
					if (pending < 0 || totalweight == 0) {
						article.find('.btn-basic').prop('disabled', true)
					} else {
						article.find('.btn-basic').prop('disabled', false)
					}
				}
			});
		}
	});
	$(document).on('change', '.actualpcs', function(e) {
		e.preventDefault();
		let apc = $(this).val();
		let that = $(this);
		let tr = that.closest('tr');
		let sizid = $(this).closest('tr').find('.sizeid').val();
		if (apc <= 0 || apc == '') {
			tr.find('.actualWeight').val('');
		} else {
			$.ajax({
				type: "post",
				url: "Ajax.php",
				data: {
					doAction: 'getweightfromsizeidpcs',
					disizeid: sizid,
					dispc: apc
				},
				success: function(data) {
					let dd = JSON.parse(data);
					tr.find('.actualWeight').val((dd[1] * 1000).toFixed(2));
					let totalweight = 0.00;
					let actualWeight = tr.closest('tbody').find('.actualWeight');
					actualWeight.each(function() {
						let wt = parseFloat(($(this).val() == '') ? 0 : $(this).val());
						totalweight += wt;
					});
					let article = tr.closest('.article');
					let materialWeight = article.find('.materialWeight').val();
					article.find('.total').text(totalweight.toFixed(3));
					let pending = (parseFloat(materialWeight) - totalweight).toFixed(3)
					article.find('.pending').text(pending);
					if (pending < 0) {
						alert('Total Dispatch Weight is above Total Material Weight of this Slip');
						tr.find('.actualWeight').val('').trigger('change');
					}
					if (pending < 0 || totalweight == 0) {
						article.find('.btn-basic').prop('disabled', true)
					} else {
						article.find('.btn-basic').prop('disabled', false)
					}
				}
			});
		}
	});
	$('#finalSubmit').on('submit', function() {
		let pending = parseFloat($('#pending').val() == '' ? 0 : $('#pending').val());
		if (pending.toFixed(3) > 0) {
			return false
		}
	})
</script>