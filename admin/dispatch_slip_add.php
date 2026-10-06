<?php

$perm=check_permission("A","DP");
	$vid = $_GET['vid'];
	
	if(isset($_GET['vid']) && !empty($_GET['vid']) && is_numeric($_GET['vid'])){
		#if(isset($_GET['custid']) && !empty($_GET['custid']) && is_numeric($_GET['custid'])){
			$custid = mysqli_real_escape_string($conn, $_GET['custid']); //dispatch id
				

			#if(isset($_GET['dispid']) && !empty($_GET['dispid']) && is_numeric($_GET['dispid'])){
				$disid = mysqli_real_escape_string($conn, $_GET['dispid']); //dispatch id
				
			#}
		#}	
		$vstatus="select custid from dispatch where vehicleid='$vid' and weight is NULL or weight='0' limit 1";
		$vstatusqq = mysqli_query($conn,$vstatus);
		$res = mysqli_fetch_assoc($vstatusqq);
		//var_dump($res);var_dump($custid);
		$res1=(empty($res['custid'])? $custid:$res['custid']);

		if(!empty($res1)){
			$custordersql = "select slid from saleorder where cid='$res1' and status<>'1'";
		    $custorderqq = mysqli_query($conn,$custordersql);
		}
		
		
		//get details of the vechile id
		$vsql = "SELECT gate.gid, gate.tokenid, gate.vehicleno, customers.`name` FROM gate
		INNER JOIN customers ON customers.cust_id = gate.cid where gid='$vid' ";
		$vsqlq=mysqli_query($conn,$vsql) or die(mysqli_error($conn));
		$vew = mysqli_fetch_assoc($vsqlq);
		
		$v2sql = "SELECT dispatch.dispatchid, dispatch.vehicleid, dispatch.custid from dispatch where vehicleid=$vid and weight is null limit 1";
		$v2qq = mysqli_query($conn,$v2sql);
		$v2rw = mysqli_fetch_assoc($v2qq);
		// echo '<script>window.location.href="main.php?paction=dispatch_slip_add&vid='.$vid.'&custid='.$v2rw['custid'].'&dispid='.$v2rw['dispatchid'].'"</script>';
		//}else{
		//	echo '<script>window.location.href="main.php"</script>';
			
	 }else{
		echo '<script>window.location.href="main.php"</script>';
	 }
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
			<h6 class="bold mb-0">Customer Wise Pending Orders</h6>
		</div>
		<form method="post" action="main.php?paction=asignweighttodispatchslip">
			<input type="hidden" name="vid" value="<?php echo $vid;?>">
			<input type="hidden" name="custid" value="<?php echo $custid;?>">
			<input type="hidden" name="doAction" value="newdisp">
			<div class="article-content container-max">
				<div class="row">
					<div class="col-xl-4 col-md-5 col-sm-6">
						<div class="form-group">
							
							<label for="customer">Select Party</label>
								<?php #echo $res1;
								if(!empty($res1) &&  $res1!='0'){
									echo '<input type="hidden" name="custid" id="custid" value="'.$res['custid'].'">';
								} ?>
								<select name="customer" id="customer" class="form-control select2me" <?php if(!empty($res['custid']) && $res['custid']!='0'){
									echo ' disabled';
									
								} ?>>
									<option value="">Select From List</option>
									<?php 
										$psql = "select cust_id,name,typ from customers where typ='CU' order by name ASC";
										$pqq=mysqli_query($conn,$psql);
										while($qrw = mysqli_fetch_assoc($pqq)){
											echo '<option value="'.$qrw['cust_id'].'" '.($qrw['cust_id']==$res1?'selected':'').'>'.strtoupper($qrw['name']).'</option>';
										}
									?>
								</select>
						</div>
						
					</div>
					
				</div>
				
				<div class="table-responsive">
					<table class="table table-bordered report">
						<thead>
							<tr>
								<th style="width: 7rem">Order No.</th>
								<th>
									<span class="size">Product / Size</span><br/>
									<span class="pending">Ordered / Pending</span><br/>
									<span class="pending">Rate / Grade</span>
								</th>
							</tr>
						</thead>
						<tbody>
							<?php if(!empty($res1)){ while($custorw = mysqli_fetch_assoc($custorderqq)){ 
								//var_Dump($custorw);?>
							<tr><!-- Loop this with new order -->
								<th><label for="disporderId<?php echo $custorw['slid'];?>"><input type="checkbox" class="mr-2" name="disporder[]" id="disporderId<?php echo $custorw['slid'];?>" value="<?php echo $custorw['slid'];?>"> <?php echo $custorw['slid'];?></label></th>
								<td class="py-0 px-1">
									<div class="container-fluid px-0">
									<?php $querysl = "SELECT saleorder.slid,
													saleorder.`status`,
													saleordersizes.sosize,
													saleordersizes.qty,
													saleordersizes.qtytype,
													saleordersizes.weightintons,
													saleordersizes.dispatched,
													products.productname,
													grade.grade,
													brands.brandname,
													sizes.size,
													saleordersizes.completed,
													saleordersizes.soprice
													FROM saleorder INNER JOIN saleordersizes ON saleorder.slid = saleordersizes.sosaleid 
													INNER JOIN products ON products.prid = saleordersizes.soprid 
													INNER JOIN grade ON grade.gid = saleordersizes.sogradeid 
													INNER JOIN brands ON brands.brid = saleordersizes.sobrandid 
													INNER JOIN sizes ON sizes.sid = saleordersizes.sosizeid
													where saleorder.slid='$custorw[slid]' and saleorder.`status`!='1' and 
													saleordersizes.completed !='1'";
													$queryslqq = mysqli_query($conn,$querysl);
													$quecnt = mysqli_num_rows($queryslqq);
													if($quecnt>0){?>
										<ul class="list-unstyled d-inline-block orderrow">
												<?php
													while($querw = mysqli_fetch_assoc($queryslqq)){
														$disptodaysql = "select * from dispatch_item where slid=$querw[slid] and sosize=$querw[sosize] and date(createdon) = CURDATE()";
														$disptodayqq=mysqli_query($conn,$disptodaysql);
														$disptodaycnt = mysqli_num_rows($disptodayqq);
														?>
											<li><!-- Repeat this with new Item in Order -->
												<div class="reportThumb my-2 mx-2 <?php
												echo ($disptodaycnt>0?' bg-lightorange': '');
												?>"> <!-- if any dispatch from this product has been made today add class "bg-lightgreen" else remove this-->
													<span><?php echo $querw['productname']; ?> / <?php echo ucwords($querw['size']); ?></span>
													<span class="mb-0"><?php echo $querw['qty'];  echo  getorderunits($querw['qtytype']);?> / <?php echo ucwords($querw['dispatched']); ?> Tons</span>
													<span class="mb-0"><i class="fa fa-rupee-sign"></i><?php echo ucwords($querw['soprice']); ?> / <?php echo ucwords($querw['grade']); ?></span>
												</div>
											</li>
											<?php }
											 ?>
										</ul>
										<?php } ?>
									</div>
								</td>
							</tr>
							<?php }} ?>
						</tbody>
					</table>
				</div>
				<div class="text-right">
				
					<button  class="btn btn-basic">Continue With Selected Orders</button>
					<!-- <a href="main.php?paction=asignweighttodispatchslip" class="btn btn-basic">Continue With Selected Orders</a> -->
				</div>
			</div>
		</form>
	</div>

	
	<?php } ?>
</div>
<script>
	$(document).ready(function(){
	//	$('#customer').trigger('change');
	});

	$(document).on('click', '.weighSlipView', function(e){
		e.preventDefault();
		let src = $(this).attr('href');
		let slipViewModal = $('#weighSlipView');
		slipViewModal.find('img').attr('src', src);
		slipViewModal.modal();
	});

	$('#customer').on('change',function(){
		let cid = $(this).val();
		if(cid ==''){
			let cid = $('#customer1').val();

		}
		let vid = <?php echo $_GET['vid']; ?>;
		// console.log(cid);
		// console.log(vid);
		$.ajax({
			type:"post",
			url:"Ajax.php",
			data:{custid:cid,vechileid:vid, doAction:'redirecttodispatchslipadd'},
			success:function(data){
				console.log(data);
				let di = JSON.parse(data);
				//console.log(di[0]);
				let vid = di[0]; // vehicleid
				let custid = di[1]; //customerid
				window.location.href='main.php?paction=dispatch_slip_add&vid='+vid+'&custid='+custid;
			}
		});
	});

	$(document).on('change','.weightToDispatch',function(){
		if($(this).val() > parseFloat($(this).data('max'))){
			alert(`Can't dispatch more than pending weight`);
			$(this).val('');
		}else{
			var idd=$(this).data('idd');
			let that = $(this);
			let weight =that.val();
			let stdlength = $(this).siblings('.stdlength').val();
			let lengthtype = $(this).siblings('.lengthtype').val();
			let mtweight = $(this).siblings('.mtweight').val();
			let ftweight = $(this).siblings('.ftweight').val();

			let tr = $(this).closest('tr');
			tr.find("#weighttodispatch2").val(weight);
			tr.find(".pccal2").trigger('change');
			//.find('#pccal2').val('');
			//tr.find("#pcstodispatch2").val(weight);
			// tr.find(".additem").data('weighttodispatch', 'weight');
			//tr.find('.additem').attr('data-weighttodispatch', weight);
			//console.log(tr.find('.additem').data('weighttodispatch'));
			let qtytype = 1;
			if(weight !='' && weight !=0){
				$.ajax({
					type:"post",
					url:"Ajax.php",
					data:{dispatchweight:weight,stdlength:stdlength,lengthtype:lengthtype,mtweight:mtweight,ftweight:ftweight,qtytype:qtytype,doAction:'calculatepcs'},
					success:function(data){
						let sdi = JSON.parse(data);
						let tr = that.closest('tr');

						tr.find(".pccal2").val(sdi[0].toFixed(2)).trigger('change');
						tr.find('#pccal'+idd).html(sdi[0].toFixed(2));
						//tr.find('.additem').attr('data-pcstodispatch', sdi[0].toFixed(2));
						tr.find('#pcstodispatch2').val(sdi[0].toFixed(2));
						//$('.pccal2').trigger('change');
						//tr.find(".additem").data('pcstodispatch',sdi[0].toFixed(2));
					}
				});
			}else{
				tr.find(".pccal2").val('').trigger('change');
			}
		}
	});

	// $(document).on('click','.additem',function(){
	//     let that = $(this);
	//     let saleorderid= $(this).data('saleorderid');
	//     let saleordersizeid = $(this).data('saleordersizeid');
	//     let productid = $(this).data('productid');
	//     let customerid = $(this).data('customerid');
	//     let tr = $(this).closest('tr');
	//     let pccal = tr.find(".pccal").val();
	//     let weightToDispatch = tr.find('.weightToDispatch').val();
	//     //let weightToDispatch = tr.find('.additem').data('weighttodispatch');
	//     console.log('saleorderid :'+saleorderid);
	//     console.log('saleordersizeid :'+saleordersizeid);
	//     console.log('productid :'+productid);
	//     console.log('customerid: '+customerid);
	//     console.log('pccal: '+pccal);
	//     console.log('weightToDispatch: '+weightToDispatch);
	//     $.ajax({
	//         type:'post',
	//         url:"Ajax.php",
	//         data:{doAction:'adddispatchplanitem',saleorderid:saleorderid,saleordersizeid:saleordersizeid,productid:productid,customerid:customerid,pccal:pccal,weightToDispatch:weightToDispatch},
	//         success:function(data){
	//             console.log(data);
	//         }
	//     });
	// });
	$('.pccal2').change(function(){
		//alert("DDDDd");
		let that = $(this);
		pcc = that.val();
		console.log('pccal2:'+pcc);
		let tr = that.closest('tr');
		// if(pcc >0){
		// 	tr.find(".additem").removeAttr("disabled");
		// }else{
		// 	tr.find(".additem").prop( "disabled", false);	
		// }
		// if(pcc>'0' && pcc !='0'){
		// 	tr.find(".additem").removeAttr("disabled");
		// }else{
		// 	tr.find(".additem").prop( "disabled", true);
		// }
		//tr.find(".additem").prop( "disabled", false);
		// if($pcc != null){
		// 	console.log("hi");
		// 	tr.find(".additem").removeAttr("disabled");
		// }else{
		// 	console.log("hi1");
		// 	tr.find(".additem").attr("disabled",true);
		// }
	});
</script>
