<?php

$perm=check_permission("A","OF");
//var_Dump($_POST);




if($_POST['doAction']=='Add'){
    
    $brand = mysqli_real_escape_string($conn, $_POST['brand']);
    $insql = "insert into brands set  brandname = '$brand', createdon = '$createdon', createdby = '$createdby'";
    //echo $insql;
    $qq = mysqli_query($conn, $insql) or die(mysqli_error($conn));
    //var_Dump($qq);
    $a = mysqli_insert_id($conn);
    //var_dump($a);
    if($a>0){
        echo '<script>window.location.href="main.php?paction=brands_view&msg=New Brand Created Successfully"</script>';
    }

}else if($_POST['doAction']=='Update'){
    //var_dump($_POST);
    $ubrid = mysqli_real_escape_string($conn, $_POST['brid']);
    $ubrand = mysqli_real_escape_string($conn, $_POST['brand']);
    $usql = "update brands set brandname = '$ubrand' where brid = '$ubrid'";
    //echo $usql;
    $uqq = mysqli_query($conn,$usql); 
    //var_Dump($uqq);
    if(mysqli_affected_rows($conn)>0){
        echo '<script>window.location.href="main.php?paction=brands_view&msg=Brand Updated Successfully"</script>';
    }
}

if(isset($_GET['brandid'])) {
    $brandid = mysqli_real_escape_string($conn, $_GET['brandid']);
    $mysql2 = "select brid,brandname from brands where brid=$brandid";
    $sq = mysqli_query($conn,$mysql2);
    $srw = mysqli_fetch_assoc($sq);
    $brid = $srw['brid'];
    $bname = $srw['brandname'];
}else if(isset($_GET['delbrandid']) && !empty($_GET['delbrandid']) && is_numeric['delbrandid']){
    $dbid=mysqli_real_escape_string($conn, $_GET['delbrandid']);
    /**
     * Update delete query to only run if brand id not assigned to products.
     */

    $dsql = "delete from brands where brid='$dbid'";
    //echo $dsql;
    $dqq=mysqli_query($conn,$dsql);
    //echo $dqq;
    if(mysqli_affected_rows($conn)>0){
        echo '<script>window.location.href="main.php?paction=brands_view&msg=Brand Updated Successfully"</script>';
    }
};

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
        <h1 class="page-heading h6 ebold">Brands</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item">Brands</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">All Brands</h5>
        </div>
        <div class="article-content container-max">
            <div class="mb-4">
                <form action="" method="post" autocomplete="off">
                    <input type="hidden" name="doAction" value="<?php echo(!empty($brandid)?'Update':'Add'); ?>">
                    <input type="hidden" name="brid" value="<?php echo(!empty($brandid)?$brid:''); ?>">
                    <div class="row">
                        <div class="col-xl-10 col-sm-8">
                            <div class="form-group mb-sm-0">
                                <label for="brand">Brand Name</label>
                                <input type="text" name="brand" id="brand" class="form-control" value="<?php echo (!empty($brandid)?$bname:''); ?>" required>
                            </div>
                        </div>
                        <div class="col-xl-2 col-sm-4">
                            <div class="input-group justify-content-end align-items-end h-100">
                                <input type="submit" name="brandSubmit" class="btn btn-basic" value="<?php echo(!empty($brandid)?'Update':'Add To')?> Brands">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-big">
                    <thead>
                        <tr>
                            <th>Brand Name</th>
                            <th style="width: 6rem"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        //$query = "select * from brands";
                        $query = "select *,(select brand from sizes where brand = brands.brid limit 1) as brandused from brands";

                        $q2 = mysqli_query($conn,$query);
                        $qrow = mysqli_num_rows($q2);
                        if($qrow>0){

                            while($rw2 = mysqli_fetch_assoc($q2)){?>
                            <tr>
                                <td><?php echo $rw2['brandname']; ?></td>
                                <td class="ws-nowrap">
                                    <a data-toggle="tooltip" title="Edit" href="main.php?paction=brands_view&brandid=<?php echo $rw2['brid']; ?>"  class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                    <?php if(empty($rw2['brandused'])){ ?>
                                    <a data-toggle="tooltip" title="Delete" href="main.php?paction=brands_view&delbrandid=<?php echo $rw2['brid']; ?>"  class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                    <?php   }
                        }else{ ?>
                            <tr><td colspan="2" class="text-center">No Record Found</td></tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

