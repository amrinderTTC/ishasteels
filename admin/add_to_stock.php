<?php 
$perm=check_permission("A","SK");
$prodid = mysqli_real_escape_string($conn, $_GET['prod_id']);
$brand = mysqli_real_escape_string($conn, $_GET['brand']);
//var_dump($_GET['brand']);
// if(isset($_GET['prod_id'])){
//     //echo "stage 1";
//     if(empty($_GET['prod_id']) || !is_numeric($_GET['prod_id'])){
//        // echo "stage 2";
//         echo '<script>window.location.href="main.php?paction=stock"</script>';
//     }
// }else{
//     echo '<script>window.location.href="main.php?paction=stock"</script>';
// }
// if(isset($_GET['brand'])){
//     //echo "stage 1";
//     if(empty($_GET['brand']) || !is_numeric($_GET['brand'])){
//         echo "stage 2";
//         echo '<script>window.location.href="main.php?paction=stock"</script>';
//     }
// }else{
//     echo '<script>window.location.href="main.php?paction=stock"</script>';
// }



if($_POST['doAction']=='updatestock'){
   // var_dump($_POST);
	$prdid = $_POST['prid'];
	$brid = $_POST['brid'];
	$sid = $_POST['sid'];
	$grid = $_POST['grid'];
	$weight = $_POST['newWeight'];
	for($y=0;$y<count($prdid);$y++){
		if(!empty($weight[$y])){
			
			$cstsql = "SELECT currentstock from sizes where sid='".$sid[$y]."'";
			//echo $cstsql;
			$cstqq = mysqli_query($conn,$cstsql);
			$cstrw = mysqli_fetch_assoc($cstqq);
			//var_dump($cstrw);
			$currentweight = $cstrw['currentstock'];
			//var_dump($currentweight).'<br>';
			$stsql = "INSERT into stock set 
				prid='".$prdid[$y]."',
				sizeid = '".$sid[$y]."',
				gradeid = '".$grid[$y]."',
				brandid = '".$brid[$y]."',
				weight = '".$weight[$y]."',
				createdon = '".$createdon."',
				createdby = '".$createdby."'
			";
			$stqq = mysqli_query($conn,$stsql);
			//echo $stsql;
			if(mysqli_insert_id($conn)>0){
				$newwght = ($currentweight+$weight[$y]);
				$ucstsql = "UPDATE sizes set currentstock = '".$newwght."' where prid='".$prdid[$y]."' and sid='".$sid[$y]."'";
				mysqli_query($conn,$ucstsql);
				echo "<script>window.location.href='main.php?page=$_GET[page]&paction=$_GET[paction]&doAction=showlist&product=$_GET[product]&sortby=$_GET[sortby]&brand=$_GET[brand]&grade=$_GET[grade]&sortby=$_GET[sortby]&size=$_GET[size]&shed=$_GET[shed]&msg=Stock updated Successfully'</script>";
				// echo '<script>window.location.href="main.php?paction=add_to_stock&msg=Stock updated Successfully"</script>';
			}else{
				//echo 'stage1';
				echo '<script>window.location.href="main.php?errmsg=Unable To Insert Stock Right Now. Please Try Later."</script>';
			}
		}
	} 
}

/** Amit Verma */
if($_GET['doAction']=='showlist'){
   
	/**
	 * sort by values
	 * 1 grade asc
	 * 2 grade desc
	 * 3 size asc
	 * 4 size desc
	 */


	if($_GET['sortby']){
		if(!empty($_GET['sortby']) && is_numeric($_GET['sortby'])){
			$sortby = $_GET['sortby'];
			//var_Dump($sortby);
			if($sortby=='1'){ // order by grade asc
				$orderby = "order by sizes.grade asc";
			}else if($sortby =='2'){ // order by grade desc
				$orderby = "order by sizes.grade desc";
			}else if($sortby =='3'){ // order by size asc
				$orderby = "order by sizes.sid asc";
			}else if($sortby =='4'){ // order by size desc
				$orderby = "order by sizes.sid desc";
			}
		}
	}
	
}else{
		$orderby = " order by products.productname asc";
	} 
		
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
	<?php if($perm){ ?>
	<div class="page-header">
		<h1 class="page-heading h6 ebold">Stock View</h1>
		<ul class="list-inline breadcrumb d-none d-md-flex">
			<li class="breadcrumb-item">Home</li>
			<li class="breadcrumb-item">Stock View</li>
		</ul>
	</div>
	<div class="article">
		<div class="article-heading flex-heading">
			<h5 class="text-center">Stock View</h5>
			<div class="sort">
				<select name="sort" class="form-control" id="sort">
					<option value="">Sort By</option>
					<option value="1" <?php echo ($_GET['sortby']=='1'?'selected':''); ?>>Grade Asc</option>
					<option value="2" <?php echo ($_GET['sortby']=='2'?'selected':''); ?>>Grade DESC</option>
					<option value="3" <?php echo ($_GET['sortby']=='3'?'selected':''); ?>>Size ASC</option>
					<option value="4" <?php echo ($_GET['sortby']=='4'?'selected':''); ?>>Size DESC</option>
				</select>
			</div>
		</div>
		<div class="filter mb-4">
			<form action="" id="filterForm" method="get">
				<input type="hidden" name="paction" value="stock_view">
				<input type="hidden" name="doAction" value="showlist">
				<div class="row">
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="form-group">
							<input type="hidden" name="paction" class="finished_stock" value="<?php echo $_GET['paction']; ?>">
							<label for="product">Filter By Product</label>
							<select name="product" id="product" class="form-control select2me filteritem">
								<option value="">All Products</option>
								<?php 
								$psql = "select prid,productname from products order by productname asc";
								$pqq = mysqli_query($conn,$psql);
								while($prw =mysqli_fetch_array($pqq)){
									echo '<option value="'.$prw['prid'].'"'.($prw['prid']==$_GET['product']?' selected':'').'>'.ucwords($prw['productname']).'</option>';
								}
								?>
							</select>
							<input type="hidden" name="sortby" class="sortby">
						</div>
					</div>
					
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="form-group">
							<label for="brand">Filter By Brand</label>
							<select name="brand" id="brand" class="form-control select2me filteritem">
								<option value="">All Brands</option>
								<?php $bsql = "select brid,brandname from brands order by brandname asc";
									$bqq = mysqli_query($conn,$bsql);
									while($brw = mysqli_fetch_assoc($bqq)){
										echo '<option value="'.$brw['brid'].'"'.($brw['brid']==$_GET['brand']?' selected':'').' >'.$brw['brandname'].'</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="form-group">
							<label for="grade">Filter By Grade</label>
							<select name="grade" id="grade" class="form-control select2me filteritem">
								<option value="">All Grades</option>
								<?php $gsql = "select gid,grade from grade order by grade desc";
									  $gqq = mysqli_query($conn,$gsql); 
										while($grw = mysqli_fetch_assoc($gqq)){
											echo '<option value="'.$grw['gid'].'" '.($grw['gid']==$_GET['grade']?'selected':'').'>'.$grw['grade'].'</option>';
										}
									?>
							</select>
							<input type="hidden" name="sortby" class="sortby">
						</div>
					</div>
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="form-group">
							<label for="size">Filter By Size</label>
							<select name="size" id="size" class="form-control select2me">
								<option value="">All Sizes</option>

							</select>
						</div>
					</div>
					<div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="form-group">
							<label for="shed">Filter By Shed</label>
							<label for="shed">All Sheds</label>
							<select name="shed" id="shed" class="form-control select2me">
								<option value="">All Sheds</option>
								<?php
									$shedsql="select lid,locname from location order by lid asc";
									$shedqq = mysqli_query($conn,$shedsql);
									while($shrw = mysqli_fetch_assoc($shedqq)){
										echo '<option value="'.$shrw["lid"].'"'.($shrw['lid']==$_GET['shed']?' selected ': '').'>'.$shrw["locname"].'</option>';
									}
								?>
							</select>
						</div>
					</div>
					<div class="col-xl-2">
						<div class="h-100 d-flex align-items-center justify-content-end">
							<input type="submit" class="btn btn-basic" value="Apply Filter">
						</div>
					</div>
				</div>
			</form>
		</div>

		<div class="article-content container-max">
			<form action="main.php?paction=<?php echo $_GET['paction'];?>&page=<?php echo $_GET['page'];?>&doAction=showlist&product=<?php echo $_GET['product'];?>&sortby=<?php echo $_GET['sortby'];?>&brand=<?php echo $_GET['brand'];?>&grade=<?php echo $_GET['grade'];?>&sortby=<?php echo $_GET['sortby'];?>&size=<?php echo $_GET['size'];?>&shed=<?php echo $_GET['shed'];?>" method="post">
				<input type="hidden" name="doAction" value="updatestock">
				<div class="table-responsive">
					<table class="table table-bordered table-big">
						<thead>
							<tr>
								<th rowspan="2">#</th>
								<th rowspan="2">Product</th>
								<th rowspan="2">Brand</th>
								<th rowspan="2">Size/Dia(mm)</th>
								<th rowspan="2">Length(Meter)</th>
								<th rowspan="2">Grade</th>
								<th colspan="2" class="text-center">Available Qty</th>
								<th colspan="2" class="text-center">Add Qty</th>
								<th rowspan="2" class="text-center">Location</th>
								<!-- <th style="width: 5rem" rowspan="2"></th> -->
							</tr>
							<tr>
								<th class="text-center">Weight(Tons)</th>
								<th class="text-center">Pcs / Bundles</th>
								<th style="width: 8rem" class="text-center">Weight(Tons)</th>
								<th class="text-center">Pcs / Bundles</th>
							</tr>
						</thead>
						<tbody>
							<?php
							if($_GET['per_page']){
								$noprd=$_GET['per_page'];
							}else{
								$noprd=50;
							}

							$cpage=$_REQUEST['page'];
							if ($cpage == 0){
								$cpage=1;
								$cnt=1;
							}else{
								$cnt=($cpage * $noprd) - $noprd + 1;
							}
							
							$frm=($cpage * $noprd) - $noprd;
							$whr=1;
							if(isset($_GET['product']) && !empty($_GET['product']) && is_numeric($_GET['product'])){
								$prid = mysqli_real_escape_string($conn,$_GET['product']);
								$whr .=" and sizes.prid='$prid' ";
							}
							
							if(isset($_GET['brand']) && !empty($_GET['brand']) && is_numeric($_GET['brand'])){
								$brid = mysqli_real_escape_string($conn,$_GET['brand']);
								$whr .=" and sizes.brand ='$brid' ";
							}
							if(isset($_GET['grade']) && !empty($_GET['grade']) && is_numeric($_GET['grade'])){
								$whr .=" and sizes.grade='".$_GET['grade']."' ";
							}
							if(isset($_GET['size']) && !empty($_GET['size']) && is_numeric($_GET['size'])){
								$whr .=" and sizes.sid='".$_GET['size']."' ";
							}
							if(isset($_GET['shed']) && !empty($_GET['shed']) && is_numeric($_GET['shed'])){
								$whr .=" and sizes.location='".$_GET['shed']."' ";
							}

							$query = "SELECT sizes.sid,
								sizes.prid,
								sizes.size,
								sizes.mtweight,
								sizes.ftweight,
								sizes.weighttype,
								sizes.stdlength,
								sizes.lengthtype,
								sizes.grade as gradeid,
								sizes.bundleweight,
								grade.grade,
								sizes.brand, 
								sizes.bundleweight, 
								(select brandname from brands where brid=sizes.brand) as brandname, 
								sizes.location, 
								sizes.currentstock as weigth, 
								products.productname, 
								location.locname FROM sizes 
								INNER JOIN products ON products.prid = sizes.prid 
								INNER JOIN location ON location.lid = sizes.location 
								INNER JOIN grade ON grade.gid = sizes.grade
								where $whr
							";
							$sql = $query;
							$query.=" $orderby LIMIT $frm, $noprd";
							// $query.=" $orderby";
							//echo $query;
							$qq = mysqli_query($conn,$query);
							// $x = 1;
							$twght=0;
							$tpc=0;
							while($rw= mysqli_fetch_assoc($qq)){
								if($rw['lengthtype']=='1'){ // pc in meters
									$wghttn = ($rw['mtweight']*$rw['stdlength'])/1000;
								}else if($rw['lengthtype']=='2'){ // pc in foot
									$wghttn = ($rw['ftweight']*$rw['stdlength'])/1000;
								}
								$wght2pcs=($rw['weigth']/$wghttn);
								?>
								<tr>
									<td><?php echo $cnt; ?>
									<input type="hidden" name="prid[]" value="<?php echo $rw['prid']; ?>">
									<input type="hidden" name="brid[]" value="<?php echo $rw['brand']; ?>">
									<input type="hidden" name="sid[]" value="<?php echo $rw['sid']; ?>">
									<input type="hidden" name="grid[]" value="<?php echo $rw['gradeid']; ?>">
									</td>
									<td><?php echo $rw['productname']; ?></td>
									<td><?php echo (empty($rw['brandname'])?'-':$rw['brandname']); ?></td>
									<td><?php echo $rw['size']; ?></td>
									<td><?php 
									
									echo ($rw['stdlength']);
									echo ($rw['lengthtype']=='1'?' Mt.':' Ft.');
									//echo ($rw['weighttype']=='1'?' Mt.':' Ft.'); ?></td>
									<td><?php echo $rw['grade'] ;?></td>
									<td class="text-right"><?php echo $rw['weigth'];
									$twght +=$rw['weigth'];
									?> Tons</td>
									<td class="text-right"><?php
									$newr=wtpcconvert($rw['stdlength'],$rw['lengthtype'],$rw['mtweight'],$rw['ftweight'],$rw['weigth'],'1',$rw['bundleweight']);
									//var_dump($newr);
										$pc = round($newr[0],2);
										echo $pc;
										if(!empty($rw['bundleweight'])){
											echo ' Bundles';
										}else {
											echo ' Pcs';
										}
										$tpc +=$pc;    
										?></td>
									<td class="text-right"><input type="number" name="newWeight[]" step="0.001" class="form-control newWeight">
									<input type="hidden" name="sizeid" class="sizeid" value="<?php echo $rw['sid']; ?>"></td>
									<td class="text-right newpcs"></td>
									<td class="text-center"><?php echo $rw['locname']; ?></td>
									
								</tr>
								<?php
								$cnt++;
							}
							?>
							<tr>
								<th class="text-right" colspan="6">Total</th>
								<td class="text-right"><?php echo $twght; ?> Tons</td>
								<td class="text-right"><?php #echo $tpc; ?> </td>
								<td class="text-right" colspan="4"></td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="text-right">
					<input type="submit" class="btn btn-basic" name="updateAll" value="Update All">
				</div>
			</form>

			<!-- Pagging -->
			<div class="pagging">
				<div class="right">
					<?php
					include('ps_pagination.php');
					$pager = new PS_Pagination($conn, $sql, $noprd, 6, "paction=$_GET[paction]&doAction=showlist&product=$_GET[product]&sortby=$_GET[sortby]&brand=$_GET[brand]&grade=$_GET[grade]&sortby=$_GET[sortby]&size=$_GET[size]&shed=$_GET[shed]");
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
	<?php } ?>
</div>

<script>

	$('#sort').change(function(){
		let sort = $(this).val();
		$('.sortby').val(sort);
		$('#filterForm').submit();
	});
	$('document').ready(function(){
		$('#grade').trigger('change');
		gid = '<?php echo $_GET['grade']; ?>';
		console.log('gid:'+gid);
	});
	
	// $('#grade').change(function(){
	//     let gid = $('#grade').val(); // grade id
	//     //console.log(gid);
	//     if(gid!=''){
	//         $.ajax({
	//             url: 'Ajax.php',
	//             type: 'POST',
	//             data: {doAction:'gradesize',gradid:gid},
	//             success: function(data) {
	//                 //console.log(data);
	//                 let di = JSON.parse(data);
	//                 html='<option value="">Select From List</option>';
					
	//                 for(let x=0; x<di.length; x++){
	//                     html +='<option value="'+di[x].sid+'"';
					
	//                     html +='>'+di[x].size+'</option>';
	//                 }
	//                 //console.log(html);
	//                 $('#size').html(html);
	//             }
	//         });
	//     }
	// });

	$('.filteritem').each(function(){
		$(this).change(function(){
			let gid = $('#grade').val(); // grade id
			let brand = $('#brand').val(); // brand id
			let product = $('#product').val(); // product id
			//console.log(gid);
			if(gid!='' && brand != '' && product != ''){
				$.ajax({
					url: 'Ajax.php',
					type: 'POST',
					data: {doAction:'stockviewsizelist',gradid:gid, brandid: brand, productid:product},
					success: function(data) {
						//console.log(data);
						let di = JSON.parse(data);
						html='<option value="">Select From List</option>';
						let csizeid = '<?php echo $_GET['size']; ?>';
						for(let x=0; x<di.length; x++){
							html +='<option value="'+di[x].sid+'"';
							if(csizeid !='' && di[x].sid==csizeid){
								html += ' selected';
							}
							html +='>'+di[x].size+'</option>';
						}
						//console.log(html);
						$('#size').html(html);
					}
				});
			}
		})
	});
	
	//To work on
	$(document).on('change','.newWeight',function(e){
		e.preventDefault();
		let awght = $(this).val();
		let sizid = $(this).siblings('.sizeid').val();
		let that = $(this);
		$.ajax({
			type:"post",
			url:"Ajax.php",
			data:{doAction:'getpcsfromsizeidweight',disizeid:sizid,disweight:awght},
			success:function(data){
				//console.log(data);
				let dd = JSON.parse(data);
				//console.log(dd[0]);
				let tr = that.closest('tr');
				//console.log(tr.find('.newpcs'));
				tr.find('.newpcs').html(dd[0].toFixed(2));   
			}
		});
	});
</script>
