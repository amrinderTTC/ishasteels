<?php
$perm=check_permission("A","WT");
include_once "thumbnail_images.class.php";
if(isset($_GET['vid'])){
    if(!empty($_GET['vid']) && is_numeric($_GET['vid'])){
        echo 'stage 1<br>';
        $vid = mysqli_real_escape_string($conn, $_GET['vid']);
        // $vsql = "SELECT gate.gid, gate.vehicleno, gate.vehicletype, gate.efrom, gate.gatestatus, vehicletype.vtname FROM gate
        // INNER JOIN vehicletype ON vehicletype.vtid = gate.vehicletype where gatestatus='0' and gid=$vid";
        $vsql = "SELECT gate.gid, gate.vehicleno, gate.vehicletype, gate.efrom, gate.gatestatus, vehicletype.vtname FROM gate
        INNER JOIN vehicletype ON vehicletype.vtid = gate.vehicletype where gid=$vid";
        echo $vsql;
        $vqq = mysqli_query($conn,$vsql);
        $wcnt = mysqli_num_rows($vqq);
        if($wcnt>0){

            //echo 'stage 2<br>';
            $vew = mysqli_fetch_assoc($vqq);
            //var_Dump($vew);
            $wesql = "select * from weight where vhid='$vid'";
            $weqq = mysqli_query($conn,$wesql);
            $wecnt = mysqli_num_rows($weqq);
            $vrw = mysqli_fetch_assoc($weqq);
            $unloaded = $vrw['unloaded'];
            
            
            // insert first first for the selected vehicle
            $uploadTo="../admin/assets/images/weight/";
            if($_POST['doAction']=='addweight'){
                $basename ='';
                if($_FILES["weighmentSlip"]["error"] == UPLOAD_ERR_OK) {
                    $htgraph = strtolower($vid);
                    $drawno = str_replace(' ','',$htgraph);
                    $drawno1 = preg_replace('/[^a-zA-Z0-9_.]/', '_', $drawno);
                    $extention = pathinfo($_FILES['weighmentSlip']['name'],PATHINFO_EXTENSION);
                    $rstr=generateRandomString(10);
                    $basename=basename($drawno1."_in_".$rstr.".".$extention);
                    $obj_img = new thumbnail_images();
                    $obj_img->PathImgOld = $_FILES['weighmentSlip']['tmp_name'];
                    $obj_img->PathImgNew = $uploadTo.$basename;
                    $obj_img->NewWidth =500;
                    $obj_img->NewHeight	=500;
                    $obj_img->create_thumbnail_images();
                }
                
                $weight =  mysqli_real_escape_string($conn, $_POST['loadedWeight']);
                $weighttyp =  mysqli_real_escape_string($conn, $_POST['weighttyp']);
                $aw = "insert into weight set vhid = '$vid', wht = '$weight', whtslip = '$basename',  weighttyp='$weighttyp',createdon='$createdon', createdby='$createdby'";
                $awq = mysqli_query($conn,$aw);
                if(mysqli_insert_id($conn)>0){
                    $ugate ="update gate set gatestatus='1' where gid =$vid";
                        echo '<script>window.location.href="main.php?msg=Vehicle loaded Weight Done Successfully"</script>';
                    
                    
                }
                // if first weight entered successfully set gate id status to 1 and vehilce will be showen in yard for unloading
                
            }else if($_POST['doAction']=='updateweight'){
                            echo 'stage 1<br>';
                            $weight =  mysqli_real_escape_string($conn, $_POST['loadedWeight']);
                            $wid =  mysqli_real_escape_string($conn, $_POST['weightid']);
                            $oldslip =  mysqli_real_escape_string($conn, $_POST['slip']);
                
                            unlink($uploadTo.$oldslip);
                            $basename ='';
                            //var_dump($_FILES["weighmentSlip"]["error"]);
                            if($_FILES["weighmentSlip"]["error"] == UPLOAD_ERR_OK) {
                                //echo 'stage 2';
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
                            }
                            $aw = "update weight set vhid = '$vid', wht = '$weight', whtslip = '$basename',
                            weighttyp='1',modifiedon = '$createdon', modifiedby = '$createdby' where whtid='$wid'";
                            //echo $aw;
                            $awqq = mysqli_query($conn,$aw);
            }else if($_POST['doAction']=='outweight'){
               // var_dump($_POST);
                $whtid = mysqli_real_escape_string($conn, $_POST['weightid']);
                $tweight =  mysqli_real_escape_string($conn, $_POST['tweight']);

                if($_FILES["tweighSlip"]["error"] == UPLOAD_ERR_OK) {
                    echo 'stage 2';
                    $htgraph = strtolower($vid);
                    $drawno = str_replace(' ','',$htgraph);
                    $drawno1 = preg_replace('/[^a-zA-Z0-9_.]/', '_', $drawno);
                    $extention = pathinfo($_FILES['tweighSlip']['name'],PATHINFO_EXTENSION);
                    $rstr=generateRandomString(10);
                    $basename=basename($drawno1."_out_".$rstr.".".$extention);
    
                    $obj_img = new thumbnail_images();
                    $obj_img->PathImgOld = $_FILES['tweighSlip']['tmp_name'];
                    $obj_img->PathImgNew = $uploadTo.$basename;
                    $obj_img->NewWidth = 500;
                    $obj_img->NewHeight	=500;
                    $obj_img->create_thumbnail_images();
                }

                $eaw = "update weight set emtywght = '$tweight', emtywghtslip = '$basename', weighttyp='2',modifiedon = '$createdon',emptydt='$createdon',emptyby ='$createdby', modifiedby = '$createdby' where whtid='$whtid'";
                $awqq = mysqli_query($conn,$eaw);

               echo '<script>window.location.href="main.php?msg=Vehicle unloaded Weight inserted successfully."</script>';
            }

            $wsql1 = "select * from weight where vhid='$vid'";
                echo $wsql;
                $wqq1 = mysqli_query($conn,$wsql1);
                $wcnt = mysqli_num_rows($wqq1);
                //var_dump($wcnt);
                // if($wcnt>0){
                //     //$vw = mysqli_fetch_assoc($wqq1);
                //     //var_Dump($vw);
                // }
        }else{
            //echo '<script>window.location.href="main.php"</script>'; 
        }
    }
}
?>
<div class="main-content-inner">
    <div class="page-header">
        <h1 class="page-heading h6 ebold">Material In</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Weight</li>
            <li class="breadcrumb-item">Material In</li>
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
                            <th>Entry Type</th>
                            <th>Vehicle Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $vew['gid']; ?></td>
                            <td><?php echo $vew['vehicleno']; ?></td>
                            <td><?php echo ($vew['efrom']=='1'?'Material In':'Material Out'); ?></td>
                            <td><?php echo $vew['vtname']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>S. No.#</th>
                            <th>In Weight</th>
                            <th>In Slip</th>
                            <th>Next Weight</th>
                            <th>Next Slip</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php 
                        $xy=1;
                        $lw = '';
                        $fw = '';
                        $whtid = '';
                        $whtslip ='';
                        while($vw = mysqli_fetch_assoc($wqq1)){
                            $lw=$vw['wht'];
                            $fw = $vw['emtywght'];
                            $whtid =$vw['whtid'];
                            $whtslip =$vw['whtslip'];
                            $ipath = "../admin/assets/images/weight/";
                            $inpath='<a href = "'.$ipath.$vw['whtslip'].'"  target="_blank">'.$vw['whtslip'].'</a>';
                            $outpath='<a href = "'.$ipath.$vw['emtywghtslip'].'"  target="_blank">'.$vw['emtywghtslip'].'</a>';
                            ?>
                        <tr>
                            <td><?php echo $xy; ?></td>
                            <td><?php echo $vw['wht']; ?></td>
                            <td><?php echo (!empty($vw['whtslip'])?$inpath:''); ?></td>
                            <td><?php echo $vw['emtywght']; ?></td>
                            <td><?php echo (!empty($vw['emtywghtslip'])?$outpath:''); ?></td>
                        </tr>
                        <?php $xy++; 
                            }  ?>
                    </tbody>
                </table>
            </div>
            <form action="" method="post" enctype="multipart/form-data" class="mt-4 pt-2">
                <input type="hidden" name="doAction" value="<?php echo (empty($fw)?'updateweight':'addweight'); ?>">
                <input type="hidden" name="paction" value="material_in_weight">
                <input type="hidden" name="weighttyp" value="1"><!-- 1:material in loaded weight 2: material in empty weight -->
                <?php if($wcnt>0){ ?>
                    <input type="hidden" name="weightid" value="<?php echo $vw['whtid']; ?>">
                    <input type="hidden" name="slip" value="<?php #echo $vw['whtslip']; ?>">
                <?php }
                ?>
                
                <div class="row">
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="loadedWeight">Loaded Weight</label>
                            <div class="input-group">
                                <input type="number" min="0" name="loadedWeight" step="0.001" class="form-control" value="<?php echo (empty($fw)?$lw:''); #if($wcnt>0){echo $lw;} ?>">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="weighmentSlip">Weighment Slip</label>
                            <input type="file" name="weighmentSlip" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="input-group h-100 justify-content-end justify-content-md-start align-items-center">
                            <input type="submit" name="firstWeightSubmit" class="btn btn-basic" value="<?php echo (empty($fw)?'Update':'Submit');?>">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <?php if(empty($fw)){ ?>
    <div class="article mt-4">
        <div class="article-heading flex-heading">
            <h5 class="text-center">Material Weigh Record</h5>
        </div>
        <div class="article-content container-max">
            <form action="" method="post"  enctype="multipart/form-data"  autocomplete="off">
            <input type="hidden" name="doAction" value="outweight">
            <input type="hidden" name="paction" value="material_in_weight">
            <input type = "hidden" name="vid" value="<?php echo $vrw['vhid']; ?>">
            <input type="hidden" name="weightid" value="<?php echo $whtid; ?>">
            <input type="hidden" name="weighttyp" value="2"><!-- 1:material in loaded weight 2: material in empty weight -->
                <div class="table-responsive">
                    <table class="table table-bordered table-xl">
                        <thead>
                            <tr>
                                <th>Loaded Weight(Tons)</th>
                                <th>Tare Weight(Tons)</th>
                                <th>Weighment Slip</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php echo $lw; #echo $vrw['wht']; ?></td>
                                <td><input type="number" name="tweight" min="0" step="0.001" class="form-control" value="<?php echo $fw; ?>"></td>
                                <td><input type="file" name="tweighSlip" class="form-control"></td>
                                <td><input type="submit" name="weightSubmit" class="btn btn-basic"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </form>
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
</script>

