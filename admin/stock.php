<?php
$perm=check_permission("A");
$whr=1;

if(isset($_GET['product']) && !empty($_GET['product']) && is_numeric($_GET['product'])){
    $prid = mysqli_real_escape_string($conn,$_GET['product']);
    $whr .=" and sizes.prid='$prid' ";
}

if(isset($_GET['brand']) && !empty($_GET['brand']) && is_numeric($_GET['brand'])){
    $brid = mysqli_real_escape_string($conn,$_GET['brand']);
    $whr .=" and sizes.brand ='$brid' ";
}
?>
<div class="main-content-inner">
    <?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
    <?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
    <?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
    <?php if($perm){ ?>
    <div class="page-header">
        <h1 class="page-heading h6 ebold">Stock</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Stock</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">Stock</h5>
            <div class="sort">
                <select name="sort" class="form-control" id="sort">
                    <option value="">Sort By</option>
                    <option value="1">Product ASC</option>
                    <option value="2">Product DESC</option>
                    <option value="3">Size ASC</option>
                    <option value="4">Size DESC</option>
                </select>
            </div>
        </div>
        
        <div class="filter mb-4">
            <form action="" id="filterForm" method="get">
                <input type="hidden" name="paction" value="stock">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <input type="hidden" name="paction" class="finished_stock" value="<?php echo $_GET['paction']; ?>">
                            <label for="product">Filter By Product</label>
                            <select name="product" id="product" class="form-control select2me">
                                <option value="">Select From List</option>
                                <?php 
                                $psql = "select prid,productname from products order by productname asc";
                                $pqq = mysqli_query($conn,$psql);
                                while($prw =mysqli_fetch_array($pqq)){
                                    echo '<option value="'.$prw['prid'].'">'.ucwords($prw['productname']).'</option>';
                                }
                                ?>
                            </select>
                            <input type="hidden" name="sortby" class="sortby">
                        </div>
                    </div>
                    
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="brand">Filter By Brand</label>
                            <select name="brand" id="brand" class="form-control select2me">
                                <option value="">Select From List</option>
                                <?php $bsql = "select brid,brandname from brands order by brandname asc";
                                    $bqq = mysqli_query($conn,$bsql);
                                    while($brw = mysqli_fetch_assoc($bqq)){
                                        echo '<option value="'.$brw['brid'].'">'.$brw['brandname'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <input type="submit" name="filterFinishedStock" class="btn btn-basic" value="Apply Filter">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="article-content container-max">
            <div class="table-responsive">
                <table class="table table-bordered table-big">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product</th>
                            <th>Brand</th>
                            <th class="text-right">Weight(Tons)</th>
                            <th style="width: 3.2rem"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $rsql = "SELECT Sum(sizes.currentstock) as wht, sizes.prid, sizes.brand as brandid, products.productname,
                        (select brandname from brands where brid=sizes.brand) as brandname FROM sizes
                        INNER JOIN products ON products.prid = sizes.prid
                        where $whr 
                         group by prid,brand order by prid desc";
                        $rqq = mysqli_query($conn,$rsql);
                        $x = 1;
                        $whttotal = 0;
                        while($rrw = mysqli_fetch_assoc($rqq)){
                            $whttotal +=$rrw['wht'];
                        ?>

                        <tr>
                            <td><?php echo $x; ?></td>
                            <td><?php echo $rrw['productname']; ?></td>
                            <td><?php echo (empty($rrw['brandname'])?'-':$rrw['brandname']); ?></td>
                            <td class="text-right"><?php echo $rrw['wht']; ?> Tons</td>
                            <td>
                                <a href="main.php?paction=stock_view&prod_id=<?php echo $rrw['prid'];?>&brand=<?php echo $rrw['brandid'];?>" class="btn action-btn btn-success"><i class="bi bi-eye"></i></a>
                            </td>
                        </tr>
                                <?php $x++; } ?>
                        
                        <tr>
                            <th class="text-right" colspan="3">Total</th>
                            <td class="text-right"><?php echo round($whttotal,3); ?> Tons</td>
                            <!-- <td class="text-right">6000Pcs</td> -->
                            <td class="text-right"></td>
                        </tr>
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

<script>
	$('#sort').change(function(){
        let sort = $(this).val();
        $('.sortby').val(sort);
        $('#filterForm').submit();
    });
</script>
