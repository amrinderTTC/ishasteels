<?php
$perm=check_permission("A","OF","OD");

$sid=mysqli_real_escape_string($conn,$_GET['slid']);
//var_Dump($_POST);
if($_POST['doAction'] == 'createso'){
    $cid=mysqli_real_escape_string($conn,$_POST['customer']);
    $cperson= mysqli_real_escape_string($conn,$_POST['contactPerson']);
    //$orderqty= mysqli_real_escape_string($conn,$_POST['orderQty']);
        $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);
    $item =$_POST['item'];
    $grade = $_POST['grade'];
    $brand = $_POST['brand'];
    $size = $_POST['size'];
    $price = $_POST['price'];
    $qty = $_POST['qty'];
    $qtyunit =$_POST['qtyunit'];
$itemremarks = $_POST['itemremarks'];

    $isql="insert into saleorder set cid = '$cid',cperson = '$cperson',remarks='$remarks',
    createdon = '$createdon',createdby = '$createdby'";
    $inqq=mysqli_query($conn,$isql);
    $slid=mysqli_insert_id($conn);

    if($slid>0){
        $er =0;
        for($x=0;$x<count($item);$x++){
            //echo "stage3";
            /**
             * Get size std weight mtwght ftwght
             */
               
                $sizesql = "select mtweight,ftweight,stdlength,lengthtype,bundleweight from sizes where sizes.sid = $size[$x]";
                
                $sizeqq = mysqli_query($conn,$sizesql);
                $sizeinfo = mysqli_fetch_assoc($sizeqq);
                
            //weight conversion
            if($qtyunit[$x]==3){//quintals
                //qunital to tons conversion
                $weightintons=quintaltotons($qty[$x]);
                //weigth and pcs conversion  returns array[pcs,weight]
                $res = wtpcconvert($sizeinfo['stdlength'],$sizeinfo['lengthtype'],$sizeinfo['mtweight'],$sizeinfo['ftweight'],$weightintons,'1',$sizeinfo['bundleweight']);
            }else{
                //weigth and pcs conversion  returns array[pcs,weight]
                $res = wtpcconvert($sizeinfo['stdlength'],$sizeinfo['lengthtype'],$sizeinfo['mtweight'],$sizeinfo['ftweight'],$qty[$x],$qtyunit[$x],$sizeinfo['bundleweight']);
            }
            
           
                $xsa = "insert into saleordersizes set
                sosaleid = '$slid',
                soprid='$item[$x]',
                sogradeid = '$grade[$x]',
                sobrandid='$brand[$x]',
                sosizeid='$size[$x]',
                qty = '$qty[$x]',
                qtytype='$qtyunit[$x]',
                weightintons='$res[1]',
                pcs = '$res[0]',
                soprice='$price[$x]',
                itemremarks='$itemremarks[$x]',
                createdon='$createdon',
                createdby='$createdby'";
             
             if($res[1]!=0 && $res[0]!=0){
                mysqli_query($conn,$xsa);
            }else{
                $er = $er+1;
            }
        }
        
        if($er>0){
            echo '<script>window.location.href="main.php?paction=sale_order_view&msg=Order Updated successfully But Some Sized were not Added due to Short order qty."</script>';    
        }else{
            echo '<script>window.location.href="main.php?paction=sale_order_view&msg=Sale Order Created Successfully"</script>';   
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
                                <?php if(isset($_GET['slid'])){ ?>
                                    <input type ="hidden" name="customer" value="<?php echo $crx['cid']; ?>">
                                <?php } ?>
                                <select name="customer" class="form-control select2me" id="customer" <?php echo(isset($_GET['slid'])?'disabled':'required'); ?>>
                                    <option value="" disabled selected>Select Customer</option>
                                    <option value="">Customer Name</option>
                                    <?php 
                                    $csql = "select cust_id,name from customers where typ='CU'";
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
                                <select name="contactPerson" class="form-control" id="contactPerson" required>
                                    <option value="" disabled selected>Select Contact Person</option>
                                <?php
                                if(!empty($sid)){
                                    $cpsql = "select cid,cperson from contacts where cust_id =$crx[cid]";
                                    $cpqq = mysqli_query($conn,$cpsql);
                                    while($cprw = mysqli_fetch_assoc($cpqq)){
                                        echo '<option value="'.$cprw['cid'].'"'.($cprw['cid'] ==$crx['contid']?'selected':'').'>'.$cprw['cperson'].'</option>';
                                    }
                                }
                                ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="designation">Designation</label>
                                <input type="text" name="designation" class="form-control" id="designation" value="<?php echo $crx['designation'];?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" class="form-control" id="email" value="<?php echo $crx['email'];?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label for="contactNumber">Contact Number</label>
                            <div class="input-group">
                                <input type="hidden" name="countrycode"  value="101">
                                <input type="number" min="0" name="contactNumber" class="form-control" id="contactNumber" value="<?php echo $crx['contact'];?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="email">Remarks</label>
                                <input type="remarks" name="remarks" class="form-control" id="remarks" value="<?php echo $crx['remarks'];?>">
                            </div>
                        </div>
                        
                        <?php if(isset($_GET['slid'])){ ?>
                            <input type="hidden" name="soid" value="<?php echo $crx['slid']; ?>">
                        <?php } ?>
                        
                    </div>
                    <h6 class="bold text-uppercase mb-3 mt-4">Add Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-med itemtable">
                            <thead>
                                <?php
                                $saleOrderSlid = isset($_GET['slid']) && $_GET['slid'] !== '' ? (int)$_GET['slid'] : 0;
                                $qa = "SELECT saleorderproducts.sopid, saleorderproducts.slid,saleorderproducts.gradeid, saleorderproducts.price, products.productname, grade.grade,saleorderproducts.prodid,(select count(*) from saleordersizes where sosaleid=saleorderproducts.slid and soprid=saleorderproducts.prodid) as scnt
                                FROM saleorderproducts INNER JOIN products ON products.prid = saleorderproducts.prodid INNER JOIN grade ON grade.gid = saleorderproducts.gradeid where saleorderproducts.slid=$saleOrderSlid";
                                $qaq = mysqli_query($conn,$qa);
                                $s=1;
                                ?>
                                
                            <tr>
                                <th>S. No.</th>
                                <th>Item Name <span class="text-danger">*</span></th>
                                <th>Grade <span class="text-danger">*</span></th>
                                <th>Brand <span class="text-danger">*</span></th>
                                <th style="width: 11rem">Size <span class="text-danger">*</span></th>
                                <th>Rate/Ton </th>
                                <th>Avl Qty</th><!-- (Tons|Pcs) -->
                                <th>Booked</th>
                                <th style="width: 15rem">Qty<span class="text-danger">*</span></th>
                                <th>Item Remarks</th>
                                <th><button type="button" class="btn action-btn btn-warning additem"><i class="fa fa-plus"></i></button></th>
                            </tr>
                            </thead>
                            <tbody>
                                
                                <tr>
                                    <td class="order">1</td>
                                    <td>
                                        <select class="form-control select2me product" name="item[]" required>
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select prid,productname from products order by productname ASC";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prorw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prorw['prid'].'" '.($ugrw['prodid']==$prorw['prid']?'selected':'').'>'.$prorw['productname'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2me grade" name="grade[]" required>
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select gid,grade from grade order by gid asc";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prodrw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prodrw['gid'].'" '.(($ugrw['gradeid']==$prodrw['gid'] || (empty($ugrw['gradeid']) && $prodrw['gid']=='1'))?'selected':'').'>'.ucwords($prodrw['grade']).'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2me brand" name="brand[]" required>
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select brid, brandname from brands order by brid asc";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prodrw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prodrw['brid'].'" '.(($ugrw['brid']==$prodrw['brid'] || (empty($ugrw['brid']) && $prodrw['brid']=='1'))?'selected':'').'>'.ucwords($prodrw['brandname']).'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2me size" name="size[]">
                                            <option value="">Select From List</option>
                                            
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control" name="price[]" value="<?php echo $ugrw['price']; ?>">
                                    </td>
                                    <td class="availqty">
                                        
                                    </td>
                                    <td class="pendingqty">
                                        
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" min="0" step="0.001" class="form-control min-width:7rem" name="qty[]" value="" style="min-width:7rem" required>
                                            <div class="input-group-append">
                                                <select name="qtyunit[]" class="form-control qtyunit1" required>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control itemremarks" name="itemremarks[]" value="<?php echo $ugrw['itemremarks']; ?>">
                                    </td>
                                    <td>
                                        <button type="button" class="btn action-btn btn-danger delitem"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                <tr class="clonable-row d-none">
                                    <td></td>
                                    <td>
                                        <select class="form-control selectc product" name="item[]" required disabled>
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select prid,productname from products order by productname ASC";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prorw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prorw['prid'].'">'.$prorw['productname'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control selectc grade" name="grade[]" required disabled>
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select gid,grade from grade order by gid asc";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prodrw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prodrw['gid'].'" '.(($ugrw['gradeid']==$prodrw['gid'] || (empty($ugrw['gradeid']) && $prodrw['gid']=='1'))?'selected':'').'>'.ucwords($prodrw['grade']).'</option>';
                                                   // echo '<option value="'.$prodrw['gid'].'">'.$prodrw['grade'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control selectc brand" name="brand[]" required disabled>
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select brid, brandname from brands order by brid asc";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prodrw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prodrw['brid'].'" '.(($ugrw['brid']==$prodrw['brid'] || (empty($ugrw['brid']) && $prodrw['brid']=='1'))?'selected':'').'>'.ucwords($prodrw['brandname']).'</option>';
                                                    //echo '<option value="'.$prodrw['brid'].'">'.$prodrw['brandname'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control selectc size" name="size[]" required disabled>
                                            <option value="">Select From List</option>
                                            
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control" name="price[]" value="" disabled>
                                    </td>
                                    <td class="availqty">
                                        
                                    </td>
                                    <td class="pendingqty">
                                        
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <input type="number" min="0" step="0.001" class="form-control  min-width:7rem" name="qty[]" value=""  style="min-width:7rem" required disabled>
                                            <div class="input-group-append">
                                                <select name="qtyunit[]" class="form-control qtyunit1" required disabled>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control itemremarks" name="itemremarks[]" value="<?php echo $ugrw['itemremarks']; ?>">
                                    </td>
                                    <td>
                                        <button type="button" class="btn action-btn btn-danger delitem"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="btns text-right mt-4">
                        <button type="submit" name="submt_btn" value="1" class="btn btn-basic"><i class="fa fa-check"></i> 
                        <?php echo ($_GET['slid']!=""?"Update":"Add")?> Sale Order</button>
                    </div>
                </form>
               
            </div>
        </div>
    </div>
    <?php }?>
</div>


<script type="text/javascript">
$(document).bind('keypress', function(event) {
    if( event.which === 65 && event.shiftKey ) {
        //alert('you pressed SHIFT+A');
        $('.additem').trigger('click');
    }
});
// document.onkeydown = function(e) {
    
//     switch(e.which) {
//       /** case 37: //left
//         break;

//         case 38: //up
//         break;

//         case 39: //right
//         break;*/

//         case 40: //down
//             console.log('down down down');
//             $('.additem').trigger('click');
//         break;

//         default: return; // exit this handler for other keys
//     }
//     e.preventDefault(); // prevent the default action (scroll / move caret)
// };
    //KeyboardJS.on('down', function() { console.log('down'); });
    $(document).ready(function(){
        $('.select2-container').css('width', '100%');
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
               
                let html ='<option value="" disabled selected>Select Contact Person</option>';
                for(let x = 0; x<di.length;x++){
                    html+='<option value="'+di[x].cid+'"';
                    if(di[x].prime=='1'){
                        html += ' selected ';
                    }
                    html+='>'+di[x].cperson+'</option>';
                }
                $('#contactPerson').html(html);
                 $('#contactPerson').trigger('change');
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
    // $(document).on('change', '.product', function(){
    //     let pid = $(this).val();
    //     let that = $(this);
    //     $.ajax({  
    //         type:'post',
    //         url:"Ajax.php",
    //         data:{prid:pid,doAction:'prodsize'},
    //         success:function(data){
                
    //             let di2 = JSON.parse(data);
    //             console.log(di2);
    //             console.log(di2.length);
    //             let html = '<option value="">Select Size</option>';

    //             for(let y=0;y<di2.length;y++){
    //                 html +='<option value="'+di2[y].sid+'">'+di2[y].size+'</option>';
    //             }
    //             console.log(html);
    //             that.closest('tr').find('.size').html(html).trigger('change');
    //         }
    //     });
    // })
    // $('#product').change(function(){
    //     let pid = $('#product').val();
    //     //console.log(pid);
    //     $.ajax({  
    //         type:'post',
    //         url:"Ajax.php",
    //         data:{prid:pid,doAction:'prodsize'},
    //         success:function(data){
                
    //             let di2 = JSON.parse(data);
    //             console.log(di2);
    //             console.log(di2.length);
    //             let html = '<option value="">Select Size</option>';

    //             for(let y=0;y<di2.length;y++){
    //                 html +='<option value="'+di2[y].sid+'">'+di2[y].size+'</option>';
    //             }
    //             console.log(html);
    //             $('#size').html(html);
    //         }
    //     });
    // });

    $(document).on('change', '.size', function(){
        let sid = parseInt($(this).val());
        console.log(sid);
        let that = $(this);
        $.ajax({
            type:"post",
            url:"Ajax.php",
            data:{sizeid:sid, doAction:'sizeqtyavailableandpending'},
            success:function(data){
                //console.log(data);
                let di3 = JSON.parse(data);
                console.log(di3[0]);
                console.log(di3[0].bundleweight);
                
                that.closest('tr').find('.availqty').html(di3[0].currentstock);
                that.closest('tr').find('.pendingqty').text(di3[0].pending);
                let options = '';
                if(di3[0].bundleweight !='' && di3[0].bundleweight !='0' && di3[0].bundleweight!= undefined){
                    options +='<option value="">Unit</option>';
                    options +='<option value="1" selected>Tons</option>';
                    options +='<option value="3">Quintal</option>';
                    options +='<option value="4">Bundle</option>';
                    // options +='<option value="5">Feet</option>';
                    // options +='<option value="6">Meter</option>';
                }else{
                    options +='<option value="">Unit</option>';
                    options +='<option value="1" selected>Tons</option>';
                    options +='<option value="2">Pcs</option>';
                    options +='<option value="3">Quintal</option>';
                    options +='<option value="5">Feet</option>';
                    options +='<option value="6">Meter</option>';
                }
                that.closest('tr').find('.qtyunit1').html(options);
            }
        });
    });
    
    $('.additem').click(function(){
        let clone = $('.clonable-row').clone();
        clone.removeClass('d-none clonable-row');
        clone.find('td').first().addClass('order');
        clone.find('.form-control').each(function(){
            $(this).prop('disabled', false);
            if($(this).hasClass('selectc')){
                $(this).select2();
                $(this).siblings('.select2-container').css('width', '100%');
            }
        })
        $('.itemtable').children('tbody').prepend(clone);
        $('td.order').text(function (i) {
            return i + 1;
        });
    });

    $(document).on('click','.delitem', function(){
        let confirm = window.confirm('Are you sure to delete this item?');
        if(confirm){
            if($('table tr').length>1) {
                var rowCount =$('table tr').length;
                console.log(rowCount);
                $(this).closest('tr').remove();
                $('td.order').text(function (i) {
                    return i + 1;
                });
            }
        }
    });

    $(document).on('change','.product', function(){
        let productId = parseInt($(this).val());
        let brandId = parseInt($(this).closest('tr').find('.brand').val());
        let gradeId = parseInt($(this).closest('tr').find('.grade').val());
        let sizes = getsizebyPrdidBridGrid(productId, brandId, gradeId, $(this));
        //$(this).closest('tr').find('.size').val(sizes);
    })
    $(document).on('change','.brand', function(){
        let productId = parseInt($(this).closest('tr').find('.product').val());
        let brandId = parseInt($(this).val());
        let gradeId = parseInt($(this).closest('tr').find('.grade').val());
        let sizes = getsizebyPrdidBridGrid(productId, brandId, gradeId, $(this));
        //$(this).closest('tr').find('.size').val(sizes);
    })

    $(document).on('change','.grade', function(){
        let productId =parseInt($(this).closest('tr').find('.product').val());
        let brandId = parseInt($(this).closest('tr').find('.brand').val());
        let gradeId = parseInt($(this).val());
        let sizes = getsizebyPrdidBridGrid(productId, brandId, gradeId, $(this));
        //$(this).closest('tr').find('.size').val(sizes);

    })

    function getsizebyPrdidBridGrid(prdid, brid, grid, elsm){
        let option = '<option value="">Select Size</option>'
        if((prdid != NaN || prdid != 'Nan') && (brid != NaN || brid != 'Nan') && (grid != NaN || grid != 'Nan')){
            //console.log(prdid+'-'+brid+'-'+grid);
            //let that = $(this);
            $.ajax({
            type:"post",
            url:"Ajax.php",
            data:{prdid:prdid,brid:brid,grid:grid,doAction:'sizeaspercondition'},
            success:function(data){
                console.log(data);
                let di3 = JSON.parse(data);
                for(let r=0; r<di3.length;r++){
                    option +="<option value='"+di3[r].sid+"'>"+di3[r].size+"</option>";
                    //console.log('html3:'+di3[r]);
                }
                console.log('html3:'+di3);
                //that.closest('tr').find('size').html(html3);
                console.log(option);
                console.log(elsm.closest('tr').find('.size').html(option));
                elsm.closest('tr').find('.size').html(option);
                //elsm.html(html3);
                // // console.log(di3.stdlength);
                // $('#length').val(di3.stdlength);
                // $('#lengthUnit').val(di3.lengthtype);
            }
        });
        }else{
            elsm.closest('tr').find('.size').html(option);
            elsm.closest('tr').find('.size').trigger('change');
        }
    }
</script>
