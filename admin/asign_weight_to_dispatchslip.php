<?php

$perm=check_permission("A","DP");
//var_dump($_POST);
	$vid = mysqli_real_escape_string($conn, $_POST['vid']); //vehicle id
	if(empty($_POST['custid'])){
	    $custid = mysqli_real_escape_string($conn, $_POST['customer']);
	}else{
	    $custid=mysqli_real_escape_string($conn, $_POST['custid']); //vehicle id
	}
	//$custid=mysqli_real_escape_string($conn, $_POST['custid']); //vehicle id
	$disporder = $_POST['disporder']; // customer sale order array where orders are not completed.	
	if(!empty($_POST['udispid'])){
		$udispid=mysqli_real_escape_string($conn, $_POST['udispid']); //dispatchid id
	}
	if(empty($_POST['vid'])){
		echo '<script>window.location.href="main.php?paction=dispatch_plan"</script>';
	}
	// else if(empty($_POST['custid']) && !empty($_POST['vid'])){
	// 	echo '<script>window.location.href="main.php?paction=dispatch_slip_edit&vid=$vid"</script>';
	// }else if(!empty($_POST['custid']) && !empty($_POST['vid']) && empty($_POST['disporder'])){
	// 	echo '<script>window.location.href="main.php?paction=dispatch_slip_edit&vid=$vid"</script>';
	// }
	
	//var_dump($_POST);
	if(!empty($_POST['udispid'])){
		$udispid=mysqli_real_escape_string($conn, $_POST['udispid']); //dispatchid id
	}
	if($_POST['doAction']=='createdispatchslip'){	
		$insdissql ="insert into dispatch set vehicleid='".$vid."', custid='".$custid."',createdon='$createdon',createdby='$createdby'";
		$insdisqq=mysqli_query($conn,$insdissql);
		$dispatchnewid=mysqli_insert_id($conn);
		if($dispatchnewid>0){
			$slid = $_POST['slid']; // array
			$sosize = $_POST['sosize']; //array
			$sopid = $_POST['sopid'];
			$gradeid = $_POST['gradeid'];
			$sizeid = $_POST['sizeid'];
			$brandid = $_POST['brandid'];
			$todispatch = $_POST['todispatch'];
			$todispatchpcs=$_POST['todispatchpcs'];
			for($x=0;$x<count($slid);$x++){
				if($todispatch[$x]!='0' && !empty($todispatch[$x])){
					$insertditem = "insert into dispatch_item set
					vid='$vid',
					customer = '$custid',
					slid = '$slid[$x]',
					sopid = '$sopid[$x]',
					sosize = '$sosize[$x]',
					gradeid = '$gradeid[$x]',
					sizeid = '$sizeid[$x]',
					brandid = '$brandid[$x]',
					dispid = '$dispatchnewid',
					weighttodispatch = '$todispatch[$x]',
					pctodispatch = '$todispatchpcs[$x]',
					createdon = '$createdon',
					createdby ='$createdby'";
					// echo $insertditem;
					// echo '<br><br><br>';
					mysqli_query($conn,$insertditem);
					$nd=mysqli_insert_id($conn);
				}
			}
			echo '<script>window.location.href="main.php?paction=dispatch_plan&vid='.$vid.'&msg=New Dispatch Slip Created Successfully."</script>';
		}
	}else if($_POST['doAction']=='updatedispatchslip'){
			$vid = $_POST['vid']; //
			$custid = $_POST['custid']; //
			$udispid = $_POST['udispid']; // dispatchid
			$slid = $_POST['slid']; // array
			$dispatchitemid = $_POST['dispatchitemid']; //array dispatched item id only if record is for updated for insert it will be empty
			$sosize = $_POST['sosize']; //array
			$sopid = $_POST['sopid']; // product id
			$gradeid = $_POST['gradeid']; // gradeid
			$brandid = $_POST['brandid']; // brandid
			$sizeid = $_POST['sizeid']; // size id 
			$todispatch = $_POST['todispatch']; // weight to dispatch
			$todispatchpcs=$_POST['todispatchpcs'];// pcs/bundle to dispath
			$er=0;
			//delete all dispatch items from dispatch id
			$deldispitems="delete from dispatch_item where dispid='$udispid'";
			mysqli_query($conn,$deldispitems);
			// again create all dispatch items with new dispatch item details
			for($y=0;$y<count($todispatch);$y++){
				if((!empty($todispatch[$y]) || $todispatch[$y]!=0) && (!empty($todispatchpcs[$y]) || $todispatchpcs[$y]!=0)){
					$insertditem = "insert into dispatch_item set
						vid='$vid',
						customer = '$custid',
						slid = '$slid[$y]',
						sopid = '$sopid[$y]',
						sosize = '$sosize[$y]',
						gradeid = '$gradeid[$y]',
						sizeid = '$sizeid[$y]',
						brandid = '$brandid[$y]',
						dispid = '$udispid',
						weighttodispatch = '$todispatch[$y]',
						pctodispatch = '$todispatchpcs[$y]',
						createdon = '$createdon',
						createdby ='$createdby'";
						mysqli_query($conn,$insertditem);
						// echo $insertditem;
						// echo '<br><br><br>';
					// if(empty($dispatchitemid[$y])){
					// 	//insert
					// 	$insertditem = "insert into dispatch_item set
					// 	vid='$vid',
					// 	customer = '$custid',
					// 	slid = '$slid[$y]',
					// 	sopid = '$sopid[$y]',
					// 	sosize = '$sosize[$y]',
					// 	gradeid = '$gradeid[$y]',
					// 	sizeid = '$sizeid[$y]',
					// 	brandid = '$brandid[$y]',
					// 	dispid = '$udispid',
					// 	weighttodispatch = '$todispatch[$y]',
					// 	pctodispatch = '$todispatchpcs[$y]',
					// 	createdon = '$createdon',
					// 	createdby ='$createdby'";
					// 	echo $insertditem;
					// 	echo '<br><br><br>';
					// 	//mysqli_query($conn,$insertditem);
					// }else{
					// 	//update
					// 	$updateditem = "update dispatch_item set
					// 	vid='$vid',
					// 	customer = '$custid',
					// 	slid = '$slid[$y]',
					// 	sopid = '$sopid[$y]',
					// 	sosize = '$sosize[$y]',
					// 	gradeid = '$gradeid[$y]',
					// 	sizeid = '$sizeid[$y]',
					// 	brandid = '$brandid[$y]',
					// 	dispid = '$udispid',
					// 	weighttodispatch = '$todispatch[$y]',
					// 	pctodispatch = '$todispatchpcs[$y]',
					// 	modifiedon = '$createdon',
					// 	modifiedby ='$createdby' where dispitemid='$dispatchitemid[$y]'";
					// 	//mysqli_query($conn,$updateditem);
					// 	echo $updateditem;
					// 	echo '<br><br><br>';
					// }
				}else{
					$er = $er+1;
				}
			}
			if($er>0){
				echo '<script>window.location.href="main.php?paction=dispatch_plan&vid='.$vid.'&msg=Dispatch Slip updated Successfully. But items dispatched with 0 weight or Pcs are not saved."</script>';
			}else{
				echo '<script>window.location.href="main.php?paction=dispatch_plan&vid='.$vid.'&msg=Dispatch Slip updated Successfully."</script>';
			}
			
	}

		//get details of the vechile id
		$vsql = "select * from gate where gid=$vid";
		$vsql = "SELECT gate.gid, gate.tokenid, gate.vehicleno, customers.`name` FROM gate
		INNER JOIN customers ON customers.cust_id = gate.cid where gid='$vid' ";
		$vsqlq=mysqli_query($conn,$vsql) or die(mysqli_error($conn));
		$vew = mysqli_fetch_assoc($vsqlq);
		
		$v2sql = "SELECT dispatch.dispatchid, dispatch.vehicleid, dispatch.custid from dispatch where vehicleid=$vid and weight is null limit 1";
		//echo $v2sql;
		echo '<br><br><br>';
		$v2qq = mysqli_query($conn,$v2sql);
		$v2rw = mysqli_fetch_assoc($v2qq);
	if($_GET['msg']){
		$msg=$_GET['msg'];
	}
	if($_GET['errmsg']){
		$errmsg=$_GET['errmsg'];
	}
?>
<link href="assets/css/print.css" type="text/css" rel="stylesheet"></link>
<div class="main-content-inner">
	<?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
	<?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
	<?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
	<?php
	if($perm){
	?>
	<div class="page-header no-print">
		<h1 class="page-heading h6 ebold">Add Dispatch Slip</h1>
		<ul class="list-inline breadcrumb d-none d-md-flex">
			<li class="breadcrumb-item">Home</li>
			<li class="breadcrumb-item">Admin</li>
			<li class="breadcrumb-item">Add Dispatch Slip</li>
		</ul>
	</div>
	<div class="article">
		<div class="article-heading flex-heading">
			<h5 class="text-center">Add Dispatch Slip</h5>
		</div>
		<div class="article-content container-max">
			<div class="table-responsive">
				<table class="table table-bordered">
					<thead>
						<tr>
							<th>Token No.</th>
							<th>Vehicle No.</th>
							<th>Entry Type</th>
							<th>Party Name</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $vew['tokenid']; ?></td>
							<td><?php echo strtoupper($vew['vehicleno']); ?></td>
							<td>Material Out</td>
							<td><?php echo ucwords($vew['name']); ?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
	<div class="article mt-4">
		<div class="article-heading d-block d-md-flex flex-heading">
			<h6 class="bold mb-0">Add Items To Dispatch List</h6>
		</div>
		<div class="article-content container-max">
			<form method="post" action="">
				<input type="hidden" name="doAction" value="<?php echo (empty($udispid)?'createdispatchslip':'updatedispatchslip');?>">
				<input type="hidden" name="vid" value="<?php echo $vid;?>" >
				<input type="hidden" name="custid" value="<?php echo $custid;?>" >
				<input type="hidden" name="udispid" value="<?php echo $udispid;?>">
				<div class="table-responsive">
					<table class="table table-bordered table-large">
						<thead>
							<tr>
								<th rowspan="2">Order No.</th>
								<th rowspan="2">Product</th>
								<th rowspan="2"> Size/Dia(mm)</th>
								<th rowspan="2">Grade</th>
								<th rowspan="2">Brand</th>
								<th class="text-center" colspan="2">Pending</th>
								<th class="text-center" colspan="2">In Stock</th>
								<th class="text-center" colspan="2">To Dispatch</th>
								<th rowspan="2" class="text-center">Rate/Ton</th>
							</tr>
							<tr>
								<th class="text-right">Weight(Tons)</th>
								<th class="text-right">Bundle/Pcs</th>
								<th class="text-right">Weight(Tons)</th>
								<th class="text-right">Bundle/Pcs</th>
								<th class="text-right">Weight(Tons)</th>
								<th class="text-right">Bundle/Pcs</th>
							</tr>
						</thead>
						<tbody>
							<?php
							//if(!empty($_POST['custid'])){
							if(!empty($custid)){
								foreach($disporder as $dispodr){
								$query = "SELECT
								saleorder.slid,
								saleorder.cid,
								saleorder.cperson,
								saleorder.`status`,
								saleordersizes.sosize,
								saleordersizes.soprid,
								saleordersizes.sogradeid,
								saleordersizes.soitemid,
								saleordersizes.sobrandid,
								saleordersizes.sosizeid,
								saleordersizes.soprice,
								saleordersizes.qty,
								saleordersizes.qtytype,
								saleordersizes.weightintons,
								saleordersizes.dispatched,
								saleordersizes.completed,
								saleordersizes.pcs,
								saleordersizes.todispatchwegith,
								saleordersizes.todispachpcs,
								products.productname,
								grade.grade,
								brands.brandname,
								sizes.size,
								sizes.bundleweight,
								sizes.weighttype,
								sizes.stdlength,
								sizes.lengthtype,
								sizes.ftweight,
								sizes.mtweight,
								sizes.currentstock
								FROM
								saleorder
								INNER JOIN saleordersizes ON saleorder.slid = saleordersizes.sosaleid
								INNER JOIN products ON products.prid = saleordersizes.soprid
								INNER JOIN grade ON grade.gid = saleordersizes.sogradeid
								INNER JOIN brands ON brands.brid = saleordersizes.sobrandid
								INNER JOIN sizes ON sizes.sid = saleordersizes.sosizeid
								where cid='$custid'  and saleordersizes.completed='0' and saleorder.slid='$dispodr'";
								
								$qq = mysqli_query($conn,$query);
								$tpending = 0;
								$totalDispatch = 0;
								while($qrw = mysqli_fetch_assoc($qq)){
									$dispsql2 = "SELECT
									dispatch_item.weighttodispatch,
									dispatch_item.pctodispatch,
									dispatch_item.dispitemid,
									dispatch_item.dispid,
									dispatch_item.sosize
									from dispatch_item
									where dispid='$_POST[udispid]' and sosize='$qrw[sosize]'";
									
									$dispqq2=mysqli_query($conn,$dispsql2);
									$dispcnt2=mysqli_num_rows($dispqq2);
									
									if($dispcnt2>0){
										$disprw2=mysqli_fetch_assoc($dispqq2);
									}
									
									$pending=($qrw['weightintons']-$qrw['dispatched']);
									$disptodaysql = "select * from dispatch_item where slid='$qrw[slid]' and sosize='$qrw[sosize]' and date(createdon)=CURDATE()";
									$disptodayqq=mysqli_query($conn,$disptodaysql);
									$disptodaycnt = mysqli_num_rows($disptodayqq);
									?>
									<tr class="<?php echo ($disptodaycnt>0?' bg-lightorange': ''); ?>">
									<td><?php echo $qrw['slid'];?><input type="hidden" name="slid[]" value="<?php echo $qrw['slid'];?>">
									
										<input type="hidden" name="dispatchitemid[]" value="<?php if($dispcnt2>0){ echo $disprw2['dispitemid'];} ?>">
									
									<input type="hidden" name="sosize[]" value="<?php echo $qrw['sosize'];?>">
									<input type="hidden" name="sopid[]" value="<?php echo $qrw['soprid'];?>">
									<input type="hidden" name="gradeid[]" value="<?php echo $qrw['sogradeid'];?>">
									<input type="hidden" name="brandid[]" value="<?php echo $qrw['sobrandid'];?>"></td>
									<td><?php echo $qrw['productname'];?></td>
									<td><?php echo $qrw['size'];?> <input type="hidden" name="sizeid[]" value="<?php echo $qrw['sosizeid'];?>"></td>
									<td><?php echo $qrw['grade'];?></td>
									<td><?php echo $qrw['brandname'];?></td>
									<td class="text-right"><?php echo $pending;  $tpending +=$pending; ?> Tons</td>
									<td class="text-right">
									<?php 
										$res = wtpcconvert($qrw['stdlength'],$qrw['lengthtype'],$qrw['mtweight'],$qrw['ftweight'],$pending,1,$qrw['bundleweight']);
										echo $res[0];
										if(!empty($qrw['bundleweight'])){
											echo ' Bundle';
										}else{
											echo " Pcs.";
										}
									?>
									</td>
									<td class="text-right"><?php echo $qrw['currentstock']; ?> Tons</td>
									<td class="text-right">
									<?php 
										$res1 = wtpcconvert($qrw['stdlength'],$qrw['lengthtype'],$qrw['mtweight'],$qrw['ftweight'],$qrw['currentstock'],1,$qrw['bundleweight']);
										echo $res1[0];
										if(!empty($qrw['bundleweight'])){
											echo ' Bundle';
										}else{
											echo " Pcs.";
										}
									?>
										</td>
										<td class="text-right" style="width: 7rem">
										<input type="number" step="0.001" class="form-control todispatch" max="<?php echo ($pending<'0'?'0':$pending); ?>" data-bundleweight="<?php echo $qrw['bundleweight']; ?>" data-ftweight="<?php echo $qrw['ftweight']; ?>" data-mtweight="<?php echo $qrw['mtweight']; ?>" data-lengthtype="<?php echo $qrw['lengthtype']; ?>" data-stdlength="<?php echo $qrw['stdlength']; ?>" name="todispatch[]" value="<?php
										if(!empty($disprw2) && $qrw['sosize']==$disprw2['sosize']){ 
											$todispatch =  $disprw2['weighttodispatch'];
										}else{
											$todispatch =  $pending;
										}
										$totalDispatch += ($todispatch < 0?'0':$todispatch);
										echo ($todispatch < 0?'0':$todispatch);
										?>"></td>
										<td class="text-right"><span class="newpcs"><?php 
										$res2 = wtpcconvert($qrw['stdlength'],$qrw['lengthtype'],$qrw['mtweight'],$qrw['ftweight'],$pending,1,$qrw['bundleweight']);
										if(!empty($disprw2) && $qrw['sosize']==$disprw2['sosize']){
											echo $disprw2['pctodispatch'];
									   }else{
										   echo $res2[0];
										   } 
										if(!empty($qrw['bundleweight'])){
											echo ' Bundle';
										}else{
											echo " Pcs.";
										}
										//echo $qrw['sosize'].'-'.$disprw2['sosize'];
									?></span>
											<input type="hidden" name="todispatchpcs[]" class="todispatchpcs" value="<?php 
											if(!empty($disprw2) && $qrw['sosize']==$disprw2['sosize']){
												 echo $disprw2['pctodispatch'];
											}else{
												echo $res2[0];
												} 
											 ?>">
										</td>
									<td class="text-right"> <?php echo (empty($qrw['soprice'])?'N/A':'<i class="fa fa-rupee-sign fa-small"></i>'.$qrw['soprice']); ?></td>
								</tr>
						<?php } ?>
							
							<?php } 
							} //foreach loop?>
							<tr>
							<th colspan="5" class="text-right"></th>
							<th colspan="" class="text-right"><?php echo $tpending; ?></th>
								<th colspan="3" class="text-right">Total</th>
								<td colspan="" class="text-right" ><span class="totalWeight"><?php echo $totalDispatch; ?></span> Tons</td>
								<td colspan=""></td>
								<td colspan="" class="text-right"></td>
							</tr>
						</tbody>
					</table>
					
				</div>
				<div class="text-right">

					<input type="submit" name="submit" class="btn btn-basic">
				</div>
			</form>
		</div>
	</div>
	<?php } ?>
</div>
<script>
	$(document).on('click', '.weighSlipView', function(e){
		e.preventDefault();
		let src = $(this).attr('href');
		let slipViewModal = $('#weighSlipView');
		slipViewModal.find('img').attr('src', src);
		slipViewModal.modal();
	});
	$('.todispatch').on()
	
	$(document).on('change','.todispatch',function(){
		if($(this).val() > parseFloat($(this).data('max'))){
			alert(`Can't dispatch more than pending weight`);
			$(this).val('');
		}else{
			var idd=$(this).data('idd');
			let that = $(this);
			let weight =that.val();
			let stdlength = $(this).data('stdlength');
			let lengthtype = $(this).data('lengthtype');
			let mtweight = $(this).data('mtweight');
			let ftweight = $(this).data('ftweight');
			let bundleweight = $(this).data('bundleweight');

			let tr = $(this).closest('tr');
			let qtytype = 1;
			if(weight !='' && weight !=0){
				$.ajax({
					type:"post",
					url:"Ajax.php",
					data:{dispatchweight:weight,stdlength:stdlength,lengthtype:lengthtype,mtweight:mtweight,ftweight:ftweight,qtytype:qtytype,bundleweight:bundleweight,doAction:'calculatepcs'},
					success:function(data){
						let sdi = JSON.parse(data);
						let tr = that.closest('tr');
						let type='';
						if(bundleweight !='' && bundleweight!=null){
							type=' Bundles';
						}else{
							type=' Pcs';
						}
						tr.find(".newpcs").text(sdi[0].toFixed(2)+type);
						tr.find(".todispatchpcs").val(sdi[0].toFixed(2));
					}
				});
			}else{
				tr.find(".newpcs").text('')
				tr.find(".todispatchpcs").val('')
				
			}
		}
		let count = 0;
		$('.todispatch').each(function(){
			let value = parseFloat($(this).val());
			count += value
		});
		$('.totalWeight').text(count.toFixed(3));
	});
	$('.pccal2').change(function(){
		let that = $(this);
		pcc = that.val();
		console.log('pccal2:'+pcc);
		let tr = that.closest('tr');
		if(pcc>'0' && pcc !='0'){
			tr.find(".additem").removeAttr("disabled");
		}else{
			tr.find(".additem").prop( "disabled", true);
		}

	});
</script>
