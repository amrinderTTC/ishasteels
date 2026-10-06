<?php
$perm=check_permission("A");

if($_POST['doAction'] =="add"){ 
    $size = removespace(mysqli_real_escape_string($conn, $_POST['size']));
    $prid = mysqli_real_escape_string($conn, $_POST['product']);
    $weight = removespace(mysqli_real_escape_string($conn, $_POST['weight']));
    $weighttype = mysqli_real_escape_string($conn, $_POST['weightUnit']);
    $stdlength = removespace(mysqli_real_escape_string($conn, $_POST['length']));
    $lengthtype = mysqli_real_escape_string($conn, $_POST['lengthUnit']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $brand =  mysqli_real_escape_string($conn, $_POST['brand']);
    $location =  mysqli_real_escape_string($conn, $_POST['location']);
    $openingstock = mysqli_real_escape_string($conn, $_POST['openingStock']);
    $bundleweight = mysqli_real_escape_string($conn, $_POST['bundleWeight']);
    $wt = ftmtconvert($weight,$weighttype);
    //First check if size with product,stdlnth,grade, brand already exists in the stock !!if so dont insert
    $stat=chkproduct($size,$prid,$stdlength,$lengthtype,$grade,$brand);
    if($stat==0){
        $ssql = "insert into sizes set prid = '$prid', size = '$size',";
        if($weighttype=='2'){ //meter mt
            //var_dump('okmt: '.$weighttype);
            $ssql .="mtweight = '$weight',ftweight = '".$wt."', ";
        }elseif($weighttype=='1'){ // feet ft
            //var_dump('okft: '.$weighttype);
            $ssql .="mtweight = '".$wt."',ftweight = '$weight', ";
        }

        $ssql .="weighttype = '$weighttype', stdlength ='$stdlength', lengthtype ='$lengthtype',
        bundleweight ='$bundleweight',grade ='$grade', brand ='$brand', location = '$location', currentstock = '$openingstock',
        createdon = '$createdon', createdby = '$createdby'";
        //echo $ssql;
        $sqq = mysqli_query($conn, $ssql);
        if(mysqli_insert_id($conn)>0){
            $stsql = "insert into stock set
            prid='$prid',
            sizeid = '$size',
            gradeid = '$grade',
            brandid = '$brand',
            weight = '$openingstock',
            bundleweight = '$bundleweight',
            createdon = '$createdon',
            createdby = '$createdby'";
            //echo $stsql;
            $stqq=mysqli_query($conn,$stsql);
            
            echo '<script>window.location.href="main.php?paction=sizes_view&msg=New Size Created Successfully"</script>';
        }else{
            echo '<script>window.location.href="main.php?paction=sizes_view&errmsg=Something Went Wrong. Please Try Again."</script>';    
        }
    }else{
        echo '<script>window.location.href="main.php?paction=add_size&errmsg=Size with current settings already Exists."</script>';
    }
    // $chksql = "select * from sizes where size ='$size' and prid='$prid' and stdlength='$stdlength' and lengthtype='lengthtype' and grade='$grade' and brand='$brand'";
    // $chkqq = mysqli_query($conn,$chksql);
    // $chkcnt = mysqli_num_rows($chkqq);
    
    // $wt = ftmtconvert($weight,$weighttype);
    // $ssql = "insert into sizes set prid = '$prid', size = '$size',";
    // if($weighttype=='2'){ //meter mt
    //     var_dump('okmt: '.$weighttype);
    //     $ssql .="mtweight = '$weight',ftweight = '".$wt."', ";
    // }elseif($weighttype=='1'){ // feet ft
    //     var_dump('okft: '.$weighttype);
    //     $ssql .="mtweight = '".$wt."',ftweight = '$weight', ";
    // }

    // $ssql .="weighttype = '$weighttype',
    // stdlength = '$stdlength',
    // lengthtype = '$lengthtype',
    // createdon = '$createdon',
    // createdby = '$createdby'";
    // echo $ssql;
    // $sqq = mysqli_query($conn, $ssql);
    // if(mysqli_insert_id($conn)>0){
    //     echo '<script>window.location.href="main.php?paction=sizes_view&msg=New Size Created Successfully"</script>';
    // }
}else if($_POST['doAction'] =="edit"){
    //var_Dump($_POST);
    $sid =  mysqli_real_escape_string($conn, $_POST['sid']);
    $size = mysqli_real_escape_string($conn, $_POST['size']);
    $prid = mysqli_real_escape_string($conn, $_POST['product']);
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $weighttype = mysqli_real_escape_string($conn, $_POST['weightUnit']);
    $stdlength = mysqli_real_escape_string($conn, $_POST['length']);
    $lengthtype = mysqli_real_escape_string($conn, $_POST['lengthUnit']);
    $grade = mysqli_real_escape_string($conn, $_POST['grade']);
    $brand =  mysqli_real_escape_string($conn, $_POST['brand']);
    $location =  mysqli_real_escape_string($conn, $_POST['location']);
    $openingstock = mysqli_real_escape_string($conn, $_POST['openingstock']);
    $bundleweight = mysqli_real_escape_string($conn, $_POST['bundleWeight']);
    $wt = ftmtconvert($weight,$weighttype);

    $ssql = "update sizes set prid = '$prid', size = '$size' ,";
    if($weighttype=='2'){ //meter mt
        //var_dump('okmt: '.$weighttype);
        $ssql .="mtweight = '$weight',ftweight = '".$wt."', ";
    }elseif($weighttype=='1'){ // feet ft
        //var_dump('okft: '.$weighttype);
        $ssql .="mtweight = '".$wt."',ftweight = '$weight', ";
    }
    $ssql .="weighttype = '$weighttype',stdlength = '$stdlength',lengthtype = '$lengthtype',grade = '$grade', 
    brand = '$brand', location = '$location',bundleWeight = '$bundleweight', modifiedon = '$createdon',modifiedby = '$createdby' where sid='$sid'";

    $sqq = mysqli_query($conn, $ssql);
    echo '<script>window.location.href="main.php?paction=sizes_view&msg=Size Updated Successfully"</script>';
}


if(isset($_GET['sizeid']) && !empty($_GET['sizeid']) && is_numeric($_GET['sizeid'])){
    $sizeid = mysqli_real_escape_string($conn, $_GET['sizeid']);
    $ssql2 = "SELECT sizes.sid, sizes.prid, sizes.size,sizes.mtweight,sizes.ftweight, sizes.weighttype,sizes.stdlength, sizes.lengthtype, sizes.bundleweight
    ,sizes.grade, sizes.brand, sizes.location FROM sizes where sid='$sizeid'";
    //echo $ssql2;
    $sqq2=mysqli_query($conn,$ssql2);
    $srw2= mysqli_fetch_assoc($sqq2);
    //var_dump($srw2);
}   

if($_GET['msg']){
	$msg=$_GET['msg'];
}
if($_GET['errmsg']){
	$errmsg=$_GET['errmsg'];
}
?>

<div class="main-content-inner">
    <div class="page-header">
        <h1 class="page-heading ebold heading5"><?php echo($_GET['sizeid']?"Edit":"Add")?> Size</h1>
        <ul class="list-inline breadcrumb breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Sizes</li>
            <li class="breadcrumb-item"><?php echo($_GET['sizeid']?"Edit":"Add")?> Size</li>
        </ul>
    </div>
    <?php
    if($perm){
    ?>
    <div class="page-content container-max">
        <div class="article">
            <div class="article-heading flex-heading">
                <h5 class="text-center"><?php echo($_GET['sizeid']?"Edit":"Add")?> Size</h5>
                <a href="main.php?paction=sizes_view" class="btn btn-basic btn-sm">All Sizes</a>
            </div>
            <div class="article-content">
                <form action="#" method="post">
                    <input type="hidden" name="doAction" value="<?php echo($_GET['sizeid']?"update":"Add")?>">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="product">Product Name</label>
                                <select name="product" id="product" class="form-control select2me" required>
                                    <option value="">Select From List</option>
                                    <?php
                                    $psql = "select prid,productname from products";
                                    $pqq = mysqli_query($conn,$psql);
                                    while($prw = mysqli_fetch_assoc($pqq)){
                                        echo '<option value="'.$prw['prid'].'"'.($prw['prid']==$srw2['prid']?'selected':'').'>'.$prw['productname'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="size">Size/Dia (mm)</label>
                                <input type="text" name="size" id="size" class="form-control" value="<?php echo $srw2['size'];  ?>" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="weight">Weight (Kg)</label>
                                <div class="input-group">
                                    <input type="number" step="0.0001" name="weight" class="form-control" id="weight" value="<?php 
                                    if($srw2['weighttype']=='2'){ // mt selected
                                        echo $srw2['mtweight'];
                                    }else if($srw2['weighttype']=='1'){ // ft selected
                                        echo $srw2['ftweight'];
                                    }
                                      ?>" required>
                                    <div class="input-group-append">
                                        <select name="weightUnit" class="form-control" required>
                                            <option value="" disabled>Select One</option>
                                            <option value="2" <?php echo ($srw2['weighttype']=='2'?'selected':''); ?>>Per Meter</option>
                                            <option value="1" <?php echo (($srw2['weighttype']=='1'|| $srw2['weighttype'] == '')?'selected':''); ?>>Per Feet</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="length">Standard Length</label>
                                <div class="input-group">
                                    <input type="number" step="0.001" name="length" class="form-control" id="length" value="<?php echo $srw2['stdlength']; ?>" required>
                                    <div class="input-group-append">
                                        <select name="lengthUnit" class="form-control" required>
                                            <option value="" disabled>Select Unit</option>
                                            <option value="2"  <?php echo ($srw2['lengthtype']=='2'?'selected':''); ?>>Meter</option>
                                            <option value="1" <?php echo (($srw2['lengthtype']=='1' || $srw2['lengthtype'] == '')?'selected':''); ?>>Feet</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="grade">Grade</label>
                                
                                <select name="grade" class="form-control select2me" required>
                                    <option value="">Select From List</option>
                                    <?php
                                    $gsql = "select gid,grade from grade order by grade DESC";
                                    $gqq = mysqli_query($conn,$gsql);

                                    while($grw = mysqli_fetch_assoc($gqq)){
                                        echo '<option value="'.$grw['gid'].'"'.($grw['gid']==$srw2['grade'] || $grw['gid']=='1'?'selected':'').'>'.ucwords($grw['grade']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <select name="brand" class="form-control select2me" required>
                                    <option value="">Select From List</option>
                                    
                                    <?php
                                    $bsql = "select brid,brandname from brands order by brandname desc";
                                    $bqq = mysqli_query($conn,$bsql);

                                    while($brw = mysqli_fetch_assoc($bqq)){
                                        echo '<option value="'.$brw['brid'].'"'.($brw['brid']==$srw2['brand'] || $brw['brid']=='1'?'selected':'').'>'.ucwords($brw['brandname']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="location">Location</label>
                                <select name="location" class="form-control" required>
                                    <option value="">Select From List</option>
                                    <?php
                                    $lsql = "select lid,locname from location order by locname desc";
                                    $lqq = mysqli_query($conn,$lsql);

                                    while($lrw = mysqli_fetch_assoc($lqq)){
                                        echo '<option value="'.$lrw['lid'].'"'.($lrw['lid']==$srw2['location']?'selected':'').'>'.ucwords($lrw['locname']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="bundleWeight">Avg Bundle Weight(kg)</label>
                                <input type="number" name="bundleWeight" class="form-control" value="<?php echo $srw2['bundleweight']; ?>">
                            </div>
                        </div>
                        <?php if(!isset($_GET['sizeid'])){ ?>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="openingStock">Opening Stock(Tons)</label>
                                <div class="input-group">
                                    <input type="number" name="openingStock" class="form-control" min="0" step="0.001" required>
                                    <div class="input-group-prepend">
                                        <select name="qtyunit[]" class="form-control" >
                                            <option value="">Unit</option>
                                            <option value="1" selected>Tons</option>
                                            <option value="2">Pcs</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="btns text-right mt-4">
                        <input type="hidden" name="paction" value="add_size">
                        <input type="hidden" name="sid" value="<?php echo $srw2['sid']; ?>">
                        <input type="hidden" name="doAction" value="<?php echo ($_GET['sizeid']!=''?'edit':'add')?>" />
                        <button type="submit" name="submt_btn" value="1" class="btn btn-basic"><i class="fa fa-check"></i> 
                        <?php echo ($_GET['sizeid']!=""?"Update":"Add")?> Size</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php } ?>
</div>