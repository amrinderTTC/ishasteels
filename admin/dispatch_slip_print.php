<style>
    @media print{
        @page{
            size: 13cm 21.5cm;
            margin: .75cm .75cm;
        }

        *{
            -webkit-print-color-adjust: exact !important;   /* Chrome, Safari, Edge */
            color-adjust: exact !important;
        }
        body{
            /* max-width:400px;
            width: 400px !important;
            min-width: 400px !important; */
            /* margin:0px auto; */
            /* width: 11cm;
            height: 21.5cm; */
            /* background: #e0e0e0 !important */
        }
        .page-wrapper .main-content{
            padding: 0px;
            padding-top: 0px !important;
        }
        .table{
            margin-bottom: 0px;
        }
        table tr.heading{
            background: rgba(230,230,230)
        }
        .table.table-bordered th, .table.table-bordered td{
            border-color: #000 !important
        }
        .table th, .table td{
            font-family: 'Nunito', sans-serif;
            padding: 2px 5px;
            font-size: .65rem;
            font-weight: 700;
        }
        .table tr.productrow td{
            padding: 1px 5px;
        }
        table tr.heading th{
            padding:3px 5px;
            background: rgba(220,220,220) !important;
            white-space: nowrap
        }
        span.heading5, h6.bold{
            font-size: .8rem
        }
        th h5.bold{
            font-size: 1rem
        }
        .table-bordered th.border-bottom-0, .table-bordered td.border-bottom-0{
            border-bottom: 0px !important;
        }
        .table-bordered th.border-top-0, .table-bordered td.border-top-0{
            border-top: 0px !important;
        }

    }
</style>

<?php

$perm=check_permission("A");

	if(isset($_GET['vid']) && !empty($_GET['vid']) && is_numeric($_GET['vid'])){
		if(isset($_GET['vid']) && !empty($_GET['vid']) && is_numeric($_GET['vid'])){
			$vid = mysqli_real_escape_string($conn, $_GET['vid']);
			$dispid = mysqli_real_escape_string($conn, $_GET['dispid']);
		
		$vsql ="SELECT gate.gid, gate.cid, gate.vehicleno, gate.vehicletype, gate.transport, gate.drivername, gate.drivermobile, gate.efrom, gate.gatestatus, gate.chkinon, gate.chkinby, gate.dslip, gate.chkouton, gate.chkoutby, gate.createdon, gate.createdby, gate.modifiedon, gate.modifiedby, vehicletype.vtname,
		(SELECT customers.`name` FROM customers INNER JOIN dispatch ON customers.cust_id = dispatch.custid where dispatch.dispatchid=$dispid ) as custname, (select createdon from dispatch where dispatchid=$dispid) as dispatchcreatedon FROM gate INNER JOIN vehicletype ON vehicletype.vtid = gate.vehicletype where gid=$vid";
		//echo $vsql;
		$vsqlq=mysqli_query($conn,$vsql) or die(mysqli_error($conn));
		$vew = mysqli_fetch_assoc($vsqlq);
		// dispatch id details
		
		// $query1 = "SELECT
		// dispatch_item.dispitemid,
		// dispatch_item.dispid,
		// dispatch_item.vid,
		// dispatch_item.customer,
		// dispatch_item.slid,
		// dispatch_item.sopid,
		// dispatch_item.sosize,
		// dispatch_item.prodid,
		// dispatch_item.gradeid,
		// dispatch_item.sizeid,
		// dispatch_item.brandid,
		// dispatch_item.weighttodispatch,
		// dispatch_item.pctodispatch,
		// dispatch_item.finalweighttodispatch,
		// dispatch_item.finalbundles,
		// dispatch_item.finalpctodispatch,
		// dispatch_item.completed,
		// dispatch_item.createdon,
		// dispatch_item.createdby,
		// grade.grade,
		// brands.brandname,
		// sizes.size,
		// products.productname,
		// sizes.currentstock,
		// sizes.lengthtype,
		// sizes.stdlength,
		// sizes.mtweight,
		// sizes.ftweight,
		// saleorderproducts.price,
		// saleordersizes.dispatched,
		// saleordersizes.weightintons as weightintons2,
		// location.locname,
		// sizes.location
		// FROM
		// dispatch_item
		// INNER JOIN grade ON grade.gid = dispatch_item.gradeid
		// INNER JOIN brands ON brands.brid = dispatch_item.brandid
		// INNER JOIN sizes ON sizes.sid = dispatch_item.sizeid
		// INNER JOIN products ON products.prid = dispatch_item.prodid
		// INNER JOIN saleorderproducts ON saleorderproducts.sopid = dispatch_item.sopid
		// INNER JOIN saleordersizes ON saleordersizes.sosize = dispatch_item.sosize
		// INNER JOIN location ON location.lid = sizes.location
		// where dispid = $_GET[dispid] and vid=$_GET[vid] and customer=$_GET[custid]";
		$query1="SELECT
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
		dispatch_item.createdby,
		dispatch_item.bundleweight as isbundle,
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
		location.locname,
		sizes.location,
		saleordersizes.itemremarks
		FROM
		dispatch_item
		INNER JOIN grade ON grade.gid = dispatch_item.gradeid
		INNER JOIN brands ON brands.brid = dispatch_item.brandid
		INNER JOIN products ON products.prid = dispatch_item.sopid
		INNER JOIN sizes ON sizes.sid = dispatch_item.sizeid
		INNER JOIN location ON location.lid = sizes.location
		INNER JOIN saleordersizes ON saleordersizes.sosize=dispatch_item.sosize
		where dispid = $_GET[dispid] and vid=$_GET[vid] and customer=$_GET[custid] order by saleordersizes.sosize,products.productname asc";
		//echo $query1;order by sizes.location,dispatch_item.sopid asc
		//$disqq = mysqli_query($conn,$dissql);
		$totaltod =0;
		$qq = mysqli_query($conn,$query1);
		$re = mysqli_num_rows($qq);
		
		}
	}
?>
<link href="assets/css/print.css" type="text/css" rel="stylesheet"></link>
		<table class="table table-bordered">
			<thead>
				<tr>
					<th colspan="11" class="py-3"><h5 class="bold mb-0 text-center"><?php echo $vew['custname']; ?></div></th>
				</tr>
				<tr>
					<th colspan="5" class="py-2"><h6 class="bold mb-0">Dispatch Slip <?php echo $_GET['dispid'];?></h6></th>
					<th colspan="6" class="py-3 text-right"><span class="heading6 mb-0">Date: <?php echo date('d-m-Y', strtotime($vew['dispatchcreatedon'])); ?></span></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<th colspan="3">Token No.</th>
					<th colspan="3">Vehicle No.</th>
					<th colspan="5">Entry Type</th>
				</tr>
				<tr>
					<td colspan="3"><?php echo $vew['gid']; ?></td>
					<th colspan="3"><?php echo $vew['vehicleno']; ?></th>
					<td colspan="5">Material Out</td>
				</tr>
				<tr>
					<th rowspan="2" style="width: 4rem">Sr.No.</th>
					<th rowspan="2">Product</th>
					<th rowspan="2">Size/Dia<br>(mm)</th>
					<th rowspan="2">Grade</th>
					<th rowspan="2">Brand</th>
					<th rowspan="2" class="text-right" style="width: 6rem">Planned Weight</th>
					<th class="text-right" rowspan="2">Pcs/Bundles</th>
					<!-- <th class="text-center" colspan="2">Qty Planned</th> -->
					<th class="text-center" rowspan="2">Qty Dispatched</th>
					<th rowspan="2">Location</th>
					<th rowspan="2">Remark Office</th>
					<th rowspan="2">Remark Field</th>
				</tr>
				<tr>
					<!-- <th>Bundle</th>
					<th>Pcs</th> -->
				</tr>
				<?php
					if($re>0){
						$pwsum = 0; // panned weight sum
						$pcsum = 0; // planned pc sum
						$bundlesum = 0; // qty planned bundle sum
						$bundelpcssum = 0; // qty planned pcs in bundle sum
						$disbundlesum = 0; // qty dispatched bundle sum
						$disbundelpcssum = 0; // qty dispatched pcs in bundle sum
						$chklocation = '';
						$i = 1;
						$gtotal=$stotal= 0;
						while($rew =  mysqli_fetch_assoc($qq)){
							$tsum += $rew['weighttodispatch'];

							//$pwsum +=$rew['weighttodispatch'];
							//$gtotal += $pwsum;
							

							//echo "$rew[weighttodispatch] - ";
							
							/* if($chklocation!=$rew['location'] && !empty($chklocation)){ 
								//    if($chklocation!=$rew['location']){ 
								?>
								<tr style="height:4rem">
									<th style="vertical-align:bottom" colspan="13" class="text-right"><?php #echo $chklocation.'-'.$rew['location']; ?>Supervisor Name</th>
								</tr>
								<?php
							} */
							
							if($chklocation!=$rew['location'] && $chklocation){ 
								//echo $chklocation
								?>
								<tr>
									<td colspan="5"></td>
									<td><strong><?php echo $stotal;?> Tons</strong></td> 	
									<td colspan="5"></td>
								</tr>

								<tr style="height:4rem">
									<th style="vertical-align:bottom" colspan="13" class="text-right">Supervisor Name</th>
								</tr>
								<?php
								$stotal=0;
							}
							$stotal+=$rew['weighttodispatch'];
							$gtotal+=$rew['weighttodispatch'];
							?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $rew['productname']; ?></td>
								<td><?php echo $rew['size']; ?></td>
								<td><?php echo $rew['grade']; ?></td>
								<td><?php echo (!empty($rew['brandname'])?$rew['brandname']:'-'); ?></td>
								<td><?php echo $rew['weighttodispatch'];?> Tons</td> 
								<td class="text-right"><?php echo $rew['pctodispatch']; echo ($rew['bundleweight']==0?' Pcs':' Bundles');
								$pcsum += $rew['pctodispatch']; ?></td>
								<td></td>
								<td><?php echo $rew['locname']; ?></td>
								<td><?php echo $rew['itemremarks']; ?></td>
								<td></td>
							</tr>
							<?php
							$chklocation=$rew['location'];
							$i++;
						}
						?>
						<tr>
							<td colspan="5"></td>
							<td><strong><?php echo $stotal;?> Tons</strong></td> 	
							<td colspan="5"></td>
						</tr>
						<?php 
					}
					?>
				<tr style="height:4rem">
					<th style="vertical-align:bottom" colspan="15" class="text-right">Supervisor Name</th>
				</tr>
			</tbody>
		</table>

		<strong>Total Weight: <?php echo $gtotal;?> Tons</strong>
<script>
	$(document).ready(function(){
	   window.print();
	});
	window.addEventListener('afterprint', (event) => {
		window.location.href="main.php?paction=dispatch_plan&vid=<?php echo $vid ?>"
	});
</script>