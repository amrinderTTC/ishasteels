<?php
$perm=check_permission("A");


?>

<div class="main-content-inner">
    <div class="page-header">
        <h1 class="page-heading ebold heading5"><?php echo($_GET[sizeid]?"Edit":"Add")?> Size</h1>
        <ul class="list-inline breadcrumb breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Sizes</li>
            <li class="breadcrumb-item"><?php echo($_GET[sizeid]?"Edit":"Add")?> Size</li>
        </ul>
    </div>
    <?php
    if($perm){
    ?>
    <div class="page-content container-max">
        <div class="article">
            <div class="article-heading flex-heading">
                <h5 class="text-center"><?php echo($_GET[sizeid]?"Edit":"Add")?> Size</h5>
                <a href="main.php?paction=sizes_view" class="btn btn-basic btn-sm">All Sizes</a>
            </div>
            <div class="article-content">
                <form action="#" method="post">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="size">Size/Dia (mm)</label>
                                <input type="text" name="size" id="size" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="product">Product Name</label>
                                <select name="product" id="product" class="form-control select2me" required>
                                    <option value="">Select From List</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="weight">Weight (Kg)</label>
                                <div class="input-group">
                                    <input type="number" step="0.001" name="weight" class="form-control" id="weight" value="" required>
                                    <div class="input-group-append">
                                        <select name="weightUnit" class="form-control" required>
                                            <option value="" disabled>Select One</option>
                                            <option value="2" selected>Per Meter</option>
                                            <option value="1">Per Feet</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="length">Standard Length</label>
                                <div class="input-group">
                                    <input type="number" step="0.001" name="length" class="form-control" id="length" value="">
                                    <div class="input-group-append">
                                        <select name="weightUnit" class="form-control">
                                            <option value="" disabled>Select Unit</option>
                                            <option value="2" selected>Meter</option>
                                            <option value="1">Feet</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="grade">Grade</label>
                                <select name="grade" class="form-control select2me">
                                    <option value="">Select Form List</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <select name="brand" class="form-control select2me">
                                    <option value="">Select Form List</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="location">Location</label>
                                <select name="weightUnit" class="form-control">
                                    <option value="">Select Form List</option>
                                    <option value="1">Shed 1</option>
                                    <option value="2">Shed 2</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="openingStock">Opening Stock(Tons)</label>
                                <input type="number" name="openingStock" class="form-control" min="0" step="0.001">
                            </div>
                        </div>
                    </div>
                    <div class="btns text-right mt-4">
                        <input type="hidden" name="paction" value="add_size">
                        <input type="hidden" name="doAction" value="<?php echo ($_GET[sizeid]!=''?'edit':'add')?>" />
                        <button type="submit" name="submt_btn" value="1" class="btn btn-basic"><i class="fa fa-check"></i> 
                        <?php echo ($_GET[sizeid]!=""?"Update":"Add")?> Size</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php }?>
</div>
