<?php $perm=check_permission("A","DP");
    $ip =  $_SERVER['REMOTE_ADDR'];
        $queryIp="SELECT * from allow_ip where ip='$ip'";
        $qIp=mysqli_query($conn, $queryIp) or die(mysqli_error($conn));
        $qlist=mysqli_fetch_array($qIp);
        // var_dump($qlist['ip']); die();
        if($qlist['ip'] != $ip & $_SESSION["user_typ"] != 'A'){ 
            echo '<script>window.location.href="main.php?paction=unauthorize&errmsg=Ip address blocked"</script>';
        }else{
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
                LEFT JOIN customers ON customers.cust_id = gate.cid WHERE gate.gatestatus = '0'"; 
                $tsqlq=mysqli_query($conn,$tsql) or die(mysqli_error($conn)); 
                while($trw = mysqli_fetch_assoc($tsqlq)){ 
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

    
    <!-- <div class="article mt-4">
        <div class="article-heading flex-heading no-print">
            <h5 class="text-center">Customerwise Pending Deals</h5>
            <div class="">
                <!-- <a href="main.php" class="btn btn-basic btn-sm">Dashboard</a>
                <button class="btn btn-basic btn-sm" onclick="window.print()"><i class="bi bi-printer"></i>&nbsp;Print</button>
            </div>
        </div>
       
    </div> -->
    <?php } } ?>
</div>