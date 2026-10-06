<?php $perm=check_permission("A");
if(isset($_GET['vid']) && !empty($_GET['vid']) &&is_numeric($_GET['vid'])){
    // check out gate
	$vid = mysqli_real_escape_string($conn, $_GET['vid']);
    if(isset($_GET['chkout']) && !empty($_GET['chkout']) &&is_numeric($_GET['chkout'])){
        $updatesql = "update gate set gatestatus='2',chkouton='$createdon' , chkoutby = '$createdby' where gid=$_GET[vid] and gid not in(select vhid from weight)";
        $upqq = mysqli_query($conn,$updatesql);
        if(mysqli_affected_rows($conn)>0){
            echo '<script>window.location.href=main.php?msg=Vehicle checkedout successfully.</script>';
        }else{
            echo '<script>window.location.href=main.php?msg=Vehicle is already in process.</script>';
        }
    }
	
	if(isset($_GET['av']) && !empty($_GET['av']) && is_numeric($_GET['av'])){
		$av = mysqli_real_escape_string($conn, $_GET['av']);
		//var_dump($_GET['av']);
		if($av==$vid){
			$admingateupdate="update gate set adminvehout='1' where gid ='$av'";
			mysqli_query($conn,$admingateupdate);
		}
	}
}



if($_GET['msg']){
    $msg=$_GET['msg'];
}

if($_GET['errmsg']){
    $errmsg=$_GET['errmsg'];
}

    $whr=1;
    $prwhr=1;

?>

<div class="main-content-inner">
<?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
    <?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
    <?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
    <?php
    if($perm){
    ?>
<?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
    <?php
    if($perm){
    ?>
    <div class="page-header">
        <h1 class="page-heading h6 ebold">Dashboard</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item">Dashboard</li>
        </ul>
    </div>

    <div class="row">
        <div class="col-xl-3 col-lg-4 py-3">
            <a href="main.php?paction=stock" class="card d-block shadow-sm">
                <div class="card-body py-4">
                    <h6 class="bold mb-3">Total Stock</h6>
                    <h2 class="ebold mb-0 pb-0">
                    <?php 
                        $tssql="select sum(currentstock) as availableqty from sizes";
                        $tssqlqq=mysqli_query($conn,$tssql);
                        $tsqlrw=mysqli_fetch_assoc($tssqlqq)['availableqty'];
                        echo $tsqlrw;
                    ?>
                     Tons</h2>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-4 py-3">
            <a href="main.php?paction=client_report" class="card d-block shadow-sm">
                <div class="card-body py-4">
                    <h6 class="bold mb-3">Pending Orders</h6>
                    <h2 class="ebold mb-0 pb-0"><?php
                    $pendingsql = "select sum(weightintons) as pw,sum(dispatched) as pd from saleordersizes where completed ='0'";
                    $pendingsqlqq = mysqli_query($conn,$pendingsql);
                    $prw = mysqli_fetch_assoc($pendingsqlqq);
                    $pending = ($prw['pw']-$prw['pd']);
                    echo $pending;
                    ?> Tons</h2>
                </div>
            </a>
        </div>
        <div class="col-xl-3 col-lg-4 py-3">
            <a href="main.php?paction=client_report" class="card d-block shadow-sm">
                <div class="card-body py-4">
                    <h6 class="bold mb-3">Pending Deals</h6>
                    <h2 class="ebold mb-0 pb-0"><?php
                    $pendingdsql = "select sum(orderqty) as tqty,sum(dispatchedqty) as dqty from dealorder  where status=0";
                    //echo $pendingdsql;
                    $pendingdsqlqq = mysqli_query($conn,$pendingdsql);
                    $prwd = mysqli_fetch_assoc($pendingdsqlqq);
                    $pendingd = ($prwd['tqty']-$prwd['dqty']);
                    echo $pendingd;
                    ?> Tons</h2>
                </div>
            </a>
        </div>
    </div>
    
    <div class="article">
        <div class="article-heading flex-heading">
            <h5>Vehicles in Yard</h5>
            <div>
                <a href="main.php?paction=vehicle_history" class="btn btn-basic btn-sm">Vehicle History</a>
            </div>
        </div>
        <div class="article-content container-max">
            <ul class="list-unstyled color-scheme">
                <li>
                    <span class="color skin"></span>
                    <span class="label">Vehicle In</span>
                </li>
                <li>
                    <span class="color yellow"></span>
                    <span class="label">Pending Last Weight</span>
                </li>
                <li>
                    <span class="color green"></span>
                    <span class="label">Ready For Final Dispatch</span>
                </li>
            </ul>
            <div class="row">
                <!-- loop starts here -->
                <?php 
                //$tsql = "SELECT gate.tokenid,gate.gid,gate.createdon,gate.vehicleno,(select custid from dispatch where weight is null and vehicleid = 12 order by dispatchid desc limit 1) as custid,(select dispatchid from dispatch where weight is null and vehicleid = 12 order by dispatchid desc limit 1) as dispid FROM gate WHERE gate.gatestatus = '1'";
                //$tsql = "SELECT gate.tokenid,gate.gid,gate.createdon,gate.vehicleno FROM gate WHERE gate.gatestatus = '1'";

                //$tsql = "SELECT gate.tokenid,gate.gid,gate.createdon,gate.vehicleno,weight.wht FROM gate INNER JOIN weight ON weight.vhid = gate.gid WHERE gate.gatestatus = '1' and weight.wht is not null ";
                $tsql = "SELECT DISTINCT gate.tokenid,gate.gid,gate.createdon,gate.vehicleno,gate.adminvehout,customers.`name`,dispatchmarkedcompleted as dc FROM gate
                INNER JOIN customers ON customers.cust_id = gate.cid  WHERE gate.gatestatus = '1'";
                
                $tsqlq=mysqli_query($conn,$tsql) or die(mysqli_error($conn));
                
                while($trw = mysqli_fetch_assoc($tsqlq)){
                    
				?>
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="vehicle-thumb <?php
                    //if vehicle in and no dispatch for vehicle token then 
                    $chksql1 = "select * from dispatch where vehicleid='$trw[gid]'";
                    $chkqq1 = mysqli_query($conn,$chksql1);
                    $chkcnt1 = mysqli_num_rows($chkqq1);
                    if($chkcnt1=='0'){//if no dispatch slip exists for vechile id
                        echo 'thumb-skin';
                    }else{ //if dispatch slip exists for vehicle id
                        $chksql2 = "select * from dispatch where vehicleid=$trw[gid] and weight is NULL";
                        //$chksql2 = "select * from dispatch_item where vid = '$trw[gid]' and finalweight is null";
                        $chkqq2 = mysqli_query($conn,$chksql2);
                        $chkcnt2 = mysqli_num_rows($chkqq2);
                        if($chkcnt2>'0'){ // if dispatch slip for vehicle exists with no 
                            echo 'thumb-yellow';
                        }else{
                            echo 'thumb-green';     
                        }   
                    }

                    ?>">
                        <div class="vehicle-thumb-header">
                            <p><span>Token Generated</span><?php echo $trw['createdon']; ?></p>
                            <div>
                            <?php if($trw['dc']=='0'){ ?>
                                <?php if(!empty($trw['custid']) && !empty($trw['dispid'])){ ?>
                                    <a href="main.php?paction=dispatch_plan&vid=<?php echo $trw['gid']; ?>&custid=<?php echo $trw['custid']; ?>&dispid=<?php echo $trw['dispid']; ?>" class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                <?php }else{ ?>
                                <a href="main.php?paction=dispatch_plan&vid=<?php echo $trw['gid']; ?>" class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>
								<?php 
									$chksql23 = "select * from dispatch where vehicleid='$trw[gid]'";
									$chkqq23 = mysqli_query($conn,$chksql23);
									$chkcnt23 = mysqli_num_rows($chkqq23);
									if($chkcnt23=='0' && $trw['adminvehout']=='0'){
								?>
								<!-- av reffers to vehicle id which admin allowed to be returned -->
								<a href="main.php?vid=<?php echo $trw['gid']; ?>&av=<?php echo $trw['gid']; ?>" class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a>
									<?php } ?>
                                <!-- <a href="main.php?paction=dispatch_plan&vid=<?php #echo $trw['gid']; ?>" class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a> -->
                                <?php } ?>
                                <!-- <a href="main.php?paction=dashboard_gate&vcin=<?php #echo $trw['gid']; ?>" class="btn action-btn btn-danger">Checkin</a> -->
                                <?php }else{
                                    echo '<strong>Completed</strong>';
                                } ?>
                            </div>
                        </div>
						
                        <a class="view" href="main.php?paction=vehicle_view&vid=<?php echo $trw['gid']; ?>">
                            <div class="vehicle-thumb-body">
                                <h6 class="semi"><?php echo $trw['name']; ?></h6>
                            </div>
                            <div></div>
                            <div class="vehicle-thumb-footer">
                                <h6 class="semi">Vehicle No.<span><?php echo $trw['vehicleno']; ?></span></h6>
                                <h6 class="semi">Token No.<span><?php echo $trw['tokenid']; ?></span></h6>
                            </div>
                        </a>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
    <div class="article mt-4">
        <div class="article-heading flex-heading">
            <h5>Vehicles Outside Yard</h5>
        </div>
        <div class="article-content container-max">
            <div class="row">
                <!-- loop starts here -->
                <?php
                // $tsql = "SELECT gate.gid, gate.createdon, gate.vehicleno, gate.cid, admin.`name` FROM gate
                // INNER JOIN admin ON admin.admin_id = gate.cid where gatestatus='0' and admin.typ='ve' or admin.typ='cu'";
                $tsql = "SELECT gate.gid,gate.tokenid, gate.createdon, gate.vehicleno, customers.`name` FROM gate
                INNER JOIN customers ON customers.cust_id = gate.cid WHERE gate.gatestatus = '0'";
                //echo $tsql;
                $tsqlq=mysqli_query($conn,$tsql) or die(mysqli_error($conn));
                //echo $tsql;
                while($trw = mysqli_fetch_assoc($tsqlq)){
                    //var_Dump($trw);
                ?>
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="vehicle-thumb thumb-light-blue">
                        <div class="vehicle-thumb-header">
                            <p><span>Token Generated</span><?php echo $trw['createdon']; ?></p>
                            <div>
                                <a href="main.php?chkout=1&vid=<?php echo $trw['gid']; ?>" class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a>
                            </div>
                        </div>
                        <a class="view" href="main.php?paction=vehicle_view&vid=<?php echo $trw['gid']; ?>">
                            <div class="vehicle-thumb-body">
                                <h6 class="semi"><?php echo $trw['name']; ?></h6>
                            </div>
                            <div class="vehicle-thumb-footer">
                                <h6 class="semi">Vehicle No.<span><?php echo $trw['vehicleno']; ?></span></h6>
                                <h6 class="semi">Token No.<span><?php echo $trw['tokenid']; ?></span></h6>
                            </div>
                        </a>
                    </div>
                </div>
                <?php  }?>
                <!-- loop ends here -->
            </div>
        </div>
    </div>



    
    <?php } ?>





</div>
<?php

$whr = 1;
if(isset($_GET['customer']) && is_numeric($_GET['customer']) && !empty($_GET['customer'])){
    $custid = mysqli_real_escape_string($conn,$_GET['customer']);
    $whr .=" and saleorder.cid=$custid";
}
?>
<div class="main-content-inner">
<?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
    <?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
    <?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
    <?php
    if($perm){
    ?>
    <br>

    <div class="article">
        <div class="article-heading flex-heading no-print">
            <h5 class="text-center">Client Wise Report</h5>
            <div class="">

            </div>
        </div>
        <div class="table-responsive mt-3 mt-lg-0">
                <table class="table table-bordered report">
                    <thead>
                        <tr>
                            <th style="width: 12rem">Customer</th>
                            <th>
                                <span class="size">Order No. / Product / Size</span><br/>
                                <span class="pending">Ordered / Pending</span><br/>
                                <span class="pending">Rate / Grade</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                           
                            $ordersql1 = "select DISTINCT cust_id,name from customers,saleorder where 1 and customers.cust_id=saleorder.cid and ((DATE(saleorder.`smarkedcompleted`)= Date(NOW()) and saleorder.`status`=1) or saleorder.`status`=0) order by name asc";
                            $ordersql2 = "select DISTINCT cust_id,name from customers,dealorder where $whr and  customers.cust_id=dealorder.cid  and ((DATE(dealorder.`markedcompleted`)= Date(NOW()) and dealorder.`status`=1) or dealorder.`status`=0)  order by name asc";
                           
                            $orderqq1 = mysqli_query($conn,$ordersql1);
                            $orderqq2 = mysqli_query($conn,$ordersql2);
                            $a = [];
                            $b = [];
                           
                            
                            // while($orw1 = mysqli_fetch_assoc($orderqq1)){
                            //     if(!in_array($orw1['cust_id'],$a)){
                            //         array_push($a, $orw1['cust_id']);
                            //     }
                            // }
                            
                            // while($orw2 = mysqli_fetch_assoc($orderqq2)){
                            //     if(!in_array($orw2['cust_id'],$a)){
                            //         array_push($a, $orw2['cust_id']);
                            //     }
                            // }
						    //echo "<pre>";print_r($a);echo "</pre>";
						    //sort($a);
						    while($orw1 = mysqli_fetch_assoc($orderqq1)){
                        	    if(!in_array($orw1['cust_id'],$a)){
                            		//array_push($a, $orw1['cust_id']);
                            		array_push($a, $orw1);
                            	}
                            }
                            
                            while($orw2 = mysqli_fetch_assoc($orderqq2)){
                            	if(!in_array($orw2['cust_id'],$a)){
                            		//array_push($a, $orw2['cust_id']);
                            		array_push($a, $orw2);
                            	}
                            }
						foreach($a as $orw){
                        ?>
                        <tr>
                            <td><?php
                            $cnamesql = "select name from customers where cust_id=$orw[cust_id]";
                            $cnameqq = mysqli_query($conn,$cnamesql);
                            $cnamerw = mysqli_fetch_assoc($cnameqq);

                            echo strtoupper( $cnamerw['name']);//strtoupper($orw); ?></td>
                            <td class="px-0">
                                <!-- deals for current client starts here -->
								<?php 
                                $dealidsql = "SELECT
                                dealorder.slid AS dealid,
                                dealorder.orderqty,
                                dealorder.`status`,
                                dealorder.orderremarks,
                                dispatchedqty as qtydispatched
                                from dealorder where cid=$orw[cust_id] and ((DATE(dealorder.`markedcompleted`)= Date(NOW())  and dealorder.`status`=1) or dealorder.`status`=0)";
                                
                                $dealidqq = mysqli_query($conn,$dealidsql);
                                $dealcnt = mysqli_num_rows($dealidqq);
                                
                                if($dealcnt >0){
                                    $prwhr=1;
                                if(isset($_GET['product']) && is_numeric($_GET['product']) && !empty($_GET['product'])){
                                    $productid = mysqli_real_escape_string($conn,$_GET['product']);
                                    $prwhr .="  and products.prid=$productid";
                                }
                                if(isset($_GET['size']) && is_numeric($_GET['size']) && !empty($_GET['size'])){
                                    $sizeid = mysqli_real_escape_string($conn,$_GET['size']);
                                    $prwhr .=" and sizes.sid=$sizeid ";
                                }
                                while($dealidrw=mysqli_fetch_assoc($dealidqq)){
                                
                                $dealsql ="SELECT
                                dealitems.ditemid,
                                dealitems.dealid,
                                dealitems.itemid,
                                dealitems.gradeid,
                                dealitems.price,
                                dealitems.qty,
                                dealitems.qtyunit,
                                dealitems.qtydispatched,
                                products.productname,
                                dealorder.cid,
                                dealorder.slid,
                                dealorder.`status`,
                                dealitems.remarks,
                                grade.grade
                                FROM
                                dealitems
                                INNER JOIN products ON products.prid = dealitems.itemid
                                INNER JOIN dealorder ON dealorder.slid = dealitems.dealid
                                INNER JOIN grade ON grade.gid = dealitems.gradeid
                                where $prwhr and dealorder.cid=$orw[cust_id] and dealid='$dealidrw[dealid]' order by dealitems.ditemid asc";
                                $dealqq = mysqli_query($conn,$dealsql);
                                $dealcnt = mysqli_num_rows($dealqq);
                                if($dealcnt>0){
                                    $dealsum="select sum(dispatchqty) as qtydispatched from dealdispatch where dealid='$dealidrw[dealid]'";
                                    $dealsumqq=mysqli_query($conn,$dealsum);
                                    $dealsum=mysqli_fetch_assoc($dealsumqq);
                                    $dealpending=$dealidrw['orderqty']-$dealsum['qtydispatched'];
                                    //$dealpending = $dealidrw['orderqty']-$dealidrw['qtydispatched'];
                                ?>
                                <div class="container-fluid px-0  <?php
                                    if($dealidrw['status']=='1'){
                                        echo ' bg-lightgreen ';
                                    }else{
                                        echo ' bg-light ';
                                    }
                                    ?>">
                                    <ul class="list-unstyled d-inline-block orderrow">
                                        
                                       <?php if(!empty($dealidrw['orderremarks'])){ ?>
                                            <div class="d-block w-100 m-1">
                                                <strong>Remarks: </strong><?php echo $dealidrw['orderremarks'];?>
                                            </div>
                                        <?php } ?>
                                        <li class="d-md-flex w-100 px-3 py-1" style="width: 100%">

                                            <div class="d-flex pr-sm-3">
                                                <h6 class="bold mb-0">Deal Qty : </h6><p class="mb-md-0"><?php echo $dealidrw['orderqty'];?> Tons</p>
                                            </div>
                                            <div class="d-flex pr-sm-3">
                                                <h6 class="bold mb-0">Dispatched Qty : </h6> <p class="mb-md-0"><?php echo (!empty($dealsum['qtydispatched'])?$dealsum['qtydispatched']:'0');?> Tons</p>
                                            </div>
                                            <div class="d-flex">
                                                <h6 class="bold mb-0">Pending Qty : </h6> <p class="mb-md-0"><?php echo $dealpending; #echo ($dealidrw['orderqty']-$dealidrw['qtydispatched']);?> Tons</p>
                                            </div>
                                            
                                        </li>
                                        <?php while($szrw = mysqli_fetch_assoc($dealqq)){
                                            $pending1 = (!empty($szrw['qtydispatched'])?$szrw['qtydispatched']:'0');
                                            ?>
                                        <li>
                                            <div class="reportThumb my-1 mx-2">
                                                <?php if(!empty($szrw['remarks'])){?>
                                                    <span class="mb-0"><strong>Remarks:</strong> <?php echo $szrw['remarks']; ?></span>
                                                <?php } ?>
                                                <span><b><?php echo $szrw['dealid']; ?></b> / <?php echo ucwords($szrw['productname']); ?></span>
                                                <span class="mb-0"><i class="fa fa-rupee-sign"></i><?php echo $szrw['price']; ?> / <?php echo ucwords($szrw['grade']); ?></span>
                                            </div>
                                        </li>
                                        <?php } ?>
                                        <!-- Dispatch vechile history start -->
                                        <?php
                                        $dispdetails = "select dealdispatchid,dealid,vehicleno,dispatchqty,dealdispatch.createdon from dealdispatch where dealid=$dealidrw[dealid]";
                                        
                                        $dispdetailsqq = mysqli_query($conn,$dispdetails);
                                        $ddcnt = mysqli_num_Rows($dispdetailsqq);
                                        
                                        if($ddcnt>0){
                                        ?>
                                        <div class="d-block w-100">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <th>S.No.</th>
                                                    <th>V.No.</th>
                                                    <th>Qty.</th>
                                                    <th>Dated</th>
                                                </tr>
                                                <?php  $y=1;
                                                while($ddrw = mysqli_fetch_assoc($dispdetailsqq)){ ?>
                                                <tr>
                                                    <td><?php echo $y; ?></td>
                                                    <td><?php echo strtoupper($ddrw['vehicleno']); ?></td>
                                                    <td><?php echo $ddrw['dispatchqty']; ?></td>
                                                    <td><?php
                                                        if ($ddrw['createdon'] != '0000-00-00 00:00:00' || empty($ddrw['createdon'])) {
                                                            echo date('d-m-Y', strtotime($ddrw['createdon']));
                                                        } ?>
                                                    </td>
                                                </tr>
                                                <?php $y++; } ?>
                                            </table>
                                        </div>
                                        <?php } ?>
                                        <!-- Dispatch vechile history ends -->
                                    </ul>
                                </div>
                                <?php }
                                } 
                            } ?>
                                <!-- deals for current client ends here -->
                                <!-- Put break line between deals and orders items for current client -->
                                <hr>
                                <!-- order for current client starts here -->
                                <?php
                                $saleordersql = "select * from saleorder where cid='$orw[cust_id]' and ((DATE(saleorder.`smarkedcompleted`)= DATE(NOW()) and saleorder.`status`=1) or saleorder.`status`=0)";
                                //and ((DATE(saleorder.`smarkedcompleted`)= DATE(NOW()) and saleorder.`status`=1) or saleorder.`status`=0)
                                $saleorderqq = mysqli_query($conn,$saleordersql);
                                while($saleorderrw = mysqli_fetch_assoc($saleorderqq)){
                                    $dispsql = "SELECT DISTINCT dispatch_item.dispid,dispatch.weight FROM dispatch_item
                                    INNER JOIN dispatch ON dispatch.dispatchid = dispatch_item.dispid
                                    where date(dispatch_item.createdon)=date(CURDATE()) and weight is null and slid=$saleorderrw[slid]";
                                    $dispqq = mysqli_query($conn,$dispsql);
                                     ?>
                                    <div class="container-fluid px-2 <?php
                                    if($saleorderrw['status']=='1'){
                                        echo ' bg-lightgreen ';
                                    }else if(mysqli_num_rows($dispqq)>0 && $saleorderrw['status']!='1'){
                                        echo 'thumb-yellow ';
                                    }else{
                                        echo ' bg-light ';
                                    }
                                    ?>">
                                        <ul class="list-unstyled d-inline-block orderrow"><!-- Loop this with new order -->
                                         <?php if(!empty($saleorderrw['remarks'])){
                                            echo '<span  class="d-block w-100 m-1"><strong> Remarks:</strong>'.$saleorderrw['remarks'].'</span>';
                                        } ?>
                                        <?php echo (empty($saleorderrw['smarkedcompleted'])?'':'<strong class="d-block w-100 text-center lead">COMPLETED</strong>'); ?>
                                        <?php 
                                        
                                $sizesql = "SELECT saleordersizes.sosize,
                                saleordersizes.weightintons, 
                                saleordersizes.dispatched, 
                                saleordersizes.sosaleid, 
                                saleordersizes.todispatchwegith,
                                saleordersizes.todispachpcs, 
                                saleordersizes.completed,
                                saleordersizes.soprice,
                                saleorder.cid,
                                brands.brandname, 
                                grade.grade, 
                                sizes.size,
                                saleorder.cid,
                                products.productname,
                                saleordersizes.qty,
                                saleordersizes.qtytype,
                                sizes.mtweight,
                                sizes.ftweight,
                                sizes.stdlength,
                                sizes.lengthtype,
                                sizes.bundleweight,
                                saleordersizes.itemremarks
                                FROM saleordersizes 
                                INNER JOIN saleorder ON saleorder.slid = saleordersizes.sosaleid 
                                INNER JOIN products ON products.prid = saleordersizes.soprid 
                                INNER JOIN brands ON brands.brid = saleordersizes.sobrandid
                                INNER JOIN grade ON grade.gid = saleordersizes.sogradeid 
                                INNER JOIN sizes ON sizes.sid = saleordersizes.sosizeid 
                                where $prwhr and saleorder.cid='$orw[cust_id]' and ((DATE(saleordersizes.`smarkedcompleted`)=DATE(NOW()) and saleordersizes.`completed`=1) or saleordersizes.`completed`=0) and saleorder.slid ='$saleorderrw[slid]' order by saleordersizes.sosize,products.productname asc";
                                   
                                $sizeqq = mysqli_query($conn,$sizesql); ?>
                                <?php while($szrw = mysqli_fetch_assoc($sizeqq)){
                                    
                                    $vhnosql = "SELECT DISTINCT dispatch_item.vid, gate.vehicleno FROM dispatch_item
                                        INNER JOIN gate ON gate.gid = dispatch_item.vid where slid=$szrw[sosaleid] and dispatch_item.sosize=$szrw[sosize]";
                                    $vhnoqq=mysqli_query($conn,$vhnosql);
                                    $vhnocnt=mysqli_num_rows($vhnoqq);
                                    
                                    $colorsql="select sosize from dispatch_item where sosize='$szrw[sosize]' and date(dispatchedon)=CURDATE()";
                                    $colorqq = mysqli_query($conn,$colorsql);
                                    $crow = mysqli_num_rows($colorqq);
                                    if(empty($crow)){
                                        $crow=0;
                                    }
                                    //echo $crow.'<br>';
                                    ?>
                                <li><!-- Repeat this with new Item in Order -->
                                    <div class="reportThumb my-2 mx-1 <?php  
                                    if($szrw['completed']==1){
                                        echo ' bg-lightgreen ';
                                    }
                                    if($crow==1){
                                        echo ' bg-lightgreen ';
                                    }
                                    //(($szrw['completed']==0 || $crow=0)?'':'bg-lightgreen'); ?>">
                                        <?php if(!empty($szrw['itemremarks'])){
                                            echo '<span  class="d-block w-100 m-1"><strong> Remarks:</strong>'.$szrw["itemremarks"].'</span>';
                                        } ?>
                                        <?php echo ($szrw['completed']==1?'<strong class="d-block w-100 text-center lead">COMPLETED</strong>':'');
                                        if($vhnocnt>0){
                                            echo '<span><strong>Vehicle No. </strong>';
                                            while($vhnorw=mysqli_fetch_assoc($vhnoqq)){
                                                echo $vhnorw['vehicleno'].'-';
                                            }
                                            echo '</span>';
                                        }
                                        ?>
                                        <!-- <a href="javascript:cnf(<?php echo $szrw['sosaleid']; ?>, <?php #echo $szrw['sosize'];?>)" data-toggle="tooltip" class="btn action-btn btn-success no-print" title="Mark As Complete"><i class="bi bi-check-lg"></i></a> -->
                                        <!-- <a href="#" data-toggle="tooltip" class="btn action-btn btn-success no-print" title="Mark As Complete"><i class="bi bi-check-lg"></i></a> -->
                                        <span><b><?php echo $szrw['sosaleid']; ?></b> / <?php echo ucfirst($szrw['productname']); ?> / <?php echo $szrw['size']; ?></span>
                                        <span class="mb-0"><?php echo $szrw['qty']; echo getorderunits($szrw['qtytype']);?><?php #echo $szrw['weightintons']; ?> / <?php echo tonstounits($szrw['stdlength'],$szrw['lengthtype'],$szrw['mtweight'],$szrw['ftweight'],($szrw['weightintons']-$szrw['dispatched']),$szrw['qtytype'],$szrw['bundleweight']); echo getorderunits($szrw['qtytype']);?> </span>
                                        <span class="mb-0"><i class="fa fa-rupee-sign"></i><?php echo $szrw['soprice']; ?> / <?php echo $szrw['grade']; ?></span>
                                    </div>
                                </li>
                                <?php } ?>
                                </ul>
                                </div>     
                        <?php  } ?>
                                
                                
                               
                            </td>
                        </tr>
                        <?php }// customer name while loop ends here ?>
                    </tbody>
                </table>
            </div>
    </div>
    
    <!-- <div class="article mt-4">
        <div class="article-heading flex-heading no-print">
            <h5 class="text-center">Customerwise Pending Deals</h5>
            <div class="">
                <!-- <a href="main.php" class="btn btn-basic btn-sm">Dashboard</a>
                <button class="btn btn-basic btn-sm" onclick="window.print()"><i class="bi bi-printer"></i>&nbsp;Print</button>
            </div>
        </div>
       
    </div> -->
    <?php } ?>
</div>