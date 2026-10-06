<?php

$perm=check_permission("A");
$vid = mysqli_real_escape_string($conn, $_GET['vid']);
if(isset($_GET['vid'])){
    
    $vinfo = "SELECT gate.gid, gate.vehicleno, gate.vehicletype, gate.transport, gate.drivername, gate.drivermobile,
    gate.efrom, gate.gatestatus, gate.chkinon, gate.chkinby, gate.dslip, gate.chkouton, gate.chkoutby, gate.createdon,
    gate.createdby, gate.modifiedon, gate.modifiedby from gate where gid=$vid ";
    //echo $vinfo;
    $vqq = mysqli_query($conn,$vinfo);
    $vrw = mysqli_fetch_assoc($vqq);
    //var_Dump($vrw);

    if($_POST['doAction']=='newvehicle'){
        //var_Dump($_POST);
        //$cid = mysqli_real_escape_string($conn, $_POST['customer']);
        $vehicleno = mysqli_real_escape_string($conn, $_POST['vehicleno']);
        $vehicletype = mysqli_real_escape_string($conn, $_POST['vehicletype']);
        $transport = mysqli_real_escape_string($conn, $_POST['transport']);
        $drivername = mysqli_real_escape_string($conn, $_POST['driver']);
        $drivermobile = mysqli_real_escape_string($conn, $_POST['mob']);
        $efrom = '1'; //marterial in
        // $token = "insert into gate set
        // cid = '$cid',
        // vehicleno = '$vehicleno',
        // vehicletype = '$vehicletype',
        // transport = '$transport',
        // drivername = '$drivername',
        // drivermobile = '$drivermobile',
        // efrom = '$efrom', 
        // createdon = '$createdon',
        // createdby = '$createdby'";
    
        $token = "update gate set vehicleno = '$vehicleno', vehicletype = '$vehicletype', transport = '$transport', 
        drivername = '$drivername', drivermobile = '$drivermobile', gatestatus='1', efrom = '$efrom',  modifiedon = '$createdon', modifiedby = '$createdby' where gid=$vid";
        //echo $token;
        $qq = mysqli_query($conn,$token);
        echo '<script>window.location.href="main.php?msg=Token updated Successfully."</script>';
        
    }
}else{
    echo '<script>window.location.href="main.php"</script>';
}



?>

<div class="main-content-inner">
    <div class="page-header">
        <h1 class="page-heading h6 ebold">Material In</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Gate</li>
            <li class="breadcrumb-item">Material In</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">Generate Token</h5>
        </div>
        <div class="article-content container-max">
            <form action="" method="post">
                <input type="hidden" name="doAction" value="newvehicle">
                <input type="hidden" name="vid" value="<?php echo $vid; ?>">
                <div class="row">
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label for="vehicleno">Vehicle No.</label>
                            <input type="text" name="vehicleno" class="form-control"  value="<?php echo $vrw['vehicleno']; ?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label for="vehicletype">Vehicle Type</label>
                            <select name="vehicletype" class="form-control">
                                <option value="">Select Vehicle Type</option>
                                <?php $vtsql = "select vtid,vtname from vehicletype order by vtname";
                                $vtsqlq=mysqli_query($conn, $vtsql) or die(mysqli_error($conn));
                                while($vtrw=mysqli_fetch_assoc($vtsqlq)){
                                    echo '<option value="'.$vtrw['vtid'].'" '.($vtrw['vtid']==$vrw['vehicletype']?'selected':'').'>'.$vtrw['vtname'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label for="transport">Transport Name</label>
                            <input name="transport" class="form-control" value="<?php echo $vrw['transport']; ?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label for="driver">Driver Name</label>
                            <input name="driver" class="form-control" value="<?php echo $vrw['drivername']; ?>">
                        </div>
                    </div>
                    <div class="col-lg-4 col-sm-6">
                        <div class="form-group">
                            <label for="mob">Mob. No</label>
                            <input name="mob" class="form-control" value="<?php echo $vrw['drivermobile']; ?>">
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <!-- <input type="hidden" name="doAction" value="<?php #echo (!empty($_Get['vid'])?'newvehicle':'editvehicle');?>"> -->
                    <input type="hidden" name="vid" value="<?php echo $vrw['gid']; ?>">
                    <input type="submit" name="materialinSubmit" class="btn btn-basic" value="<?php echo(!empty($vid)?'Update Vehicle':'Generate Token'); ?>">
                </div>
            </form>
        </div>
    </div>
</div>


