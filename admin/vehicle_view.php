<?php
$perm=check_permission("A","WT");
//$permAll=check_permission("SA","A","GT","WT");
//$permA_SA_WEIGHT=check_permission("SA","A","WT");
if(isset($_GET['vid']) && !empty($_GET['vid']) && is_numeric($_GET['vid'])){
    $vid = mysqli_real_escape_string($conn, $_GET['vid']);
    // $sql= "SELECT gate.tokenid, gate.gid, gate.cid,gate.vehicleno, 
    // gate.vehicletype, gate.transport, gate.drivername,gate.drivermobile, gate.efrom, gate.gatestatus, gate.createdon, gate.createdby, gate.modifiedon, 
    // gate.modifiedby, vehicletype.vtname,(select user from admin where admin.admin_id = gate.chkinby) as chkinbyname,
    // (select user from admin where admin.admin_id = gate.chkoutby) as chkoutbyname, gate.chkinon,gate.chkouton, admin.`name` as createdbyname FROM gate 
    // INNER JOIN admin ON admin.admin_id = gate.createdby INNER JOIN vehicletype ON vehicletype.vtid=gate.vehicletype where gid='$vid' ";
    $sql= "SELECT gate.tokenid,gate.gid, gate.cid,gate.vehicleno, 
    gate.vehicletype, gate.transport, gate.drivername,gate.drivermobile, gate.efrom, gate.gatestatus, gate.createdon, gate.createdby, gate.modifiedon, 
    gate.modifiedby, customers.`name` as pname, vehicletype.vtname,(select user from admin where admin.admin_id = gate.chkinby) as chkinbyname,
    (select user from admin where admin.admin_id = gate.chkoutby) as chkoutbyname, gate.chkinon,gate.chkouton FROM gate 
    INNER JOIN customers ON customers.cust_id = gate.cid INNER JOIN vehicletype ON vehicletype.vtid=gate.vehicletype where gid='$vid' ";
$q=mysqli_query($conn,$sql) or die(mysqli_error($conn));
$rw = mysqli_fetch_assoc($q);
}else{
    echo '<script>window.location.href="main.php"</script>';
}

?>

<div class="main-content-inner">
<?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
    <?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
    <?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
    <?php
    if($perm){
    ?>
    <div class="page-header">
        <h1 class="page-heading h6 ebold">Vehicle Details</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Gate</li>
            <li class="breadcrumb-item">Vehicle Details</li>
        </ul>
    </div>
    <div class="text-right mb-4">
        <!-- <a href="main.php?paction=material_in&vid=12" class="btn btn-basic btn-sm"><i class="bi bi-pencil-square"></i>&nbsp;Edit</a> -->
        <a href="main.php?paction=dashboard_gate" class="btn btn-basic btn-sm">Dashboard</a>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <div class="article">
                <div class="article-heading flex-heading">
                    <h5 class="text-center">Vehicle Details (Material In)</h5>
                </div>
                <div class="article-content">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th>Token No.</th>
                            <td><?php echo $rw['tokenid']; ?> | <?php echo $rw['createdon']; ?></td>
                        </tr>
                        <tr>
                            <th>Party Name</th>
                            <td><?php echo ucwords($rw['pname']); ?></td>
                        </tr>
                        <tr>
                            <th>Vehicle No.</th>
                            <td><?php echo strtoupper($rw['vehicleno']); ?></td>
                        </tr>
                        <tr>
                            <th>Vehicle Type</th>
                            <td><?php echo ucwords($rw['vtname']); ?></td>
                        </tr>
                        <tr>
                            <th>Transporter</th>
                            <td><?php echo ucwords($rw['transport']); ?></td>
                        </tr>
                        <tr>
                            <th>Driver</th>
                            <td><?php echo ucwords($rw['drivername']);?></td>
                        </tr>
                        <tr>
                            <th>Mobile No.</th>
                            <td><?php echo ucwords($rw['drivermobile']);?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mt-4 mt-lg-0">
            <div class="article">
                <div class="article-heading flex-heading">
                    <h5 class="text-center">Check In / Check Out</h5>
                </div>
                <div class="article-content">
                    <table class="table table-bordered mb-0">
                        <tr>
                            <th>Checkin</th>
                            <td><?php echo $rw['createdon']; ?></td>
                        </tr>
                        <tr>
                            <th>Gate Security Incharge</th>
                            <td><?php echo ucwords($rw['createdbyname']); ?></td>
                        </tr>
                        <tr>
                            <th>Checkout</th>
                            <td><?php echo $rw['chkouton']; ?></td>
                        </tr>
                        <tr>
                            <th>Gate Security Incharge</th>
                            <td><?php echo ucwords($rw['chkoutbyname']); ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php //if($permA_SA_WEIGHT) {

    ?>

    <!-- Show this div if vehicle type is Material Out -->
    <?php $vtsql = "select *,customers.`name` as custname from dispatch INNER JOIN customers ON customers.cust_id = dispatch.custid where vehicleid=$vid";
    //echo $vtsql;
    $vtqq = mysqli_query($conn,$vtsql);
    
    //if(mysqli_num_rows($vtqq)>0){
    while($vtrw = mysqli_fetch_assoc($vtqq)){ ?>
    <div class="article mt-4">
        <div class="article-heading flex-heading">
            <h5 class="text-center">Weight &amp; Material (Dispatch Slip No. : <?php echo $vtrw['dispatchid']; ?>)</h5>
            <a href="main.php?paction=dispatch_slip_print_customer&vid=<?php echo $vid; ?>&custid=<?php echo $vtrw['custid'];?>&dispid=<?php echo $vtrw['dispatchid']; ?>" class="btn action-btn btn-success no-print"><i class="bi bi-printer"></i></a>
        </div>
        <div class="article-content">
            <?php $wsql = "select * from weight where dispatchid='$vtrw[dispatchid]' and vhid=$vid ";
                $wqq = mysqli_query($conn,$wsql);
                $wqr = mysqli_fetch_assoc($wqq);
                
            ?>
            <h6><strong>Party Name: </strong><?php echo strtoupper($vtrw['custname']); ?></h6>
            <h6><strong>Vehicle Tare Weight: </strong><?php echo (empty($wqr['wht'])?'':$wqr['wht'].' Tons');?>  </h6>
            <?php if(!empty($wqr['whtslip'])){ ?>
                <a href="assets/images/weight/<?php echo $wqr['whtslip']; ?>" data-modal-heading="Loaded Weightment Slip" class="attachemntView btn action-btn btn-success ml-3"><i class="bi bi-eye"></i> Weigh Slip</a></h6>
            <?php } ?>
            <div class="table-responsive">
            <table class="table table-bordered table-xl">
                    <thead>
                        <tr>
                            <th rowspan="2">Order No.</th>
                            <th rowspan="2">Product</th>
                            <th rowspan="2">Size/Dia(mm)</th>
                            <th rowspan="2">Length</th>
                            <th rowspan="2">Grade</th>
                            <th rowspan="2">Brand</th>
                            <!-- <th class="text-center" colspan="2">Weight</th>
                            <th class="text-center" colspan="2">Pcs Count</th> -->
                            <th class="text-center" colspan="2">Planned</th>
                            <th class="text-center" colspan="2">Actual</th>
                            <th class="text-center" colspan="2">Pricing</th>
                        </tr>
                        <tr>
                            <!-- <th>Planned</th>
                            <th>Actual</th>
                            <th>Planned</th>
                            <th>Actual</th> -->
                             <th>Weight</th>
                            <th>Pcs/ Bundles</th>
                            <th>Weight</th>
                            <th>Pcs/ Bundles</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody><?php 
                                $lsql="SELECT
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
                                saleordersizes.soprice,
                                sizes.currentstock
                                FROM
                                dispatch_item
                                INNER JOIN saleordersizes ON saleordersizes.sosize = dispatch_item.sosize
                                INNER JOIN grade ON grade.gid = dispatch_item.gradeid
                                INNER JOIN brands ON brands.brid = dispatch_item.brandid
                                INNER JOIN products ON products.prid = dispatch_item.sopid
                                INNER JOIN sizes ON sizes.sid = dispatch_item.sizeid
                                where dispid='$vtrw[dispatchid]' and vid='$vid'";
                                
                        
                                $lqq=mysqli_query($conn,$lsql);
                                $gptotalwt = 0; // planned weight total
                                $gatotalwt = 0; // actual weight total
                                $gptotalpc = 0; // planned pcs total
                                $gatotalpc = 0; // actual pcs total
                                $gbtotal = 0; // final bundles
                                $gpcstotal = 0; // final pcs
                                $totalcost = 0; // sum of all subtotals
                                while($lrw = mysqli_fetch_assoc($lqq)){
                            ?>
                        <tr>
                            <td><?php echo $lrw['slid']; ?></td>
                            <td><?php echo $lrw['productname']; ?></td>
                            <td><?php echo $lrw['size']; ?></td>
                            <td><?php echo $lrw['stdlength'];
                            switch($lrw['lengthtype']){
                                case 1:
                                    echo ' Ft.';
                                    break;
                                case 2:
                                    echo ' Mtr.';
                                    break;
                            }
                            ?></td>
                            <td><?php echo $lrw['grade']; ?></td>
                            <td><?php echo $lrw['brandname']; ?></td>
                            <td><?php 
                            if(!empty($lrw['weighttodispatch'])){
                                echo $lrw['weighttodispatch'].' Tons'; $gptotalwt +=$lrw['weighttodispatch'];
                            }
                             ?></td>
                            <td><?php //echo $lrw['finalweight']; $gatotalwt +=$lrw['finalweight'];
                            if(!empty($lrw['pctodispatch'])){
                                echo $lrw['pctodispatch']; $gptotalpc +=$lrw['pctodispatch'];
                                if($lrw['bundleweight']=='0'){
                                    echo ' Pcs.';
                                }else{
                                    echo ' Bundles';
                                }
                            } ?> </td>
                            <td><?php
                            // if(!empty($lrw['pctodispatch'])){
                            //     echo $lrw['pctodispatch']; $gptotalpc +=$lrw['pctodispatch'];
                            //     if($lrw['bundleweight']=='0'){
                            //         echo ' Pcs.';
                            //     }else{
                            //         echo ' Bundles';
                            //     }
                            // }

                            echo $lrw['finalweight']; $gatotalwt +=$lrw['finalweight'];
                            ?></td>
                            <td><?php 
                                if(!empty($lrw['actualpcs'])){
                                    echo $lrw['actualpcs']; $gatotalpc +=$lrw['actualpcs']; 
                                    if($lrw['bundleweight']=='0'){
                                        echo ' Pcs.';
                                    }else{
                                        echo ' Bundles';
                                    }
                                }?>
                            </td>

                            <!-- <td>1</td>
                            <td>10</td> -->
                            <!-- <td><?php #echo $lrw['finalbundles']; $gbtotal +=$lrw['finalbundles'];?></td>
                            <td><?php #echo $lrw['finalpcs']; $gpcstotal +=$lrw['finalpcs']; ?></td> -->
                            <td><?php  echo $lrw['soprice'];?></td>
                            <td><?php if(!empty($lrw['finalweight'])){
                                echo $subtotal1=round(($lrw['finalweight']*$lrw['soprice']),2); 
                                $totalcost+=$subtotal1;
                            } ?></td>
                        </tr>
                        <?php } ?>
                        <tr>
                            <td colspan="12"></td>
                        </tr>
                        <tr>
                            <th colspan="6" class="text-right">Total</th>
                            <td colspan=""><?php echo $gptotalwt; ?> Tons</td>
                            <td colspan=""></td>
                            <td colspan=""><?php echo $gatotalwt; ?></td>
                            <td colspan=""></td>
                            <td colspan=""></td>
                            <td colspan=""><?php echo $totalcost; ?></td>
                        </tr>
                        <tr>
                            <th colspan="11" class="text-right">GST 18%</th>
                            <td colspan=""><?php
                            $gstamt=round((($totalcost * 18) / 100),2);
                            echo $gstamt;
                            ?></td>
                        </tr>
                        <tr>
                            <th colspan="11" class="text-right">Grand Total</th>
                            <td colspan=""><?php
                            echo ($totalcost+$gstamt);
                            ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <h6>Vehicle Loaded Weight: 
            <?php if(!empty($wqr['emtywght'])){
                    echo $wqr['emtywght'];
                    echo ' Tons'; 
                }
                if(!empty($wqr['emtywghtslip'])){ ?>
                    <a href="assets/images/weight/<?php echo $wqr['emtywghtslip']; ?>" data-modal-heading="Loaded Weightment Slip" class="attachemntView btn action-btn btn-success ml-3"><i class="bi bi-eye"></i> Weigh Slip</a></h6>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
<?php 
 
}?>
</div>
<div class="modal fade" id="attachemntView">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Attachment</h4>
                <button type="button" class="close" data-dismiss="modal"><i class="bi bi-x"></i></button>
            </div>
            <div class="modal-body text-center">
                <img class="img-fluid" src="assets/images/vital-steel-bars-llp-logo.png">
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.attachemntView', function(e){
        e.preventDefault();
        let src = $(this).attr('href');
        let modalHeading = $(this).data('modal-heading');
        let slipViewModal = $('#attachemntView');
        slipViewModal.find('img').attr('src', src);
        slipViewModal.find('.modal-title').text(modalHeading)
        slipViewModal.modal();
    });
</script>
