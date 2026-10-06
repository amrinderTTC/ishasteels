<?php

$perm=check_permission("A");

?>

<div class="main-content-inner">
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
            <form action="main.php" id="filterForm" method="post">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <input type="hidden" name="paction" class="finished_stock" value="<?php echo $_GET['paction']; ?>">
                            <label for="product">Filter By Product</label>
                            <select name="product" id="product" class="form-control select2me">
                                <option value="">Select From List</option>
                            </select>
                            <input type="hidden" name="sortby" class="sortby">
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="grade">Filter By Grade</label>
                            <select name="grade" id="grade" class="form-control select2me">
                                <option value="">Select From List</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="size">Filter By Size</label>
                            <select name="size" id="size" class="form-control select2me">
                                <option value="">Select From List</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="brand">Filter By Brand</label>
                            <select name="brand" id="brand" class="form-control select2me">
                                <option value="">Select From List</option>
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
                            <th>Size/Dia(mm)</th>
                            <th>Length</th>
                            <th>Grade</th>
                            <th>Brand</th>
                            <th class="text-right">Weight(Tons)</th>
                            <th class="text-right">Pcs Calculated</th>
                            <!-- <th class="text-center" colspan="2">Invoiced Qty</th> -->
                            <th>Location</th>
                        </tr>
                        <!-- <tr>
                            <th class="text-center">Bundles</th>
                            <th class="text-center">Pcs</th>
                        </tr> -->
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>Flat</td>
                            <td>16x4</td>
                            <td>3M</td>
                            <td>31CrV3</td>
                            <td>-</td>
                            <td class="text-right">20Tons</td>
                            <td class="text-right">4000</td>
                            <td>Shed 1</td>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>Flat</td>
                            <td>16x4</td>
                            <td>3M</td>
                            <td>31CrV3</td>
                            <td>Anmol</td>
                            <td class="text-right">20Tons</td>
                            <td class="text-right">4000</td>
                            <td>Shed 1</td>
                        </tr>
                        <tr>
                            <th class="text-right" colspan="6">Total</th>
                            <td class="text-right">40 Tons</td>
                            <td class="text-right">8000Pcs</td>
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
</div>

<script>
	$('#sort').change(function(){
        let sort = $(this).val();
        $('.sortby').val(sort);
        $('#filterForm').submit();
    });
</script>
