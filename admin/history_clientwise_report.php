<?php
$perm=check_permission("A","OF");
    $ip =  $_SERVER['REMOTE_ADDR'];
    $queryIp="SELECT * from allow_ip where ip='$ip'";
    $qIp=mysqli_query($conn, $queryIp) or die(mysqli_error($conn));
    $qlist=mysqli_fetch_array($qIp);
    // var_dump($qlist['ip']); die();
    if($qlist['ip'] != $ip & $_SESSION["user_typ"] != 'A'){ 
        echo '<script>window.location.href="main.php?paction=unauthorize&errmsg=Ip address blocked"</script>';
    }else{
    if(isset($_GET['delslid']) && is_numeric($_GET['delslid'])){
    	//first delete items from saleitems
    	echo $did = $_GET['delslid'];
    	$desq2l = "delete from saleordersizes where sosaleid='$did'";
    	//echo $desq2l.'<br><br><br>';
    	mysqli_query($conn,$desq2l);
    	$de2sql1 = "delete from saleorder where slid='$did'";
    	//echo $de2sql1.'<br><br><br>';
    	mysqli_query($conn,$de2sql1);
    	echo '<script>window.location.href="main.php?paction=sale_order_view&msg=Sale Order Deleted Successfully."</script>';
    }

if($_GET['msg']){
	$msg=$_GET['msg'];
}

if($_GET['errmsg']){
	$errmsg=$_GET['errmsg'];
}
?> 
<style>
    @media print
    {    
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>
<div class="main-content-inner">
<?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
	<?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
	<?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
	<?php if($perm){ ?>

	<div class="page-header d-flex">
		    	<h1 class="page-heading h6 ebold">History Size Report </h1> 
	</div>

	<div class="article">
		<div class=" "> 
		<div class="article-content container-max">
			<div class="filter">
				<form action="" name="filter" method="get">
					<input type="hidden" name="paction" value="history_clientwise_report">
					<div class="row">
						<div class="col-lg-1 col-md-1 col-sm-12">
							<div class="form-group">
								<label for="orderno">Order No.</label>
								<input type="text" name="orderno" id="orderno" class="form-control">
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-13">
							<div class="form-group">
								<label for="partyname">Party Name</label>
								<select name="partyname" id="partyname" class="form-control select2me">
									<option value="">Search Party Name</option>
									<?php
										$csql = "select cust_id,name from customers";
										$cqq = mysqli_query($conn,$csql);
										while($crw = mysqli_fetch_assoc($cqq)){
										    if($crw['cust_id'] == $_GET['partyname']){
										        $sel1 = 'selected';
										    }else{
										        $sel1 = '';
										        
										    }
											echo '<option value="'.$crw['cust_id'].'" '.$sel1.'>'.$crw['name'].' '.$_GET['partyname'].'</option>';
										}
									?>
								</select>
							</div>
						</div> 
						<div class="col-lg-2 col-md-2 col-sm-12">
							<div class="form-group">
								<label for="prodId">Product</label>
								<select name="pproduct" id="pproduct" class="form-control select2me product1">
									<option value="">Product</option> 
									<?php
										$csql3 = "select productname,prid from products";
										$cqq3 = mysqli_query($conn,$csql3);
										while($crw3 = mysqli_fetch_assoc($cqq3)){
										    if($crw3['prid'] == $_GET['pproduct']){
										        $sel2 = 'selected';
										    }else{
										        $sel2 = '';
										        
										    }
											echo '<option value="'.$crw3['prid'].'" '.$sel2.'>'.$crw3['productname'].'</option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="col-lg-2 col-md-2 col-sm-12">
							<div class="form-group">
								<label for="psize">Size</label>
								<?php if($_GET['psize']){ ?>
    								<select name="psize" id="psize" class="form-control select2me psize">
    									<option value="">Search Size</option>
        								 <?php
    										$csql2 = "select size,sid from sizes";
    										$cqq2 = mysqli_query($conn,$csql2);
    										while($crw2 = mysqli_fetch_assoc($cqq2)){
    										    if($crw2['sid'] == $_GET['psize']){
    										        $sel = 'selected';
    										    }else{
    										        $sel = '';
    										        
    										    }
    											echo '<option value="'.$crw2['sid'].'" '.$sel.'>'.$crw2['size'].'</option>';
    										}
    									?>
    								</select>
								<?php }else{ ?> 
    								<select name="psize" id="psize" class="form-control select2me psize">
    									<option value="">Search Size</option> 
    								</select>
								
								<?php }?>
							</div>
						</div>
						<div class="col-xl-2 col-lg-2 col-sm-6">
                            <div class="form-group">
                                <label for="date">Date</label>  
								<input type="date" name="date" value="<?php if($_GET['date']){echo date('Y-m-d',strtotime($_GET['date'])); } ?>" class="form-control"> 
                                 
                            </div>
                        </div>
						<div class="col-lg-2 col-md-2 col-sm-12 d-flex justify-content-sm-space-between">
							<div class="form-group mt-4 pt-1 px-3">
								<input type="submit" name="filter" class="btn btn-basic no-print" value="Apply Filter"> 
							</div>
								<div class="form-group mt-4 pt-1">
							
								 <button class="btn btn-basic no-print" onclick="printPage()">Print</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="table-responsive mt-4">
				<table class="table table-bordered table-xl">
					<thead>
						<tr>
							<th>Order Date</th>
							<th>Completed</th>
							<th>Order No.</th>
							<th>Party Name</th>
							<th>Product</th>
							<th>Size / Grade</th>
							<th>Order Qty.</th>
							<th>Dispatched Qty.</th>
							<th>Pending Qty.</th>  
							<th>Remarks.</th>  
						</tr>
					</thead>
					<tbody>
						<?php
						if($_GET['per_page']){
							$noprd=$_GET['per_page'];
						}else{
							$noprd=20;
						}
						
						$orderby = "order by saleorder.`smarkedcompleted` desc";
						
						$cpage=$_REQUEST['page'];
						if ($cpage == 0){
							$cpage=1;
							$cnt=1;
						}else{
							$cnt=($cpage * $noprd) - $noprd + 1;
						}
						
						$frm=($cpage * $noprd) - $noprd;
						$whr=1;

						
						if($_GET['orderno']){
							$orderno = mysqli_real_escape_string($conn, $_GET['orderno']);
							$whr .= " and saleorder.`slid` = '$orderno' ";
						}
						if(isset($_GET['date']) && !empty($_GET['date'])){
                            $whr .= " and date(saleorder.smarkedcompleted)='".$_GET['date']."'  ";
                        }
                     	if($_GET['psize']){
							$psize = mysqli_real_escape_string($conn, $_GET['psize']);
							$whr .= " and saleordersizes.`sosizeid` = '$psize' ";
						}
							if($_GET['pproduct']){
							$pproduct = mysqli_real_escape_string($conn, $_GET['pproduct']);
							$whr .= " and saleordersizes.`soprid` = '$pproduct' ";
						}
						if(isset($_GET['partyname']) && !empty($_GET['partyname'])){
							$partyname = mysqli_real_escape_string($conn, $_GET['partyname']);
							$whr .= " and  customers.cust_id = '$partyname'";
						} 
						// Assuming you have a database connection object named $pdo
                        $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
                        $cdate = $date->format('Y-m-d');
                        $query = "SELECT saleorder.slid, saleorder.cid, contacts.cperson, contacts.contact AS mobile, saleorder.`status`, saleorder.orderqty AS maxorderqty, customers.`name` AS partyname, saleorder.createdby, saleordersizes.itemremarks, 
                            admin.`user` AS createdbyname, saleorder.createdon, countries.phonecode, grade.grade as gName, sizes.size as sizeName, products.productname as pname, saleordersizes.weightintons, saleordersizes.completed,
                            saleordersizes.dispatched, saleorder.`smarkedcompleted`
                        FROM saleordersizes 
                        INNER JOIN saleorder ON saleorder.slid = saleordersizes.sosaleid 
                        INNER JOIN customers ON customers.cust_id = saleorder.cid 
                        INNER JOIN grade ON grade.gid = saleordersizes.sogradeid 
                        INNER JOIN sizes ON sizes.sid = saleordersizes.sosizeid 
                        INNER JOIN products ON products.prid = saleordersizes.soprid
                        INNER JOIN admin ON admin.admin_id = saleorder.createdby 
                        INNER JOIN contacts ON contacts.cust_id = customers.cust_id 
                        INNER JOIN countries ON countries.id = contacts.countrycode 
                        WHERE $whr AND customers.cust_id = saleorder.cid AND saleorder.cperson = contacts.cid AND saleordersizes.completed = 1
                        and DATE(DATE_ADD(saleordersizes.`smarkedcompleted`, INTERVAL 2 DAY)) >= CURRENT_DATE()";
                        
                        //AND (DATE_ADD(DATE_ADD(DATE(saleorder.`smarkedcompleted`), INTERVAL 12 HOUR), INTERVAL 3 DAY) >= NOW() AND saleorder.`status` = 1)
                        
                        // echo "<pre>";print_r($query);echo "</pre>"; 
                        // die();
                        // $stmt = $pdo->prepare($query);
                        // $stmt->execute();
                        // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                        // Use $result as needed

						$sql=$query;
				        //echo $sql;$whr LIMIT $frm, $noprd
						$query.=" $orderby"; 
						$qq = mysqli_query($conn,$query);
						if(mysqli_num_rows($qq)>0){ 
						    $totalWt = 0;
						    $totaldispWt = 0;
						    $totalDispBal = 0;
							while($rw=mysqli_fetch_assoc($qq)){
								// $q1="SELECT sum(weightintons) AS orderedqty from saleordersizes where sosaleid = $rw[slid] ";
								// $q1 = mysqli_query($conn,$q1) or die(mysqli_fetch_assoc($conn));
								// if($rr=mysqli_fetch_assoc($q1)){ 
								// 	$rw['orderedqty']=$rr['orderedqty'];
								// }

								// $q1="SELECT sum(dispatched) AS ordereddispatched from saleordersizes where sosaleid = $rw[slid] ";
								// $q1 = mysqli_query($conn,$q1) or die(mysqli_fetch_assoc($conn));
								// if($rr=mysqli_fetch_assoc($q1)){ 
								// 	$rw['ordereddispatched']=$rr['ordereddispatched'];
								// }
								
								// $q1="SELECT slid AS slidused from dispatch_item where slid = $rw[slid] group by slid "; 
								// $q1 = mysqli_query($conn,$q1) or die(mysqli_fetch_assoc($conn));
								// if($rr=mysqli_fetch_assoc($q1)){ 
								// 	$rw['slidused']=$rr['slidused'];
								// }
								?>
								<tr 
									<?php 
									if($rw['completed'] == '1'){
										echo 'class="bg-lightgreen"';
									} 
									$totalWt +=$rw['weightintons'];
									$totaldispWt +=$rw['dispatched'];
									$totalDispBal +=$rw['weightintons'] - $rw['dispatched'];
									?>>
									<td><?php echo date('d-m-Y',strtotime($rw['createdon']));?></td>
									<td><?php echo date('d-m-Y',strtotime($rw['smarkedcompleted'])); ?></td>
									<td><?php echo $rw['slid']; echo $currentDate;?> </td>
									<td><?php echo strtoupper($rw['partyname']); ?></td>
									<td><?php echo strtoupper($rw['pname']); ?></td>
									<td><?php echo strtoupper($rw['gName']);; ?> / <?php echo strtoupper($rw['sizeName']);; ?></td>
									<td class="text-right" ><?php echo $rw['weightintons']; ?> Tons</td>
									
									<td><?php echo $rw['dispatched'];?> Tons</td>
									<td><?php echo ($rw['weightintons']-$rw['dispatched']); ?> Tons</td>
									 <td><?php echo $rw['itemremarks'];?></td>
								</tr>
								<?php
							}
						}else{
							echo '<tr><td colspan="8" class="text-center">No Record Found.</td></tr>';
						} ?>
					</tbody> 
					<tfoot>
					    <tr>
					        <td class="text-right" colspan="7"><strong><?php echo number_format($totalWt,3);?> Tons</strong></td>
					        <td><strong><?php echo number_format($totaldispWt,3);?> Tons </strong></td>
					        <td colspan="2" class="text-left"><strong><?php echo number_format($totalDispBal,3);?> Tons</strong></td> 
					    </tr>
					</tfoot>
				</table>
			</div> 
		</div>
	</div>
	<?php } } ?>
</div>
<script>
	$('.product1').on('change', function() {
		let pid = $(this).val();
	alert(pid);
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
				$('.psize').html(html4);

			}
		});
	});
</script>
<script>
// 	$(document).on('click','.statusToggle',function(){
// 		$(this).toggleClass('active inactive');
// 		$(this).children('.fa').toggleClass('fa-toggle-on fa-toggle-off');
// 		let id = $(this).data('id');
// 		let action = "changestatus";
// 		$.ajax({  
// 			type:'post',
// 			url:"Ajax.php",
// 			data:{uid:id,doAction:action},
// 			success:function(data){
// 			   // console.log(data);
// 				location.reload(true); 
// 			}
// 		});
// 	});
// </script>
   <script>
        function printPage() {
            window.print();
        }
    </script>