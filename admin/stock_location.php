<?php

$perm=check_permission("A");

if($_POST['doAction']=='addlocation'){
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $lsql='insert into location set locname="'.htmlspecialchars($location).'",createdon="'.$createdon.'", createdby = "'.$createdby.'"' ;
    $lqq=mysqli_query($conn,$lsql);
    if(mysqli_insert_id($conn)>0){
     echo '<script>window.location.href="main.php?paction=stocklocation&msg=New Location Created Successfully"</script>';
    }
}else if($_POST['doAction']=='Updatelocation'){
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $locid = mysqli_real_escape_string($conn, $_POST['locid']);
    $lsql='update location set locname="'.htmlspecialchars($location).'",modifiedon="'.$createdon.'", modifiedby = "'.$createdby.'" where lid="'.$locid.'"' ;
    //echo $lsql;

     $lqq=mysqli_query($conn,$lsql);
    if(mysqli_affected_rows($conn)>0){
     echo '<script>window.location.href="main.php?paction=stocklocation&msg=Selected Location Updated Successfully"</script>';
    }
}

if(isset($_GET['locid'])){
    if(!empty($_GET['locid']) && is_numeric($_GET['locid'])){
        $lid = mysqli_real_escape_string($conn, $_GET['locid']);
        $locsql = "select lid,locname from location where lid=$lid";
        $locqq = mysqli_query($conn,$locsql);
        $locrw = mysqli_fetch_assoc($locqq);
        $locnt = mysqli_num_rows($locqq);
    }
}else if(isset($_GET['dellocid']) ){
    if(!empty($_GET['dellocid']) && is_numeric($_GET['dellocid'])){
        $lid = mysqli_real_escape_string($conn, $_GET['dellocid']);
        $delsql = "delete from location where lid='$lid'";   
        $dqq = mysqli_query($conn,$delsql);
        if(mysqli_affected_rows($conn)>0){
            echo '<script>window.location.href="main.php?paction=stocklocation&msg=Selected Location Deleted Successfully"</script>';
        }
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
    <?php if($perm){ ?>
    <div class="page-header">
        <h1 class="page-heading h6 ebold">All Locations</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">All Locations</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">All Locations</h5>
        </div>
        <div class="article-content container-max">
            <div class="locationAddEdit mb-4">
                <form action="" method="post" autocomplete="off">
                    <input type="hidden" name="doAction" value="<?php echo ($locnt=='1'?'Updatelocation':'addlocation'); ?>">
                    <?php if($locnt=='1'){ ?>
                        <input type="hidden" name="locid" value="<?php echo $locrw['lid']; ?>">
                    <?php } ?>
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="form-group mb-md-0">
                                <label for="location">Location</label>
                                <input type="text" class="form-control" name="location" id="location"
                                <?php
                                if(isset($_GET['locid']) && !empty($_GET['locid']) && is_numeric($_GET['locid'])){
                                    if($locnt=='1'){
                                        echo 'value = "'.html_entity_decode($locrw['locname']).'"';
                                    }
                                }
                                ?>
                                >
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group h-100 justify-content-end justify-content-sm-start align-items-end">
                                <input type="submit" class="btn btn-basic" name="locationSubmit" id="locationSubmit" value="<?php echo($_GET['locid']?'Update':'Add To');?> Location">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table">
                    
                    <thead>
                        <tr>
                            <th style="width: 4rem;">#</th>
                            <th>Stock Location</th>
                            <th style="width: 6rem;"></th>
                        </tr>
                    </thead>
                    
                    <tbody>
                        <?php
                        $shsql = "select lid,locname,(select location from sizes where sizes.location=location.lid limit 1) as locationused from location order by locname asc";
                        $shqq = mysqli_query($conn,$shsql);
                        $x = 1;
                        while($shrw = mysqli_fetch_assoc($shqq)){
                        ?>
                        <tr>
                            <td><?php echo $x;?></td>
                            <td><?php echo ucwords($shrw['locname']); ?></td>
                            <td>
                                <a href="main.php?paction=stocklocation&locid=<?php echo $shrw['lid']; ?>" class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                <?php if(empty($shrw['locationused'])){ ?>
                                    <a href="main.php?paction=stocklocation&dellocid=<?php echo $shrw['lid']; ?>" class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php $x++; } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
