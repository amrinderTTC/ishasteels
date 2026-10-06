<?php
$perm=check_permission("A");
$whr = 1;
$vid = mysqli_real_escape_string($conn, $_GET['vid']);
$custid = mysqli_real_escape_string($conn, $_GET['cust_id']);
$dispatchid = mysqli_real_escape_string($conn, $_GET['dispid']);
if(empty($vid) || empty($custid) || empty($dispatchid)){
    echo '<script>window.location.href="main.php?paction=dispatch_report"</script>';
}else if(!is_numeric($vid) || !is_numeric($custid)){
    echo '<script>window.location.href="main.php?paction=dispatch_report"</script>';
}
error_reporting(0);
?>

<script>
	// function cnf(id, sizecom){
	// 	let confirm = window.confirm("Do you Wand to Mark. This Size Complete?");
	// 	if(confirm==true){
	// 		window.location.href="main.php?paction=client_wise_deal&slid="+id+"&sizecom="+sizecom+"&mark=C";
	// 	}else{
            
	// 	}
	// }
</script>
<link href="assets/css/print.css" type="text/css" rel="stylesheet">
<div class="main-content-inner">
<?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
    <?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
    <?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
    <?php
    if($perm){
    ?>

    <div class="page-header no-print">
        <h1 class="page-heading h6 ebold">Dispatch Report Details</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Dispatch Report Details</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading no-print">
            <h5 class="text-center">Today's Dispatch Report</h5>
            <div class="">
                <a href="main.php" class="btn btn-basic btn-sm">Dashboard</a>
                <a href="main.php?paction=dispatch_slip_print_customer&vid=<?php echo $vid; ?>&custid=<?php echo $custid;?>&dispid=<?php echo $dispatchid; ?>" class="btn btn-basic btn-sm no-print"><i class="bi bi-printer"></i>&nbsp;Print</a>
                <!-- <button class="btn btn-basic btn-sm" ><i class="bi bi-printer"></i>&nbsp;Print</button> -->
            </div>
        </div>

        <div class="article-content container-max">
            <div class="filter mb-4 no-print">
                <!-- <form action="" name="filter" method="post">
                    <input type="hidden" name="paction" value="dispatch_report">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="product">Select Product</label>
                                <select name="product" class="form-control select2me">
                                    <option value="">Select From List</option>
                                    <?php
                                    $csql="select prid, productname from products order by productname asc";
                                    $cqq = mysqli_query($conn,$csql);
                                    while($crw=mysqli_fetch_assoc($cqq)){
                                        echo '<option value="'.$crw['prid'].'"'.($crw['prid']==$prid?'selected':'').'>'.strtoupper($crw['productname']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="grade">Select Grade</label>
                                <select name="grade" class="form-control select2me">
                                    <option value="">Select From List</option>
                                    <?php
                                    $csql="select gid, grade from grade order by gid asc";
                                    $cqq = mysqli_query($conn,$csql);
                                    while($crw=mysqli_fetch_assoc($cqq)){
                                        echo '<option value="'.$crw['gid'].'"'.(($crw['gid']==$gid || ($crw['gid'] != '' && strtoupper($crw['grade']) == 'MS'))?'selected':'').'>'.strtoupper($crw['grade']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="size">Select Size</label>
                                <select name="size" class="form-control select2me">
                                    <option value="">Select From List</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="dispatchslip">Select Dispatch Slip</label>
                                <select name="dispatchslip" class="form-control">
                                    <option value="">Select From List</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="input-group h-100 justify-content-end align-items-end">
                                <input type="submit" name="filter" class="btn btn-basic" value="Apply Filter">
                            </div>
                        </div>
                    </div>
                </form> -->
            </div>
            <div class="row">
            <?php $dsql="select gate.vehicleno,customers.name from gate,customers where gid=$vid and cust_id=$custid";
                    $dqq = mysqli_query($conn,$dsql);
                    $drw = mysqli_fetch_assoc($dqq);
                    //var_Dump($drw);
                 ?>
            <div class="col-4">
                    <h6 class="bold">Dispatch Id.</h6>
                    <p><?php echo $dispatchid; ?></p>
                </div>
                <div class="col-4">
                    <h6 class="bold">Customer Name</h6>
                    <p><?php echo strtoupper($drw['name']); ?></p>
                </div>
                <div class="col-4">
                    <h6 class="bold">Vehicle No.</h6>
                    <p><?php echo strtoupper($drw['vehicleno']);?></p>
                </div>
            </div>
            <div class="table-responsive mt-3 mt-lg-0">
                <table class="table table-bordered table-large">
                    <thead>
                        <tr>
                            <th rowspan="2">S.N.</th>
                            <th rowspan="2">O.N.</th>
                            <th rowspan="2">Product</th>
                            <th rowspan="2">Grade</th>
                            <th rowspan="2">Brand</th>
                            <th colspan="2" class="text-center">Dispatched</th>
                            <!-- <th rowspan="2">Dispatch<br/>Slip No.</th> -->
                        </tr>
                        <tr>
                            <th class="text-center">Weight MT</th>
                            <th class="text-center">Pcs/Bndl</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        
                        $query = "SELECT
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
                        dispatch_item.bundleweight,
                        dispatch_item.finalweighttodispatch,
                        dispatch_item.finalweight,
                        dispatch_item.finalbundles,
                        dispatch_item.finalpcs,
                        dispatch_item.finalpctodispatch,
                        dispatch_item.completed,
                        dispatch_item.createdon,
                        dispatch_item.createdby,
                        dispatch_item.modifiedon,
                        dispatch_item.modifiedby,
                        dispatch.custid,
                        customers.`name`,
                        products.productname,
                        grade.grade,
                        brands.brandname,
                        sizes.size,
                        gate.vehicleno
                        FROM
                        dispatch_item
                        INNER JOIN dispatch ON dispatch.dispatchid = dispatch_item.dispid
                        INNER JOIN customers ON customers.cust_id = dispatch.custid
                        INNER JOIN products ON products.prid = dispatch_item.sopid
                        INNER JOIN grade ON grade.gid = dispatch_item.gradeid
                        INNER JOIN brands ON brands.brid = dispatch_item.brandid
                        INNER JOIN sizes ON sizes.sid = dispatch_item.sizeid
                        INNER JOIN gate ON gate.gid = dispatch.vehicleid where vid='$vid' and customer='$custid' and dispatch_item.dispid=$dispatchid and gate.gatestatus='2'";
                        // where date(dispatch_item.createdon)=CURDATE()
                        $qq = mysqli_query($conn,$query); 
                        //echo $query;
                        $i=1;
                        $total = 0;
                        while($rw = mysqli_fetch_assoc($qq)){
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $rw['slid'];?></td>
                            <td><?php echo $rw['productname'] .'<br/>'.$rw['size'];?></td>
                            
                            <td><?php echo $rw['grade'];?></td>
                            <td><?php echo $rw['brandname'];?></td>
                            <td class="text-right"><?php echo $rw['finalweight']; $total +=$rw['finalweight']; ?></td>
                            <td><?php echo $rw['actualpcs'];?></td>
                            <!-- <td><?php #echo $rw['dispid'];?></td> -->
                        </tr>
                        <?php $i++; } ?>
                        <tr>
                            <th colspan="5" class="text-right">Total</th>
                            <td  class="text-right"><?php echo $total; ?></td>
                            <td></td>
                            
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Pagging -->
                <div class="pagging">
                    <div class="right">
                        <?php
                        include('ps_pagination.php');
                        $pager = new PS_Pagination($conn, $sql, $noprd, 6, "paction=$_GET[paction]");
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


<div class="modal" id="adddispatchtodeals">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title bold">Add New Dispatch To Deal</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <h6 class="bold">Pending Qty : <span id="pendingQty">0</span> Tons</h6>
                <form action="" class="mt-4" method="post">
                    <input type="hidden" name="doAction" value="adddealdispatch">
                    <input type="hidden" name="dealid" id="dealid"  required>
                    <input type="hidden" name="dealitemid" id="dealitemid" required>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="vehicleNo">Vehicle No.</label>
                                <input type="text" name="vehicleNo" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="vehicleNo">Qty Dispatched (Tons)</label>
                                <input type="number" step="0.001" name="dispatch" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <input type="submit" name="dispatchsubmit" class="btn btn-basic" value="Dispatch">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
	function adddispatch(dealid, dealitemid, pendingqty){
        $('#pendingQty').text(pendingqty);
        $('#dealid').val(dealid);
        $('#dealitemid').val(dealitemid);
        $('#adddispatchtodeals').modal();
    }
</script>
