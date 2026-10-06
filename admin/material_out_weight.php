<?php
    $perm=check_permission("WT","A");
    include_once "thumbnail_images.class.php";
    $vid = $_GET['vid'];
    if(isset($_GET['vid']) && !empty($_GET['vid']) && is_numeric($_GET['vid'])){
        $vsql = "SELECT gate.tokenid, gate.gid,gate.initialweight,gate.initialweightinkg,gate.initialweighton,gate.initialweightby, gate.initialweightslip, gate.vehicleno, gate.vehicletype, gate.efrom, gate.gatestatus, vehicletype.vtname, gate.cid,(select `name` from customers where cust_id=gate.cid) as customername FROM gate INNER JOIN vehicletype ON vehicletype.vtid  where gid=$vid";
        $vsqlq=mysqli_query($conn,$vsql) or die(mysqli_error($conn));
        $vew = mysqli_fetch_assoc($vsqlq);
        
        if($_POST['doAction']=='addweight'){
            
            $uploadTo="../admin/assets/images/weight/";
            $basename='';
            $vehicleid = mysqli_real_escape_string($conn, $_POST['vehicletokenid']);
            
            $firstweightinkg = mysqli_real_escape_string($conn, $_POST['loadedWeight']); // value in kg
            $firstweight = ((float)$firstweightinkg/1000); // value in tons
            $updatefirstweight="update gate set initialweight='$firstweight',initialweightinkg='$firstweightinkg' ";

            if($_FILES["weighmentSlip"]["error"] == UPLOAD_ERR_OK && $_FILES["weighmentSlip"]["name"] ) {
                unlink('assets/images/weight/'.$vew['initialweightslip']);
                $htgraph = strtolower($vid);
                $drawno = str_replace(' ','',$htgraph);
                $drawno1 = preg_replace('/[^a-zA-Z0-9_.]/', '_', $drawno);
                $extention = pathinfo($_FILES['weighmentSlip']['name'],PATHINFO_EXTENSION);
                $rstr=generateRandomString(10);
                $basename=basename($drawno1."_in_".$rstr.".".$extention);
                $obj_img = new thumbnail_images();
                $obj_img->PathImgOld = $_FILES['weighmentSlip']['tmp_name'];
                $obj_img->PathImgNew = $uploadTo.$basename;
                $obj_img->NewWidth = 500;
                $obj_img->NewHeight	=500;
                $obj_img->create_thumbnail_images();
                $updatefirstweight .=" , initialweightslip='$basename'";
            }
            $updatefirstweight .=" ,gate.gatestatus='1',initialweighton='$createdon',initialweightby='$createdby' where gid='$vehicleid'";
           
            mysqli_query($conn,$updatefirstweight);
            echo '<script>window.location.href="main.php?paction=material_out_weight&vid='.$vehicleid.'&msg=Initial Weight updated successfully."</script>';
        }else if($_POST['doAction']=='updateweight'){

        }
        if($_POST['doAction']=='addweight2'){ //second weight insert or update
            /**
             * First Weight will be inserted into whtinkg field value will be in kgs actual value inserted
             * First Weight will be inserted into wht field value will be in tons value will be calculated by whtinkg/1000
             
             * Second weight will be inserted into the emtywghtkg field value will be in kgs actual value inserted
             * Second weight will be inserted into the emtywght field value will be calculated by emtywghtkg/1000
             */
            $uploadTo="../admin/assets/images/weight/";
            $basename = '';
            //var_Dump($_POST);
            $weightcnt=mysqli_real_escape_string($conn, $_POST['weightcnt']);
            $dispatchid=mysqli_real_escape_string($conn, $_POST['dispatchid']);
            $vehicleid=mysqli_real_escape_string($conn, $_POST['vehicleid']);
            $initialweight=mysqli_real_escape_string($conn, $_POST['initialweight']);
            $initialweightinkg=mysqli_real_escape_string($conn, $_POST['initialweightinkg']);
            $initialweighton=mysqli_real_escape_string($conn, $_POST['initialweighton']);
            $initialweightby=mysqli_real_escape_string($conn, $_POST['initialweightby']);
            $initialweightslip=mysqli_real_escape_string($conn, $_POST['initialweightslip']);
            $emptyweight=mysqli_real_escape_string($conn, $_POST['emptyweight']);
            $loadedWeight2=mysqli_real_escape_string($conn, $_POST['loadedWeight2']);
            $doAction= mysqli_real_escape_string($conn, $_POST['doAction']);
            $loadedWeight2intons=($loadedWeight2/1000);
            
            if($weightcnt==0){
                $weightinssql = "insert into weight set
                wht = '$initialweight',
                whtinkg = '$initialweightinkg',
                whtslip ='$initialweightslip',
                emtywght='$loadedWeight2intons',
                emtywghtkg='$loadedWeight2',
                emptydt='$createdon',
                whtcreatedon = '$initialweighton',
                whtcreatedby = '$initialweightby',vhid='$vehicleid',
                dispatchid ='$dispatchid'";
            
                if($_FILES["weighmentSlip2"]["error"] == UPLOAD_ERR_OK && $_FILES["weighmentSlip2"]["name"] ) {
                    $htgraph = strtolower($vid);
                    $drawno = str_replace(' ','',$htgraph);
                    $drawno1 = preg_replace('/[^a-zA-Z0-9_.]/', '_', $drawno);
                    $extention = pathinfo($_FILES['weighmentSlip2']['name'],PATHINFO_EXTENSION);
                    $rstr=generateRandomString(10);
                    $basename=basename($drawno1."_out_".$rstr.".".$extention);
                    $obj_img = new thumbnail_images();
                    $obj_img->PathImgOld = $_FILES['weighmentSlip2']['tmp_name'];
                    $obj_img->PathImgNew = $uploadTo.$basename;
                    $obj_img->NewWidth = 500;
                    $obj_img->NewHeight	=500;
                    $obj_img->create_thumbnail_images();
                    $weightinssql .= " ,emtywghtslip='$basename' ";
                }

                $weightinssql .= " ,createdon='$createdon',createdby='$createdby'";
                echo $weightinssql.'<br><br><br><br>';
                //$weightinsqq = mysqli_query($conn,$weightinssql);
                $weightinsqq = mysqli_query($conn,$weightinssql);
                if(mysqli_insert_id($conn)>0){
                    //$newweight=($loadedWeight2-$initialweight);
                    $newweight=($loadedWeight2intons-$initialweight);
                    $updatedisp="update dispatch set weight='$newweight' where dispatchid='$dispatchid' and vehicleid='$vehicleid'";
                    echo $updatedisp.'<br><br>';
                    mysqli_query($conn,$updatedisp);
                    echo '<script>window.location.href="main.php?paction=material_out_weight&vid='.$vehicleid.'&msg=Second Weight Inserted Scessfully"</script>';
                }
            }

            if($weightcnt>0){
                //update or insert second weight for given vehicle number weight id and dispatch id
                $weightcnt= mysqli_real_escape_string($conn, $_POST['weightcnt']); 
                $whtid= mysqli_real_escape_string($conn, $_POST['whtid']); 
                $dispatchid= mysqli_real_escape_string($conn, $_POST['dispatchid']); // hidden field to store current row dispatch id
                $vehicleid= mysqli_real_escape_string($conn, $_POST['vehicleid']); // hidden field to store the vehicle id
                $initialweight= mysqli_real_escape_string($conn, $_POST['initialweight']); // hidden field initial weight incase first weight of current dispatch is already done 
                $initialweighton= mysqli_real_escape_string($conn, $_POST['initialweighton']); // hidden field in case inital weight is already done
                $initialweightby= mysqli_real_escape_string($conn, $_POST['initialweightby']); // hidden field to identify who weighted the initial weight
                $initialweightslip= mysqli_real_escape_string($conn, $_POST['initialweightslip']); // hidden field initial weight incase first weightslip of current dispatch is already done
                $emptyweight= mysqli_real_escape_string($conn, $_POST['emptyweight']); //textbox emptyweight to get initial weight  in kg
                $emptyweighttons = ((float)$emptyweight/1000);
                $loadedWeight2= mysqli_real_escape_string($conn, $_POST['loadedWeight2']); // textbox to get the loaded(second) weight.
                $loadedWeight2slip= mysqli_real_escape_string($conn, $_POST['loadedWeight2slip']); // textbox to get the loaded(second) weight.
                
                // first weight
                if(empty($whtid) && !isset( $_POST['loadedWeight2'])){
                    //insert new record to weight table with first weight
                    $insertfirstweight = "insert into weight set
                    vhid ='$vehicleid',
                    dispatchid='$dispatchid',
                    wht='$emptyweighttons',
                    whtinkg='$emptyweight',
                    whtcreatedon='$createdon',
                    whtcreatedby='$createdby'";
                    $uploadTo="../admin/assets/images/weight/";
                    $basename='';
                    
                    // weight slip upload
                    if($_FILES["weighmentSlip"]["error"] == UPLOAD_ERR_OK && $_FILES["weighmentSlip"]["name"] ) {
                        //unlink('assets/images/weight/'.$loadedWeight2);
                        $htgraph = strtolower($vid);
                        $drawno = str_replace(' ','',$htgraph);
                        $drawno1 = preg_replace('/[^a-zA-Z0-9_.]/', '_', $drawno);
                        $extention = pathinfo($_FILES['weighmentSlip']['name'],PATHINFO_EXTENSION);
                        $rstr=generateRandomString(10);
                        $basename=basename($drawno1."_in_".$rstr.".".$extention);
                        $obj_img = new thumbnail_images();
                        $obj_img->PathImgOld = $_FILES['weighmentSlip']['tmp_name'];
                        $obj_img->PathImgNew = $uploadTo.$basename;
                        $obj_img->NewWidth = 500;
                        $obj_img->NewHeight	=500;
                        $obj_img->create_thumbnail_images();
                        $insertfirstweight .= " ,whtslip='$basename' ";
                    }
                   
                    mysqli_query($conn,$insertfirstweight);
                    // if(mysqli_insert_id($conn)>0){
                    //     echo '<script>window.location.href="main.php?paction=material_out_weight&vid=$vehicleid&msg=Initial Weight Inserted successfully"</script>';
                    // }
                    //first weight ends where
                }else{
                    //Second Weight (Loaded Weight) db column emptyweight
                    
                    $uploadTo="../admin/assets/images/weight/";
                    $basename = '';
                   $loadedWeight2tons=((float)$loadedWeight2/1000);
                    $uweight = "update weight set emtywght='$loadedWeight2tons',emtywghtkg='$loadedWeight2',emptydt='$createdon'";
                    if($_FILES["weighmentSlip2"]["error"] == UPLOAD_ERR_OK && $_FILES["weighmentSlip2"]["name"] ) {
                        unlink('assets/images/weight/'.$loadedWeight2);
                        $htgraph = strtolower($vid);
                        $drawno = str_replace(' ','',$htgraph);
                        $drawno1 = preg_replace('/[^a-zA-Z0-9_.]/', '_', $drawno);
                        $extention = pathinfo($_FILES['weighmentSlip2']['name'],PATHINFO_EXTENSION);
                        $rstr=generateRandomString(10);
                        $basename=basename($drawno1."_out_".$rstr.".".$extention);
                        $obj_img = new thumbnail_images();
                        $obj_img->PathImgOld = $_FILES['weighmentSlip2']['tmp_name'];
                        $obj_img->PathImgNew = $uploadTo.$basename;
                        $obj_img->NewWidth = 500;
                        $obj_img->NewHeight	=500;
                        $obj_img->create_thumbnail_images();
                        $uweight .= " ,emtywghtslip='$basename' ";
                    }
                    $uweight .= " where whtid = '$whtid'";
                    //echo $uweight;
                    //echo '<br><br><br>';
                    $uweightqq = mysqli_query($conn,$uweight);
                   
                    if(mysqli_affected_rows($conn)>0){

                        if(empty($emptyweight)){
                            echo 'stage1';
                            $newdispweight = ($loadedWeight2tons-$initialweight);
                        }else{
                            // echo 'stage2';
                            // echo $emptyweight,'<br><br><br>';
                            // echo $loadedWeight2tons,'<br><br><br>';
                            $newdispweight = ($loadedWeight2-$emptyweight)/1000;
                           //$newdispweight = ($loadedWeight2tons-$emtywghtkg);
                        }
                        $updatedisp = "update dispatch set weight='$newdispweight' where vehicleid = '$vehicleid' and dispatchid='$dispatchid'";
                        echo $updatedisp;
                        mysqli_query($conn,$updatedisp);
                       //var_Dump(mysqli_affected_rows($conn));
                        if(mysqli_affected_rows($conn)>0){
                            echo '<script>window.location.href="main.php?paction=material_out_weight&vid='.$vehicleid.'&msg=Loaded Weight updated successfully"</script>';
                        }else{
                            echo '<script>window.location.href="main.php?paction=material_out_weight&vid='.$vehicleid.'&errmsg=something went wrong"</script>';
                        }
                   }else{
                       echo '<script>window.location.href="main.php?paction=material_out_weight&vid='.$vehicleid.'&errmsg=something went wrong"</script>';
                    }
                }
            }
            
        }

    }else{
        echo '<script>window.location.href="main.php"</script>';
    }
  
    $wsql1 = "select * from weight where vhid='$vid' order by whtid desc limit 1";
        //echo $wsql1;
        $wqq1=mysqli_query($conn,$wsql1);
        $wcnt=mysqli_num_rows($wqq1);
        $vw1=mysqli_fetch_assoc($wqq1);
        //var_Dump($wcnt);

        
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
        <h1 class="page-heading h6 ebold">Material Out</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Weight</li>
            <li class="breadcrumb-item">Material Out</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">Vehicle Weigh Record</h5>
        </div>
        <div class="article-content container-max">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Token No.</th>
                            <th>Vehicle No.</th>
                            <th>Vehicle Type</th>
                            <th>Customer</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $vew['tokenid']; ?></td>
                            <td><?php echo strtoupper($vew['vehicleno']); ?></td>
                            <td><?php echo $vew['vtname']; ?></td>
                            <td><?php echo $vew['customername']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php 
              $weightsql1 = "select whtid,vhid,dispatchid,wht,whtslip,emtywght,emtywghtslip from weight where vhid='$vid' and wht is not null";
             // var_Dump($weightsql1);
              $weightqq1 = mysqli_query($conn,$weightsql1);
                $wcnt1 = mysqli_num_rows($weightqq1);
                if($wcnt1==0){
             ?>
            <form action="" class="mt-4 pt-2" method="post"  enctype="multipart/form-data" >
                    <input type="hidden" name="doAction" value="addweight">
                
            
            <input type="hidden" name="vehicletokenid" value="<?php echo $vew['gid']; ?>">
            <div class="row">
                    <div class="col-xl-4 col-md-4">
                        <div class="form-group">
                            <label for="loadedWeight">Tare Weight</label>
                            <div class="input-group">
                                <input type="number" min="0" name="loadedWeight" step="0.001" class="form-control" value="<?php echo (empty($vew['initialweightinkg'])?'':$vew['initialweightinkg']); #(empty($vw1['wht'])?$vw1['wht']:'');  ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-4">
                        <div class="form-group">
                            <label for="weighmentSlip">Weighment Slip</label>
                            <input type="file" name="weighmentSlip" class="form-control">
                            <?php if($vew['initialweightslip']){ ?>
                                <a target="_blank" href="assets/images/weight/<?php echo $vew['initialweightslip']; ?>">Weight slip</a>
                            <?php } ?>
                        </div>
                    </div>
                    <?php if(empty($vew['initialweightslip'])){ ?>
                    <div class="col-xl-2">
                        <div class="input-group h-100 justify-content-end align-items-center">
                        <input type="submit" name="firstWeightSubmit" class="btn btn-basic" value="Submit">
                        </div>
                    </div>
                     <?php } ?>
                </div>
            </form>
            <?php } ?>
        </div>
    </div>

    <div class="article mt-4">
        <div class="article-heading flex-heading">
            <h5 class="text-center">Loaded Weight</h5>
        </div>
        <div class="article-content container-max">
           
                <div class="table-responsive">
                <?php 
                        
                        $x=1;
                           //New
                           $iweight="select initialweight,initialweightinkg from gate where gid='$vid'";
                           $iweightqq=mysqli_query($conn,$iweight);
                           $iwrw = mysqli_fetch_assoc($iweightqq);
                           if(!empty($iwrw['initialweight'])){ 
                               $dispsql="SELECT
                               dispatch.dispatchid,
                               dispatch.vehicleid,
                               dispatch.weight,
                               dispatch.custid,
                               dispatch.createdon,
                               dispatch.createdby,
                               customers.`name` as custname
                               FROM
                               dispatch
                               INNER JOIN customers ON customers.cust_id = dispatch.custid
                                where vehicleid='$vid'";
                                
                               $disqq = mysqli_query($conn,$dispsql);
                               while($disrw =mysqli_fetch_assoc($disqq)){
                                    $weightsql = "select whtid,vhid,dispatchid,wht,whtslip,emtywght,emtywghtslip from weight where vhid='$vid'";
                                    
                                    $weightqq = mysqli_query($conn,$weightsql);
                                    $weightcnt = mysqli_num_rows($weightqq);

                                    $weightsql2 = "select whtid,vhid,dispatchid,wht,whtinkg,whtslip,emtywght,emtywghtkg,emtywghtslip from weight where vhid='$vid' and dispatchid='$disrw[dispatchid]'";
                                    $weightqq2 = mysqli_query($conn,$weightsql2);
                                    $wrw2 = mysqli_fetch_assoc($weightqq2);
                                     ?>
                                    <form action="" method="post" enctype="multipart/form-data" >
                                    <input type="hidden" name="doAction" value="addproductweight">
                                    <input type="hidden" name="whtid" value="<?php echo $wrw2['whtid']; ?>">
                                    <input type="hidden" name="initialweight" value="<?php echo $vew['initialweight']; ?>">
                                    <input type="hidden" name="initialweightinkg" value="<?php echo $vew['initialweightinkg']; ?>">
                                    <input type="hidden" name="initialweighton" value="<?php echo $vew['initialweighton']; ?>">
                                    <input type="hidden" name="initialweightby" value="<?php echo $vew['initialweightby']; ?>">
                                    <input type="hidden" name="initialweightslip" value="<?php echo $vew['initialweightslip']; ?>">
                                    <input type="hidden" name="vehicleid" value="<?php echo $vew['gid']; ?>"><!-- gateid -->
                                        <table class="table table-bordered table-xl">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Dispatch Slip No.</th>
                                                    <th>Party Name</th>
                                                    <th class="text-center">Prev Weight(Kg.)</th>
                                                    <td>Empty Slip</td>
                                                    <th class="text-center">Current Weight(Kg.)</th>
                                                    <th class="text-center">Weight Slip</th>
                                                    <th class="text-center">Material Weight(Kg.)</th>
                                                    <th style="width: 6rem"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <td><?php echo $x;?></td>
                                                <td><?php echo $disrw['dispatchid'];?></td>
                                                <td><?php echo ucwords($disrw['custname']);?></td>
                                                <td>
                                                    <?php if($weightcnt>'0' && empty($wrw2['wht'])){ ?>
                                                        <input name="emptyweight" id="emptyweight" type="number" step="0.001" min="0" class="form-control" value="">
                                                    <?php }else{
                                                        echo '<input type="hidden" name="emptyweight" value="'.$wrw2['whtinkg'].'">';
                                                        echo $wrw2['whtinkg'];
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php if($weightcnt>'0' && empty($wrw2['wht'])){ ?>
                                                        <input type="file" name="weighmentSlip" class="form-control">

                                                    <?php  }else{
                                                        if(!empty($wrw2['whtslip'])){
                                                            echo '<a target="_blannk" href="assets/images/weight/'.$wrw2['whtslip'].'">Weight Slip</a>';
                                                        }
                                                    } ?>
                                                </td>
                                                <td>
                                                    <?php if(($weightcnt=='0') || ($weightcnt>'0' && !empty($wrw2['wht']))){
                                                        //if($weightcnt=='0'){
                                                         ?>
                                                        
                                                    <input name="loadedWeight2" id="loadedWeight2" type="number" step="0.001" min="0" class="form-control" value="<?php echo $wrw2['emtywghtkg']; ?>"></td>
                                                    <?php  } ?>
                                                <td><?php 
                                                 if(!empty($wrw2['emtywghtslip'])){
                                                    echo '<a target="_blannk" href="assets/images/weight/'.$wrw2['emtywghtslip'].'">Weight Slip</a>';
                                                    echo '<input type="hidden" name="loadedWeight2slip" value="'.$wrw2['emtywghtslip'].'">';
                                                }
                                                if(($weightcnt=='0') || ($weightcnt>'0' && !empty($wrw2['wht']))){ ?>
                                                    <input type="file" name="weighmentSlip2" class="form-control">
                                                    <?php } ?>
                                                </td>
                                                <td><?php #echo $disrw['weight'];?><?php
                                                if(empty($wrw2['emtywghtkg'])){
                                                    echo '0';
                                                }else{
                                                    echo ($wrw2['emtywghtkg']-$wrw2['whtinkg']);
                                                }
                                                 ?></td>
                                                <td>
                                                <input type="hidden" name="doAction"  value="addweight2">
                                                <input type="hidden" name="vehicleid" value="<?php echo $vid; ?>">
                                                <input type="hidden" name="dispatchid" id="dispatchid" value="<?php echo $disrw['dispatchid'];?>">
                                                <input type="hidden" name="weightcnt" value="<?php echo $weightcnt; ?>">
                                                <input type="hidden" name="materailwht" id="materailwht" value="">
                                               
                                                <input type="hidden" name="weightcnt" value="<?php echo $weightcnt; ?>">
                                                <input type="submit" name="weightSubmit" value ="Add Weight" class="btn btn-basic">
                                            </td>
                                               
                                            </tbody>
                                        </table>
                                    </form>
                                    <?php $x++; 
                                } //while loop ends where 
                            }?>
                </div>
            
        </div>
    </div>
    <?php } ?>
</div>
<div class="modal fade" id="weighSlipView">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Weighment Slip</h4>
                <button type="button" class="close" data-dismiss="modal"><i class="bi bi-x"></i></button>
            </div>
            <div class="modal-body text-center">
                <img class="img-fluid" src="assets/images/vital-steel-bars-llp-logo.png">
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.weighSlipView', function(e){
        e.preventDefault();
        let src = $(this).attr('href');
        let slipViewModal = $('#weighSlipView');
        slipViewModal.find('img').attr('src', src);
        slipViewModal.modal();
    });
    $('#currentwht').change(function(){
        let oldweight = $('#oldweight').val();
        let currentwht = $('#currentwht').val();
        console.log(currentwht);
        let materailweight = (currentwht - oldweight);
        
        $('.materailwht').text(materailweight);
        $('#materailwht').val(materailweight);
    });
</script>

