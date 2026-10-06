<?php

$perm=check_permission("A");
    $vid = $_GET['vid'];
    if(isset($_GET['vid']) && !empty($_GET['vid']) && is_numeric($_GET['vid'])){
        $vsql = "select * from gate where gid=$vid";
        //var_dump($vsql);
        $vsqlq=mysqli_query($conn,$vsql) or die(mysqli_error($conn));
        $vew = mysqli_fetch_assoc($vsqlq);
    }
?>
<div class="main-content-inner">
    <div class="page-header">
        <h1 class="page-heading h6 ebold">Material In</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Finished Stock</li>
            <li class="breadcrumb-item">Material In</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">Return Against Sale Order</h5>
        </div>
        <div class="article-content container-max">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Token No.</th>
                            <th>Vehicle No.</th>
                            <th>Entry Type</th>
                            <th>Party Name</th>
                            <th>Loaded Weight</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="article-heading flex-heading">
            <h5 class="text-center">List Of Unloaded Material</h5>
        </div>
        <div class="article-content container-max">
            <div class="table-responsive">
                <table class="table table-bordered table-xl">
                    <thead>
                        <tr>
                            <th rowspan="2">#</th>
                            <th rowspan="2">Invoice No.</th>
                            <th rowspan="2">Product</th>
                            <th rowspan="2">Size/Dia (mm)</th>
                            <th rowspan="2">Length</th>
                            <th rowspan="2">Grade</th>
                            <th rowspan="2">Brand</th>
                            <th colspan="4" class="text-center">Qty</th>
                            <th rowspan="2">Attachment</th>
                            <th rowspan="2"></th>
                        </tr>
                        <tr>
                            <th>Weight(Tons)</th>
                            <th>Pcs Count</th>
                            <th>Bundles</th>
                            <th>Pcs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>1001</td>
                            <td>Flat</td>
                            <td>25x6</td>
                            <td>5M</td>
                            <td>31CrV3</td>
                            <td>Anmol</td>
                            <td>23.000</td>
                            <td>480</td>
                            <td>5</td>
                            <td>5</td>
                            <td><a href="assets/images/vital-steel-bars-llp-logo.png" class="attachemntView btn action-btn btn-success" data-modal-heading="Attachment"><i class="bi bi-eye"></i></a></td>
                            <td><button class="btn btn-basic btn-sm">Remove</button></td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>1001</td>
                            <td>Angle</td>
                            <td>25x6</td>
                            <td>5M</td>
                            <td>31CrV3</td>
                            <td>-</td>
                            <td>23.000</td>
                            <td>240</td>
                            <td>2</td>
                            <td>15</td>
                            <td><a href="assets/images/vital-steel-bars-llp-logo.png" class="attachemntView btn action-btn btn-success" data-modal-heading="Attachment"><i class="bi bi-eye"></i></a></td>
                            <td><button class="btn btn-basic btn-sm">Remove</button></td>
                        </tr>
                        <tr>
                            <th colspan="7">Total</th>
                            <td>46.000 Tons</td>
                            <td>720 Pcs</td>
                            <td>7</td>
                            <td>20</td>
                            <td colspan="3"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between">
                <a href="main.php?paction=material_in_stock" class="btn btn-basic">Send For Weight</a>
            </div>
        </div>
    </div>
    <div class="article mt-4">
        <div class="article-heading flex-heading">
            <h5 class="text-center">Unload Material</h5>
        </div>
        <div class="article-content container-max">
            <form action="#">
                <div class="row">
                    <div class="col-xl-2 col-lg-3 col-sm-4 col-6 px-2">
                        <div class="form-group">
                            <label for="invoiceNo">Invoice No.</label>
                            <input type="text" name="invoiceNo" id="invoiceNo" class="form-control">
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-sm-4 col-6 px-2">
                        <div class="form-group">
                            <label for="product">Product</label>
                            <select name="product" id="product" class="form-control select2me">
                                <option value="">Select From List</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-sm-4 col-6 px-2">
                        <div class="form-group">
                            <label for="size">Size/Dia(mm)</label>
                            <select name="size" id="size" class="form-control select2me">
                                <option value="">Select From List</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-sm-4 col-6 px-2">
                        <div class="form-group">
                            <label for="length">Length</label>
                            <div class="input-group">
                                <input type="number" min="0" name="length" class="form-control">
                                <div class="input-geoup-append">
                                    <select name="lengthUnit" id="lengthUnit" class="form-control">
                                        <option value="">Unit</option>
                                        <option value="1">Feet</option>
                                        <option value="2" selected>Meter</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-sm-4 col-6 px-2">
                        <div class="form-group">
                            <label for="grade">Grade</label>
                            <select name="grade" id="grade" class="form-control select2me">
                                <option value="">Select From List</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-3 col-sm-4 col-6 px-2">
                        <div class="form-group">
                            <label for="brand">Brand</label>
                            <select name="brand" id="brand" class="form-control select2me">
                                <option value="">Select From List</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-sm-4 col-6 px-2">
                        <div class="form-group">
                            <label for="weight">Weight (In invoice)</label>
                            <input type="number" min="0" name="weight" class="form-control">
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-sm-4 col-6 px-2">
                        <div class="form-group">
                            <label for="bundles">Qty(Bundle)</label>
                            <input type="number" min="0" name="bundles" class="form-control">
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-sm-4 col-6 px-2">
                        <div class="form-group">
                            <label for="pcs">Qty (Pcs)</label>
                            <input type="number" min="0" name="pcs" class="form-control">
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-7 col-sm-7 px-2">
                        <div class="form-group">
                            <label for="attachment">Attachment If Any</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <button class="btn btn-basic" type="submit">Add To Received Items</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="attachemntView">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Attachment</h4>
                <button type="button" class="close" data-dismiss="modal"><i class="bi bi-x"></i></button>
            </div>
            <div class="modal-body text-center">
                <img class="img-fluid" src="assets/images/vital-steel-bars-llp-logo.png">
            </div>
        </div>
    </div>
</div>
<script>
    $(document).on('click', '.attachemntView', function(e){
        e.preventDefault();
        let src = $(this).attr('href');
        let modalHeading = $(this).data('modal-heading');
        let slipViewModal = $('#attachemntView');
        slipViewModal.find('img').attr('src', src);
        slipViewModal.find('.modal-title').text(modalHeading)
        slipViewModal.modal();
    });
</script>
