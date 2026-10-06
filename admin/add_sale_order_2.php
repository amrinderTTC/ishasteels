<?php
$perm=check_permission("A");
$sid=mysqli_real_escape_string($conn,$_GET['slid']);

if($_POST['doAction'] == 'createso'){
    $cid= mysqli_real_escape_string($conn,$_POST['customer']);
    $cperson= mysqli_real_escape_string($conn,$_POST['contactPerson']);
    $orderqty= mysqli_real_escape_string($conn,$_POST['orderQty']);
    
    $isql="insert into saleorder set cid = '$cid',cperson = '$cperson',orderqty ='$orderqty',
    createdon = '$createdon',createdby = '$createdby'";
    $inqq=mysqli_query($conn,$isql);
    $slid=mysqli_insert_id($conn);
    if($slid>0){
        echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$slid.'&msg=Sale Order Created Successfully. Add Items Now."</script>';
    }
}

if(isset($_GET['slid']) && !empty($_GET['slid']) && is_numeric($_GET['slid'])){

    if(isset($_GET['delprodid'])){
        $dslid=mysqli_real_escape_string($conn,$_GET['delprodid']);
        $delsql="delete from saleitems where slitemid='$dslid'";
        //echo $delsql;
        $dqq = mysqli_query($conn,$delsql);
        if(mysqli_affected_rows($conn)>0){
            echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$sid.'&msg=Item Deleted From Sales Order Successfully"</script>';
        }
    }

    if(isset($_GET['prodid'])){
        if(!empty($_GET['prodid']) && is_numeric($_GET['prodid'])){
            $pid=mysqli_real_escape_string($conn,$_GET['prodid']);
            $spid = "SELECT saleitems.slitemid, saleitems.slid, saleitems.prodid,saleitems.size, saleitems.gradeid, saleitems.brandid,
            saleitems.length, saleitems.lenunit, saleitems.lentyp, saleitems.qty,saleitems.qtyunit, saleitems.price, saleitems.gst FROM saleitems
            where slitemid = $pid";
            $spqq = mysqli_query($conn,$spid);
            $sprw = mysqli_fetch_assoc($spqq);
        }
    }

    $cxsql = "SELECT saleorder.cid,contacts.cid as contid, contacts.cperson,contacts.countrycode, contacts.contact, contacts.designation,saleorder.slid,
    contacts.email FROM saleorder INNER JOIN contacts ON contacts.cid = saleorder.cperson where saleorder.slid =$sid";
    $cxqq = mysqli_query($conn,$cxsql);
    $crx = mysqli_fetch_assoc($cxqq);
}
//var_Dump($_POST);
?>
<div class="main-content-inner">
<?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
    <?php
    if($perm){
    ?>
    <div class="page-header">
        <h1 class="page-heading ebold heading5"><?php echo($_GET['slid']?"Edit ":"Add "); echo($typ); ?> Sale Order</h1>
        <ul class="list-inline breadcrumb breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Manage Order</li>
            <li class="breadcrumb-item"><?php echo($_GET['slid']?"Edit ":"Add "); echo($typ);?> Sale Order</li>
        </ul>
    </div>
    
    <div class="page-content container-max">
        <div class="article">
            <div class="article-heading flex-heading">
                <h5 class="text-center"><?php echo($_GET['slid']?"Edit ":"Add "); ?> Sale Order</h5>
                <a href="main.php?paction=sale_order_view" class="btn btn-basic btn-sm">Sale Orders</a>
            </div>
            <div class="article-content">
                <form action="" method="post">
                <input type ="hidden" name="doAction" value="<?php echo(isset($_GET['slid'])?'updateso':'createso'); ?>">
                    <h6 class="bold text-uppercase mb-3">Customer Details</h6>
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="customer">Customer Name</label>
                                <select name="customer" class="form-control select2me" id="customer" <?php echo(isset($_GET['slid'])?'disabled':'required'); ?>>
                                    <option value="" disabled selected>Select Customer1</option>
                                    <?php
                                        $csql = "select cust_id,name from customers";
                                        $cqq = mysqli_query($conn,$csql);
                                        while($crw = mysqli_fetch_assoc($cqq)){
                                            echo '<option value="'.$crw['cust_id'].'"'.($crw['cust_id']==$crx['cid']?'selected':'').'>'.$crw['name'].'</option>';
                                        }
                                    ?>
                                    
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="contactPerson">Contact Person</label>
                                <select name="contactPerson" class="form-control" id="contactPerson" value="<?php echo $editrow[name];?>" required>
                                    <option value="" disabled selected>Select Contact Person</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="designation">Designation</label>
                                <input type="text" name="designation" class="form-control" id="designation" value="<?php echo $editrow[name];?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" class="form-control" id="email" value="<?php echo $editrow[name];?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label for="contactNumber">Contact Number</label>
                            <div class="input-group">
                                <!-- <div class="input-group-prepend">
                                    <select name="countrycode" class="form-control" readonly>
                                        <option value="">Country Code</option>
                                    </select>
                                </div> -->
                                <input type="hidden" name="countrycode"  value="101">
                                <input type="number" min="0" name="contactNumber" class="form-control" id="contactNumber" value="<?php echo $editrow[name];?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label for="orderQty">Order Qty</label><!-- value in tons -->
                            <input type="number" min="0" step="0.001" name="orderQty" class="form-control" id="orderQty" value="">
                        </div>
                    </div>
                    <div class="btns text-right mt-4">
                        <button type="submit" name="submt_btn" value="1" class="btn btn-basic"><i class="fa fa-check"></i> 
                        <?php echo ($_GET[contact_id]!=""?"Update":"Add")?> To Orders</button>
                    </div>
                </form>
                <form action="#" method="post">
                    <h6 class="bold text-uppercase mb-3 mt-4">Add Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-med">
                            <thead>
                            <tr>
                                    <th>Item Name <span class="text-danger">*</span></th>
                                    <th>Grade <span class="text-danger">*</span></th>
                                    <th>Rate/Ton <span class="text-danger">*</span></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control select2me" name="item">
                                            <option value="">Select From List</option>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2me" name="grade">
                                            <option value="">Select From List</option>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control" name="itemsubmit" value="Add To Items">
                                    </td>
                                    <td>
                                        <input type="submit" class="btn btn-basic btn-sm" name="itemsubmit" value="Add To Items">
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
                <form action="#">
                    <h6 class="bold text-uppercase mb-3 mt-4">Items in List</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-big">
                            <thead>
                                <tr>
                                    <th style="width: 230px">Item Name</th>
                                    <th>Sizes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>
                                        <div class="reportThumb my-1 mx-2">
                                            <a href="#" data-toggle="tooltip" class="btn action-btn btn-primary no-print" title="" data-original-title="Edit"><i class="bi bi-pencil-square"></i></a>
                                            <span class="pr-4">Angle</span><br>
                                            <span class="mb-0">Rs 53600/Tons | 31CrV3</span>
                                        </div>
                                    </th>
                                    <td style="vertical-align: middle;">
                                        <div class="row no-gutters">
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-4">
                                                <div class="reportThumb my-1 mx-2">
                                                    <a href="#" data-toggle="tooltip" class="btn action-btn btn-primary no-print" title="" data-original-title="Edit"><i class="bi bi-pencil-square"></i></a>
                                                    <span class="pr-4">25x25x4 | 5 Mtr</span><br>
                                                    <span class="mb-0">Anmol | 15 Quintal</span>
                                                </div>
                                            </div>
                                            <div class="col-lg-1 col-sm-2 col-2 justify-content-center">
                                                <button type="button" data-itemid="2" class="btn bg-success btn-round addsize text-white mx-auto"><i class="bi bi-plus"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="addsizeModal">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="bold">Add Size To Item</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <from action="#" method="post">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="size" class="d-block">Size | Std Length</label>
                                    <select name="size" class="form-control select2me" required>
                                        <option value="">Select From List</option>
                                        <option value="">25x4 | 18 Feet</option>
                                    </select>
                                    <input type="hidden" name="itemid" id="itemid">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group w-100">
                                    <label for="brand" class="d-block">Brand</label>
                                    <select name="brand" class="form-control select2me w-100">
                                        <option value="">Select From List</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="qty">Qty</label>
                                    <div class="input-group">
                                        <input type="number" name="qty" class="form-control" required>
                                        <div class="input-group-append">
                                            <select name="qtyunit" class="form-control">
                                                <option value="">Select Unit</option>
                                                <option value="1">Tons</option>
                                                <option value="2">Pcs</option>
                                                <option value="3">Quintal</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <input type="submit" name="addtosize" class="btn btn-basic">
                        </div>
                    </from>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
<script type="text/javascript">
    $(document).on('click', '.addsize', function(){
        let itemid = $(this).data('itemid');
        $('#addsizeModal').find('#itemid').val(itemid);
        $('#addsizeModal').modal()
    });

    $('#customer').change(function(){
        let cstid = $('#customer').val();
        $.ajax({  
            type:'post',
            url:"Ajax.php",
            data:{custid:cstid,doAction:'getcontacts'},
            success:function(data){
                console.log(data);
                let di = JSON.parse(data);
                // console.log(di);
                // console.log(di.length);
                let html ='<option value="" disabled selected>Select Contact Person</option>';
                for(let x = 0; x<di.length;x++){
                    html+='<option value="'+di[x].cid+'">'+di[x].cperson+'</option>';
                }
                //console.log(html);
                $('#contactPerson').html(html);
            }
        });
    });

    $('#contactPerson').change(function(){
        let csid = $('#contactPerson').val();
        $.ajax({  
            type:'post',
            url:"Ajax.php",
            data:{csid:csid,doAction:'contactdetail'},
            success:function(data){
                $('#designation').val(data.designation);
                let di = JSON.parse(data);
                $('#designation').val(di.designation);
                $('#email').val(di.email);
                $('#contactNumber').val(di.contact);
                $('select[name=countrycode]').val("");
                $('select[name=countrycode] option[value="'+di.countrycode+'"]').prop('selected', true);
            }
        });
    });

</script>