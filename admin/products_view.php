<?php

$perm=check_permission("A","OF");
if(isset($_GET['prodid'])){$prodid = mysqli_real_escape_string($conn, $_GET['prodid']);};

if($_POST['doAction']=='Add'){
    
    $product = mysqli_real_escape_string($conn, $_POST['product']);
    $insql = "insert into products set  productname = '$product', createdon = '$createdon', createdby = '$createdby'";
    $qq = mysqli_query($conn, $insql) or die(mysqli_error($conn));
    $a = mysqli_insert_id($conn);
    
    if($a>0){
        echo '<script>window.location.href="main.php?paction=products_view&msg=New Brand Created Successfully"</script>';
    }
}else if($_POST['doAction']=='Update'){
   
    $ubrid = mysqli_real_escape_string($conn, $_POST['prid']);
    $ubrand = mysqli_real_escape_string($conn, $_POST['product']);
    $usql = "update products set productname='$ubrand',modifiedby = '$createdby',
    modifiedon='$createdon' where prid = '$ubrid'"; 
    $uqq = mysqli_query($conn,$usql); 
    
    if(mysqli_affected_rows($conn)>0){
        echo '<script>window.location.href="main.php?paction=products_view&msg=Product Updated Successfully"</script>';
    }
}

if(isset($_GET['prodid'])) {

    $brandid = mysqli_real_escape_string($conn, $_GET['prodid']);
    $mysql2 = "select prid,productname from products where prid=$brandid";
    $sq = mysqli_query($conn,$mysql2);
    $srw = mysqli_fetch_assoc($sq);
    
    $brid = $srw['prid'];
    $bname = $srw['productname'];
}else if(isset($_GET['delprodid']) && !empty($_GET['delprodid']) && is_numeric['delprodid']){

    $dbid=mysqli_real_escape_string($conn, $_GET['delprodid']);
    
    /**
     * Update delete query to only run if brand id not assigned to products.
     */

    $dsql = "delete from products where prid='$dbid'";
    
    $dqq=mysqli_query($conn,$dsql);
    
    if(mysqli_affected_rows($conn)>0){
        echo '<script>window.location.href="main.php?paction=products_view&msg=Product Deleted Successfully"</script>';
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
        <h1 class="page-heading h6 ebold">Products</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item">Products</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">All Products</h5>
        </div>
        <div class="article-content container-max">
            <div class="mb-4">
                <form action="" method="post" autocomplete="off">
                <input type="hidden" name="doAction" value="<?php echo(!empty($brandid)?'Update':'Add'); ?>">
                    <input type="hidden" name="prid" value="<?php echo(!empty($brandid)?$brid:''); ?>">
                    <div class="row">
                        <div class="col-xl-10 col-sm-8">
                            <div class="form-group mb-sm-0">
                                <label for="product">Product Name</label>
                                <input type="text" name="product" id="product" class="form-control" value="<?php echo (!empty($brandid)?$bname:''); ?>" required>
                            </div>
                        </div>
                        <!-- <div class="col-xl-5 col-sm-4">
                            <div class="form-group mb-sm-0">
                                <label for="location">Stock Location</label>
                                <select class="form-control select2me" name="location" id="location">
                                    <option value=''>Select From List</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="col-xl-2 col-sm-4">
                            <div class="input-group justify-content-end align-items-end h-100">
                                <input type="submit" name="productSubmit" class="btn btn-basic" value="<?php echo(!empty($prodid)?'Update':'Add To')?> Products">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-big">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th style="width: 6rem"></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php 
                        $query = "select *,(select prid from sizes where prid=products.prid limit 1) as productused from products order by productname asc";
                        $q2 = mysqli_query($conn,$query);
                        $qrow = mysqli_num_rows($q2);
                        if($qrow>0){

                            while($rw2 = mysqli_fetch_assoc($q2)){?>
                            <tr>
                                <td><?php echo $rw2['productname']; ?></td>
                                <td class="ws-nowrap">
                                    <a data-toggle="tooltip" title="Edit" href="main.php?paction=products_view&prodid=<?php echo $rw2['prid']; ?>"  class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                    <?php if (empty($rw2['productused'])){ ?>
                                    <a data-toggle="tooltip" title="Delete" href="main.php?paction=products_view&delprodid=<?php echo $rw2['prid']; ?>"  class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                    <?php   }
                        }else{ ?>
                            <tr><td colspan="2" class="text-center">No Record Found</td></tr>
                        <?php } ?>
                        <!-- <tr>
                            <td>Angle</td>
                            <td class="ws-nowrap">
                                <a data-toggle="tooltip" title="Edit" href="main.php?paction=products_view&prodid=1"  class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                <a data-toggle="tooltip" title="Delete" href="main.php?paction=products_view&delprodid=1"  class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr> -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

