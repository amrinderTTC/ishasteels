<?php

$perm=check_permission("A","OF");
if(isset($_GET['sizeid'])){$prodid = $_GET['prodid'];};
if(isset($_GET['delprodid']) && !empty($_GET['delprodid']) && is_numeric($_GET['delprodid'])){
    //var_dump($_GET);
    $dsid = mysqli_real_escape_string($conn, $_GET['delprodid']);
    $dsql = "delete from sizes where sid='$dsid'";
    //echo $dsql;
    $dqq = mysqli_query($conn,$dsql);

    //if(mysqli_affected_rows($dqq)>0){
        echo '<script>window.location.href="main.php?paction=sizes_view&msg=Size Deleted Successfully"</script>';
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
        <h1 class="page-heading h6 ebold">All Sizes</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item">Sizes</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">All Sizes</h5>
            <a href="main.php?paction=add_size" class="btn btn-basic">Add New Size</a>
        </div>
        <div class="article-content container-max">
            <div class="mb-4">
                <form action="" method="get">
                    <input type="hidden" name="paction" value="sizes_view">
                    <input type="hidden" name="doAction" value="searchnow">
                    <div class="row">
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="form-group mb-md-0">
                                <label for="product">Product Name</label>
                                <select name="product" id="product" class="form-control select2me" >
                                    <option value="">Select From List</option>
                                    <?php
                                    $psql = "select prid,productname from products";
                                    $pqq = mysqli_query($conn,$psql);
                                    while($prw = mysqli_fetch_assoc($pqq)){
                                        echo '<option value="'.$prw['prid'].'" '.($prw['prid']==$_GET['product']?' selected':'').'>'.$prw['productname'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="form-group mb-md-0">
                                <label for="brand">Brand</label>
                                <select name="brand" id="brand" class="form-control select2me" >
                                    <option value="">Select From List</option>
                                    <?php
                                    $bsql = "select brid,brandname from brands order by brandname desc";
                                    $bqq = mysqli_query($conn,$bsql);

                                    while($brw = mysqli_fetch_assoc($bqq)){
                                        echo '<option value="'.$brw['brid'].'"'.($brw['brid']==$_GET['brand']?' selected':'').'>'.ucwords($brw['brandname']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-3 col-md-4 col-sm-6">
                            <div class="form-group mb-md-0">
                                <label for="size">Size</label>
                                <select name="size" id="size" class="form-control select2me" >
                                    <option value="">Select From List</option>
                                    <?php
                                    $ssql = "select DISTINCT sid,size from sizes order by sid DESC";
                                    $sqq = mysqli_query($conn,$ssql);

                                    while($srw = mysqli_fetch_assoc($sqq)){
                                        echo '<option value="'.$srw['sid'].'"'.($srw['sid']==$_GET['size']?' selected':'').'>'.ucwords($srw['size']).'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xl-2 col-md-4">
                            <div class="input-group justify-content-end align-items-end h-100">
                                <input type="submit" name="sizeFilter" class="btn btn-basic" value="Apply Filter">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-big">
                    <thead>
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Product Name</th>
                            <th rowspan="2">Size(mm)</th>
                            <th colspan="2" class="text-center">Weight (Kg)</th>
                            <th rowspan="2">Standard Length</th>
                            <th rowspan="2">Grade</th>
                            <th rowspan="2">Brand</th>
                            <th rowspan="2">Avg Bundle Weight</th>
                            <th rowspan="2">Current Stock</th>
                            <th rowspan="2">Location</th>
                            <th rowspan="2" style="width: 6rem"></th>
                        </tr>
                        <tr>
                            <th>Per Feet</th>
                            <th>Per Meter</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if($_GET['doAction']=="searchnow"){
                           // var_Dump($_GET);
                        }
                        if($_GET['per_page']){
                            $noprd=$_GET['per_page'];
                        }else{
                            $noprd=60;
                        }
                        
                       // $orderby = "order by dt desc";
                        $cpage=$_REQUEST['page'];
                        if ($cpage == 0){
                            $cpage=1;
                            $cnt=1;
                        }else{
                            $cnt=($cpage * $noprd) - $noprd + 1;
                        }
                        
                        $frm=($cpage * $noprd) - $noprd;
                        $whr=1;
                        if(isset($_GET['product'])){
                            if(!empty($_GET['product']) && is_numeric($_GET['product'])){
                                $whr .=" and sizes.prid='".$_GET['product']."' ";
                            }
                        }
                        
                        if(isset($_GET['brand'])){
                            if(!empty($_GET['brand']) && is_numeric($_GET['brand'])){
                                $whr .=" and sizes.brand='".$_GET['brand']."' ";
                            }
                        }

                        if(isset($_GET['size'])){
                            if(!empty($_GET['size']) && is_numeric($_GET['size'])){
                                $whr .=" and sizes.sid='".$_GET['size']."' ";
                            }
                        }
                    //     $query = "SELECT products.productname,sizes.size,sizes.mtweight,sizes.sid,
                    //     sizes.ftweight,sizes.lengthtype,sizes.stdlength, sizes.weighttype
                    //     FROM products INNER JOIN sizes ON sizes.prid = products.prid where $whr";
                    //     $sql=$query;
                    //     $query.=" $orderby LIMIT $frm, $noprd";
                    //     echo $query;
                    //     $qq = mysqli_query($conn,$query);
                    //     $rcnt = mysqli_num_rows($qq);
                    //     if($rcnt>0){
                    //         while($rw = mysqli_fetch_assoc($qq)){ 
                        
                        // $query = "SELECT products.productname,sizes.size,sizes.mtweight,sizes.sid,
                        // sizes.ftweight,sizes.lengthtype,sizes.stdlength, sizes.weighttype
                        // FROM products INNER JOIN sizes ON sizes.prid = products.prid where $whr";
                        //$whr ='1';
                        $query = "SELECT products.productname, sizes.size, sizes.mtweight, sizes.sid,
                            sizes.ftweight, sizes.lengthtype, sizes.stdlength, sizes.weighttype, sizes.currentstock, sizes.bundleweight, grade.grade,(select brandname from brands where brid = sizes.brand) as brand,
                            sizes.brand as brandid, (select locname from location where lid=sizes.location) as location,(select sosizeid from saleordersizes where sosizeid=sizes.sid limit 1) as sizeused
                            FROM products INNER JOIN sizes ON sizes.prid = products.prid
                            INNER JOIN grade ON grade.gid = sizes.grade where $whr
                        ";
                        
                        $sql=$query;
                        $query.=" $orderby LIMIT $frm, $noprd";
                        //echo $query;
                        $qq = mysqli_query($conn,$query);
                        $rcnt = mysqli_num_rows($qq);
                        while($rw = mysqli_fetch_assoc($qq)){ 
                            ?>
                            <tr>
                                <td><?php echo $cnt;?></td>
                                <td><?php echo ucwords($rw['productname']); ?></td>
                                <td><?php echo $rw['size']; ?></td>
                                <td><?php echo $rw['ftweight']; ?></td>
                                <td><?php echo $rw['mtweight']; ?></td>
                                <td><?php echo $rw['stdlength']; 
                                echo ($rw['lengthtype']=='2'?' Mt.':' Ft.')?></td>
                                <td><?php echo ucwords($rw['grade']); ?></td>
                                <td><?php echo ucwords((empty($rw['brand'])?'-':$rw['brand'])); ?></td>
                                <td><?php echo (!empty($rw['bundleweight'])?$rw['bundleweight'].' kg.':'N/A'); ?></td>
                                <td><?php echo $rw['currentstock']; ?> Tons</td>
                                <td><?php echo ucwords(empty($rw['location'])?'-':$rw['location']); ?></td>
                                <td class="ws-nowrap">
                                    <a data-toggle="tooltip" title="Edit" href="main.php?paction=add_size&sizeid=<?php echo $rw['sid']; ?>"  class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                    <?php if(empty($rw['sizeused'])){ ?>
                                    <a data-toggle="tooltip" title="Delete" href="main.php?paction=sizes_view&delprodid=<?php echo $rw['sid']; ?>"  class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php
                            $cnt++;
                        }
                        ?>
                        
                    </tbody>
                </table>
            </div>
            <!-- Pagging -->
            <div class="pagging">
                <div class="right">
                    <?php
                    include('ps_pagination.php');
                    $pager = new PS_Pagination($conn, $sql, $noprd, 6, "paction=$_GET[paction]&product=$_GET[product]&brand=$_GET[brand]&size=$_GET[size]");
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
<script>
    $('#product').change(function(){
        let pid = $('#product').val();
        $.ajax({
            url: 'Ajax.php',
            type: 'GET',
            data: {sprodid:pid},
            success: function(data) {
                let di = JSON.parse(data);
                html='<option value="">Select From List</option>';
                
                for(let x=0; x<di.length; x++){
                    html +='<option value="'+di[x].sid+'">'+di[x].size+'</option>';
                }
                console.log(html);
                $('#size').html(html);
                //$('#size').html('html');
            }
        });
    });

    // $('#brand').change(function(){
    //     let pid=$('#product').val();
    //     let bid=$('#brand').val();

    // });
</script>

