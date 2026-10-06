<?php

$perm=check_permission("A");
if($_POST['doAction']=='createg'){
    //var_Dump($_POST);
    $grade = mysqli_real_escape_string($conn, $_POST['grades']);
    $gradedesc = mysqli_real_escape_string($conn, $_POST['gradedescription']);
    $gisql = "insert into grade set
    grade ='$grade',
    gradedesc = '$gradedesc',
    createdon = '$createdon',
    createdby = '$createdby'";
    //echo $gisql;
    $gqq = mysqli_query($conn,$gisql);
    $gid = mysqli_insert_id($conn);
    
    if($gid>0){
        echo '<script>window.location.href="main.php?paction=grades_view&msg=New Grade Created Successfully."</script>';
    }
}else if($_POST['doAction']=='updateg'){
    $gids =  mysqli_real_escape_string($conn, $_POST['gid']);
    $grade = mysqli_real_escape_string($conn, $_POST['grades']);
    $gradedesc = mysqli_real_escape_string($conn, $_POST['gradedescription']);
    $gisql = "update grade set
    grade ='$grade',
    gradedesc = '$gradedesc',
    modifiedon = '$createdon',
    modifiedby = '$createdby'
    where gid='$gids'";
    $gqq = mysqli_query($conn,$gisql);
    $rgid = mysqli_affected_rows($conn);
    //echo $rgid;
    if($rgid>0){
        echo '<script>window.location.href="main.php?paction=grades_view&msg=Selected Grade Updated Successfully"</script>';
    }
}

if(isset($_GET['gradeid']) && !empty($_GET['gradeid']) && is_numeric($_GET['gradeid'])){
    $sgid = mysqli_real_escape_string($conn, $_GET['gradeid']);
    $sgr = "select gid,grade,gradedesc from grade where gid=$sgid";
    $sgqq = mysqli_query($conn,$sgr);
    $sr = mysqli_fetch_array($sgqq);
    
}

if(isset($_GET['delgradeid']) && !empty($_GET['delgradeid']) && is_numeric($_GET['delgradeid'])){
    $dgid = mysqli_real_escape_string($conn, $_GET['delgradeid']);
    $dsql = "delete from grade where gid = $dgid";
    $dqq = mysqli_query($conn,$dsql);
    $dgidr = mysqli_affected_rows($conn);
    
    echo $dgidr;

    //if($dgidr>0){
        echo '<script>window.location.href="main.php?paction=grades_view&msg=Selected Grade Deleted Successfully"</script>';
    //}

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
        <h1 class="page-heading h6 ebold">All Grades</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">All Grades</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">All Grades</h5>
            <a href="main.php?paction=grades_add" class="btn btn-basic btn-sm">Add Grades</a>
        </div>
        <div class="article-content container-max">
            <div class="gradeAddEdit mb-4">
                <form action="" method="post">
                    <!-- <input type="hidden" name="paction" value="grades_view"> -->
                    <input type="hidden" name="doAction" value="<?php echo($_GET['gradeid']?'updateg':'createg');?>">
                    <div class="row">
                        <div class="col-xl-3 col-md-4 col-sm-4">
                            <div class="form-group mb-md-0">
                                <label for="grade">Grade Name</label>
                                <input type="text" class="form-control" name="grades" id="grades" value="<?php echo $sr['grade']; ?>">
                            </div>
                        </div>
                        <div class="col-xl-7 col-md-5 col-sm-8">
                            <div class="form-group mb-md-0">
                                <label for="gradedescription">Grade Description</label>
                                <input type="text" class="form-control" name="gradedescription" id="gradedescription" value="<?php echo $sr['gradedesc']; ?>">
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-3 col-sm-12">
                            <?php if(!empty($_GET['gradeid'])){
                                echo '<input type="hidden" class="form-control" name="gid" id="gid" value="'.$sr['gid'].'">';
                            } ?>
                            <div class="input-group h-100 justify-content-end justify-content-md-start align-items-end">
                                <input type="submit" class="btn btn-basic" name="gradeSubmit" id="gradeSubmit" value="<?php echo($_GET['gradeid']?'Update':'Add To');?> Grade">
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
                            <th>Grade Name</th>
                            <th>Grade Description</th>
                            <th style="width: 6rem;"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($_GET['per_page']){
                            $noprd=$_GET['per_page'];
                        }else{
                            $noprd=15;
                        }
                        
                        $orderby = "order by createdon desc";
                        $cpage=$_REQUEST['page'];
                        if ($cpage == 0){
                            $cpage=1;
                            $cnt=1;
                        }else{
                            $cnt=($cpage * $noprd) - $noprd + 1;
                        }
                        
                        $frm=($cpage * $noprd) - $noprd;
                        $whr=1;
                         $query = "select gid,grade,gradedesc,(select grade from sizes where sizes.grade=grade.gid limit 1) as gradeused from grade where $whr";
                         $sql=$query;
                         $query.=" $orderby LIMIT $frm, $noprd";
                         
                         $sqq = mysqli_query($conn,$query);
                         
                         while($srw=mysqli_fetch_assoc($sqq)){
                        ?>
                        <tr>
                            <td><?php echo $cnt; ?></td>
                            <td><?php echo $srw['grade']; ?></td>
                            <td><?php echo $srw['gradedesc']; ?></td>
                            <td>
                                <a href="main.php?paction=grades_view&gradeid=<?php echo $srw['gid']; ?>" class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                <?php if(empty($srw['gradeused'])){ ?>
                                    <a href="main.php?paction=grades_view&delgradeid=<?php echo $srw['gid']; ?>" class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php $cnt++; } ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagging -->
            <div class="pagging">
                    <div class="right">
                        <?php
                        include('ps_pagination.php');
                        $pager = new PS_Pagination($conn, $sql, $noprd, 6, "paction=$_GET[paction]");
                        $pager->setDebug(false);
                        $pager->total_rows=$result;
                        $rs = $pager->paginate();
                        echo $pager->renderFirst();
                        echo $pager->renderPrev("Back");
                        echo $pager->renderNav('<span>', '</span>');
                        echo $pager->renderNext("Next");
                        echo $pager->renderLast();
                        ?>
                    </div>
                </div>
            <!-- End Pagging -->
        </div>
    </div>
    <?php } ?>
</div>
