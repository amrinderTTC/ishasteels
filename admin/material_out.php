<?php
    $perm=check_permission("A");
    if($_POST['doAction'] =='created'){
        //var_dump($_POST);
        //get currentdate
        //$tok='';
        $currenttoken = '';
        $newdt=date('Y-m-d',strtotime($createdon));
        $chktoken="select tokenid from gate where date(createdon) = '$newdt' order by createdon desc limit 1";
        //echo $chktoken;
        $chkqq=mysqli_query($conn,$chktoken);
        $chkcnt=mysqli_num_rows($chkqq);
        //var_dump($chkcnt);
        if($chkcnt>0){
            $chkcnt=mysqli_fetch_assoc($chkqq);
            $chkcnt1 = $chkcnt['tokenid'];
            $currenttoken = $chkcnt1+1;
        }else{
            $currenttoken=1;
        }
        //var_dump(currenttoken);
        $cid = mysqli_real_escape_string($conn, $_POST['customer']);
        $vehicleno = mysqli_real_escape_string($conn, $_POST['vehicleno']);
        $vehicletype = mysqli_real_escape_string($conn, $_POST['vehicletype']);
        $transport = mysqli_real_escape_string($conn, $_POST['transport']);
        $drivername = mysqli_real_escape_string($conn, $_POST['driver']);
        $drivermobile = mysqli_real_escape_string($conn, $_POST['mob']);
        $efrom = '2'; //marterial out
        $token = "insert into gate set tokenid='$currenttoken',cid = '$cid' , vehicleno = '$vehicleno', vehicletype = '$vehicletype', transport = '$transport', drivername = '$drivername',
        drivermobile = '$drivermobile', efrom = '$efrom',  createdon = '$createdon', createdby = '$createdby',gatestatus='0'";
        //echo $token;
          $tqq = mysqli_query($conn,$token);
        $insertid = mysqli_insert_id($conn);
        //if(mysqli_insert_id($conn)>0){
        if($insertid>0){
            // $cid = mysqli_real_escape_string($conn, $_POST['customer']);
            // $vehicleno = mysqli_real_escape_string($conn, $_POST['vehicleno']);
            // $vehicletype = mysqli_real_escape_string($conn, $_POST['vehicletype']);
            // $transport = mysqli_real_escape_string($conn, $_POST['transport']);
            // $drivername = mysqli_real_escape_string($conn, $_POST['driver']);
            // $drivermobile = mysqli_real_escape_string($conn, $_POST['mob']);

            $efrom = '2'; //marterial out
            //echo '<script>window.location.href="main.php?msg=Token Generated Successfully."</script>';
            echo '<script>window.location.href="main.php?paction=print_token&tokenid='.$insertid.'&msg=Token Generated Successfully."</script>';
        }
    }else if($_POST['doAction'] =='update'){
       // echo "stage 10";
        $cid = mysqli_real_escape_string($conn, $_POST['customer']);
        $vehicleno = mysqli_real_escape_string($conn, $_POST['vehicleno']);
        $vehicletype = mysqli_real_escape_string($conn, $_POST['vehicletype']);
        $transport = mysqli_real_escape_string($conn, $_POST['transport']);
        $drivername = mysqli_real_escape_string($conn, $_POST['driver']);
        $drivermobile = mysqli_real_escape_string($conn, $_POST['mob']);
        $efrom = '2'; //marterial out
        if(isset($_GET['vid']) && !empty($_GET['vid']) && is_numeric($_GET['vid'])){
            // $token = "update gate set tokenid='$currenttoken',cid = '$cid' , vehicleno = '$vehicleno', vehicletype = '$vehicletype', transport = '$transport', drivername = '$drivername',
            // drivermobile = '$drivermobile', efrom = '$efrom',  createdon = '$createdon', createdby = '$createdby',gatestatus='0' where gid=$_GET[vid]";
            $token = "update gate set cid = '$cid' , vehicleno = '$vehicleno', vehicletype = '$vehicletype', transport = '$transport', drivername = '$drivername',
            drivermobile = '$drivermobile', efrom = '$efrom',  createdon = '$createdon', createdby = '$createdby',gatestatus='0' where gid=$_GET[vid]";
            mysqli_query($conn,$token);
            //echo $token;
            echo '<script>window.location.href="main.php?msg=Token Updated Successfully"</script>';
            
        }
    }
    if(isset($_GET['vid']) && !empty($_GET['vid']) && is_numeric($_GET['vid'])){
        $query = "select * from gate where gid=$_GET[vid]";
        $qq = mysqli_query($conn,$query);
        $rw = mysqli_fetch_assoc($qq);
       // var_Dump($rw);
    }
    
    

?>

<div class="main-content-inner">
    <div class="page-header">
        <h1 class="page-heading h6 ebold">Material Out</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Gate</li>
            <li class="breadcrumb-item">Material Out</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">Generate Token</h5>
        </div>
        <div class="article-content container-max">
            <form action="" method ="post">
                
                <input type="hidden" name="doAction" value="<?php echo (!empty($_GET['vid'])?'update':'created'); ?>">
                <input type="hidden" name="vid2" value='<?php echo $rw['gid'];?>'>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="customer"><span>Customer</span></label>
                            <select class="form-control select2me" name="customer" required>
                                <option value="">Select Form List</option>
                                <?php //vendor list
                                $vsql = "select cust_id,name from customers where status='1'";
                                $vsqlq=mysqli_query($conn, $vsql) or die(mysqli_error($conn));
                                while($vrw=mysqli_fetch_assoc($vsqlq)){
                                    echo '<option value="'.$vrw['cust_id'].'" '.($rw['cid']==$vrw['cust_id']?'selected':'').'>'.ucwords($vrw['name']).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="vehicleno">Vehicle No.</label>
                            <input type="text" name="vehicleno" class="form-control" value="<?php echo $rw['vehicleno']; ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="vehicletype">Vehicle Type</label>
                            <select name="vehicletype" class="form-control" required>
                                <option value="">Select Vehicle Type</option>
                                <?php $vtsql = "select vtid,vtname from vehicletype order by vtname";
                                $vtsqlq=mysqli_query($conn, $vtsql) or die(mysqli_error($conn));
                                while($vtrw=mysqli_fetch_assoc($vtsqlq)){
                                    echo '<option value="'.$vtrw['vtid'].'" '.($vtrw['vtid']==$rw['vehicletype']?'selected':'').'>'.$vtrw['vtname'].'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="transport">Transport Name</label>
                            <input type="text" name="transport" class="form-control" value="<?php echo $rw['transport']; ?>">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="driver">Driver Name</label>
                            <input type="text" name="driver" class="form-control"  value="<?php echo $rw['drivername']; ?>">
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="mob">Mob. No</label>
                            <input type="number" name="mob" class="form-control"  value="<?php echo $rw['drivermobile']; ?>">
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <input type="submit" name="materialOutSubmit" class="btn btn-basic">
                </div>
            </form>
        </div>
    </div>
</div>

<script>
	$('#outgoingType').change(function(){
        if($(this).val() != ''){
            if($(this).val() == '1'){
                $('.customer').find('span').text('Customer');
            }else{
                $('.customer').find('span').text('Vendor');
            }
        }else{
            $('.customer').find('select option[value=""]').attr('selected', true)
        }
    });
    $('#qtyUnit').change(function(){
        if($(this).val() == '1' || $(this).val() == '2'){
            $(this).closest('.input-group').find('input').attr('step', "0.001");
        }else{
            $(this).closest('.input-group').find('input').attr('step', "");
        }
    });
</script>
