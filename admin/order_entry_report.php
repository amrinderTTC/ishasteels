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
			<h1 class="page-heading h6 ebold">Order Entry Report</h1>
			<ul class="list-inline breadcrumb d-none d-md-flex">
				<li class="breadcrumb-item">Home</li>
				<li class="breadcrumb-item">Order Entry Report</li>
			</ul>
		</div>
		<div class="article">
			<div class="article-heading flex-heading no-print">
				<h5 class="text-center">Order Entry Report</h5>
				<div class="">
					<a href="main.php" class="btn btn-basic btn-sm">Dashboard</a>
					<button class="btn btn-basic btn-sm" onclick="window.print()"><i class="bi bi-printer"></i>&nbsp;Print</button>
				</div>
			</div>

			<div class="article-content container-max">
				<div class="filter mb-4 no-print">
					<form action="" name="filter" method="get">
						<input type="hidden" name="paction" value="order_entry_report">
						<div class="row">
							<div class="col-xl-3 col-lg-3 col-sm-6">
								<div class="form-group">
									<label for="user_id">Select User</label>
									<select name="user_id" class="form-control select2me">
										<option value="">Select From List</option>
										<?php
										$csql="SELECT admin_id, name from admin where typ='OF' order by name asc";
										$cqq = mysqli_query($conn,$csql);
										while($crw=mysqli_fetch_assoc($cqq)){
											echo '<option value="'.$crw['admin_id'].'"'.($crw['admin_id']==$_GET['user_id']?'selected':'').'>'.strtoupper($crw['name']).'</option>';
										}
										?>
									</select>
								</div>
							</div>
							
							<div class="col-xl-2 col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label for="dt_frm">Date From</label>
                                    <input type="date" name="dt_frm" id="dt_frm" value="<?php echo $_GET['dt_frm'];?>" class="form-control">
                                </div>
							</div>
							<div class="col-xl-2 col-lg-3 col-sm-6">
                                <div class="form-group">
                                    <label for="dt_to">Date To</label>
                                    <input type="date" name="dt_to" id="dt_to" value="<?php echo $_GET['dt_to'];?>" class="form-control">
                                </div>
							</div>
							<div class="col-xl-2 col-lg-12">
								<div class="input-group h-100 justify-content-end align-items-center">
									<input type="submit" class="btn btn-basic" value="Apply Filter">
								</div>
							</div>
                            <div class="col-xl-2 col-lg-12">
								<div class="input-group h-100 justify-content-end- align-items-center ">
									<a href="order_entry_report_export.php?user_id=<?php echo "$_GET[user_id]&dt_frm=$_GET[dt_frm]&dt_to=$_GET[dt_to]";?>" class="btn btn-info"><i class="bi bi-file-earmark-spreadsheet"></i></a>
								</div>
							</div>
						</div>
					</form>
				</div>

				<div class="table-responsive mt-3 mt-lg-0">
					<?php
					if($_GET['per_page']){
						$noprd=$_GET['per_page'];
					}else{
						$noprd=50;
					}
					
					$orderby = "order by so.slid desc";
					$cpage=$_REQUEST['page'];
					if ($cpage == 0){
						$cpage=1;
						$cnt=1;
					}else{
						$cnt=($cpage * $noprd) - $noprd + 1;
					}
					
					$frm=($cpage * $noprd) - $noprd;
					$whr=1;

					if(isset($_GET['user_id']) && !empty($_GET['user_id']) && is_numeric($_GET['user_id'])){
						$whr .= " and so.createdby = '$_GET[user_id]'";
					}
					if(isset($_GET['dt_frm']) && !empty($_GET['dt_frm'])){
						$whr .= " and date(so.createdon) >= '".$_GET['dt_frm']."'  ";
					}
					if(isset($_GET['dt_to']) && !empty($_GET['dt_to'])){
						$whr .= " and date(so.createdon) <= '".$_GET['dt_to']."'  ";
					}
					
					$query = "SELECT so.slid, so.cid, so.`status`, so.orderqty AS maxorderqty, so.createdby, c.`name` AS partyname, admin.name AS user_name, so.createdon FROM saleorder as so
						INNER JOIN customers as c ON c.cust_id = so.cid
						INNER JOIN admin ON admin.admin_id = so.createdby
						where $whr
					";
					$q_cnt = mysqli_query($conn,$query) or die(mysqli_error($conn));
					$totl_cnt = mysqli_num_rows($q_cnt);
					echo "Total Records: ".$totl_cnt;
					?>
					<table class="table table-bordered table-large">
						<thead>
							<tr>
								<th>Sr No.</th>
								<th>Order No</th>
								<th>Party</th>
								<th>User</th>
								<th>Completed Dt</th>
								<th>Entry Date</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							// print_r($query);
							$sql=$query;
							$query.=" $orderby LIMIT $frm, $noprd";
							//echo $query;
							$qqq = mysqli_query($conn,$query) or die(mysqli_error($conn));
							while($rw2 = mysqli_fetch_assoc($qqq)){
								// echo "<pre>"; print_r($rw2);echo "</pre>";
								if($rw2['smarkedcompleted']){
									$complete_dt=date('d-m-Y',strtotime($rw2['smarkedcompleted']));
								}else{
									$complete_dt="-";
								}
								?>
								<tr>
									<td><?php echo $cnt;?></td>
									<td><?php echo $rw2['slid'];?></td>
									<td><?php echo strtoupper($rw2['partyname']); ?></td>
									<td><?php echo strtoupper($rw2['user_name']); ?></td>
									<td><?php echo $complete_dt;?></td>
									<td><?php echo date('d-m-Y',strtotime($rw2['createdon'])) ?></td>
								</tr>
								<?php
								$cnt++; 
							}
							?>
						</tbody>
					</table>
				</div>
				<!-- Pagging -->
				<div class="pagging">
						<div class="right">
							<?php
							include('ps_pagination.php');
							$pager = new PS_Pagination($conn, $sql, $noprd, 6, "paction=$_GET[paction]&user_id=$_GET[user_id]&dt_frm=$_GET[dt_frm]&dt_to=$_GET[dt_to]");
							$pager->setDebug(false);
							$pager->total_rows=$result;
							$rs = $pager->paginate();
							echo $pager->renderFirst();
							echo $pager->renderPrev("Back");
							echo $pager->renderNav('<span>', '</span>');
							echo $pager->renderNext("Next");
							echo $pager->renderLast();
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

