<?php
/**
 * Vehcile outside yard which have status 0
 * Vehicle in yard who have status 1 and status 1 will be only done once weight is done first time
 * Vehicle status will set the 2 when check out is done by gate
 */
$perm=check_permission("GT","A");
$ip =  $_SERVER['REMOTE_ADDR'];
        $queryIp="SELECT * from allow_ip where ip='$ip'";
        $qIp=mysqli_query($conn, $queryIp) or die(mysqli_error($conn));
        $qlist=mysqli_fetch_array($qIp);
        // var_dump($qlist['ip']); die();
        if($qlist['ip'] != $ip & $_SESSION["user_typ"] != 'A'){ 
            echo '<script>window.location.href="main.php?paction=unauthorize&errmsg=Ip address blocked"</script>';
        }else{
if(isset($_GET['vcin']) && is_numeric($_GET['vcin'])){
    $vcin = mysqli_real_escape_string($conn, $_GET['vcin']);
    $uvcin = "update gate set  gatestatus='1', chkinon = '$createdon', chkinby='$createdby' where gid='$vcin'";
    $uvcinq=mysqli_query($conn,$uvcin) or die(mysqli_error($conn));
    //var_dump($uvc);
    if(mysqli_affected_rows($conn)>0){
        echo '<script>window.location.href="main.php?paction=dashboard_gate&msg=Vehicle Checked in Successfully."</script>';
    }else{
        echo '<script>window.location.href="main.php?paction=dashboard_gate&errmsg=Vehicle Checked in Failed."</script>';
    }
}elseif(isset($_GET['vcout']) && is_numeric($_GET['vcout'])){
    $vcout = mysqli_real_escape_string($conn, $_GET['vcout']);
    $uvcout = "update gate set  gatestatus='2', chkouton = '$createdon', chkoutby='$createdby' where gid='$vcout'";
    $uvcoutq=mysqli_query($conn,$uvcout) or die(mysqli_error($conn));
    if(mysqli_affected_rows($conn)>0){
        echo '<script>window.location.href="main.php?paction=dashboard_gate&msg=Vehicle Checked Out Successfully."</script>';
    }else{
        echo '<script>window.location.href="main.php?paction=dashboard_gate&errmsg=Vehicle Check Out Failed."</script>';
    }
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
    <?php
    if($perm){
    ?>
    <div class="page-header">
        <h1 class="page-heading h6 ebold">Dashboard</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Gate</li>
            <li class="breadcrumb-item">Dashboard</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5>Vehicles In Yard</h5>
            <div>
                <a href="main.php?paction=vehicle_history" class="btn btn-basic btn-sm">Vehicle History</a>
                
            </div>
        </div>
        <div class="article-content container-max">
            <div class="row">
                <!-- loop starts here -->
                
                <?php
                
                 $tsql = "SELECT gate.tokenid,gate.gid,gate.dispatchmarkedcompleted,gate.createdon,gate.vehicleno,gate.adminvehout,gate.cid,customers.`name`,customers.typ FROM gate
                 INNER JOIN customers ON customers.cust_id = gate.cid WHERE gate.gatestatus = '1'";

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
                        $chksql2 = "select * from dispatch_item where vid = '$trw[gid]' and finalweight is null";
                        $chkqq2 = mysqli_query($conn,$chksql2);
                        $chkcnt2 = mysqli_num_rows($chkqq2);
                        if($chkcnt2 > '0'){
                            echo 'thumb-yellow';
                        }else{
                            echo 'thumb-green';     
                        }
                        
                    }
                    
                    // if any dispatch slip with no second weight then 
                    // echo 'thumb-yellow';
                    // if all dispatch slip with final weight is done
                    // echo 'thumb-green';

                    ?>">
                        <div class="vehicle-thumb-header">
                            <p><span>Token Generated</span><?php echo $trw['createdon']; ?></p>
                            <div>
                                <a href="main.php?paction=material_in&vid=<?php echo $trw['gid']; ?>" class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                <?php 
                                //var_dump($trw['dispatchmarkedcompleted'].' || '.$trw['adminvehout']);
                                
                                if($trw['dispatchmarkedcompleted']==1 || $trw['adminvehout']=='1'){ ?>
                                <a href="main.php?paction=dashboard_gate&vcout=<?php echo $trw['gid']; ?>" class="btn action-btn btn-danger">Checkout</a>
                                <?php } ?>
                            </div>
                        </div>
                        <a class="view" href="main.php?paction=vehicle_view&vid=<?php echo $trw['gid']; ?>">
                            <div class="vehicle-thumb-body">
                                <h6 class="med"><?php echo $trw['name'];?></h6>
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
    <div class="article mt-4">
        <div class="article-heading flex-heading">
            <h5>Vehicles Outside Yard</h5>
        </div>
        <div class="article-content container-max">
            <div class="row">
                <!-- loop starts here -->
                <?php
                
                //$tsql = "SELECT gate.gid,gate.createdon,gate.vehicleno FROM gate WHERE gate.gatestatus = '0'";
                $tsql = "SELECT gate.gid,gate.tokenid, gate.createdon, gate.vehicleno, customers.`name` FROM gate
                INNER JOIN customers ON customers.cust_id = gate.cid WHERE gate.gatestatus = '0'";
                
                $tsqlq=mysqli_query($conn,$tsql) or die(mysqli_error($conn));
                
                while($trw = mysqli_fetch_assoc($tsqlq)){
                    
                ?>
                <div class="col-xl-3 col-lg-4 col-sm-6">
                    <div class="vehicle-thumb thumb-light-blue">
                        <div class="vehicle-thumb-header">
                            <p><span>Token Generated</span><?php echo $trw['createdon']; ?></p>
                            <div>
                                <a href="main.php?paction=material_out&vid=<?php echo $trw['gid']; ?>" class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                <a href="main.php?paction=print_token&tokenid=<?php echo $trw['gid']; ?>" class="btn action-btn btn-success"><i class="bi bi-printer"></i></a>
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
    <?php } } ?>
</div>

<script>
	$(document).on('click','.statusToggle',function(){
        $(this).toggleClass('active inactive');
        $(this).children('.fa').toggleClass('fa-toggle-on fa-toggle-off');
        let id = $(this).data('id');
        let action = "changestatus";
        $.ajax({  
            type:'post',
            url:"Ajax.php",
            data:{uid:id,doAction:action},
            success:function(data){
                console.log(data);
                location.reload(true); 
            }
        });
    });
</script>
