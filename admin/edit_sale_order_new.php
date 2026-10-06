<?php
$perm=check_permission("A","OF");
$sid=mysqli_real_escape_string($conn,$_GET['slid']);

if(isset($_GET['sosize'])){
    $sosize=mysqli_real_escape_string($conn,$_GET['sosize']);
    $delsize = "delete from saleordersizes where sosaleid='$sid' and sosize='$sosize'";
    mysqli_query($conn,$delsize);
    if(mysqli_affected_rows($conn)>0){
        echo '<script>window.location.href="main.php?paction=edit_sale_order&slid='.$sid.'"</script>';
    }
}

if($_POST['doAction']=='updateso'){ 
        date_default_timezone_set('Asia/Kolkata');
        $UpdatedOn = $createdon = date('Y-m-d H:i:s', time());
        $modifiedBy = $createdby = $_SESSION["user_id"];
        $remarks = mysqli_real_escape_string($conn,$_POST['remarks']);
        $slidmain=$_POST['slidmain'];
        $sosize=$_POST['sosize'];
        $item=$_POST['item'];
        $grade=$_POST['grade'];
        $brand=$_POST['brand'];
        $size=$_POST['size'];
        $price=$_POST['price'];
        $qty=$_POST['qty'];
        $qtyunit=$_POST['qtyunit'];
        $itemremarks = $_POST['itemremarks'];
        $er=0;
        // $upsaleorder = "update saleorder set remarks='".htmlentities($remarks)."' where slid='$slidmain'";
        $upsaleorder = "UPDATE saleorder SET remarks='".htmlentities($remarks)."', modifiedon='$UpdatedOn', modifiedby='$modifiedBy' WHERE slid='$slidmain'";

        //echo $upsaleorder.'<br>';
        mysqli_query($conn,$upsaleorder);
        for($xx=0;$xx<count($item);$xx++){
            $sizesql = "select mtweight,ftweight,stdlength,lengthtype,bundleweight from sizes where sizes.sid = $size[$xx]";
                $sizeqq = mysqli_query($conn,$sizesql);
                $sizeinfo = mysqli_fetch_assoc($sizeqq);
                //weight conversion
                if($qtyunit[$xx]==3){//quintals
                    //qunital to tons conversion
                    $weightintons=quintaltotons($qty[$xx]);
                    //weigth and pcs conversion  returns array[pcs,weight]
                $res = wtpcconvert($sizeinfo['stdlength'],$sizeinfo['lengthtype'],$sizeinfo['mtweight'],$sizeinfo['ftweight'],$weightintons,'1',$sizeinfo['bundleweight']);
                }else{
                    //weigth and pcs conversion  returns array[pcs,weight]
                    $res = wtpcconvert($sizeinfo['stdlength'],$sizeinfo['lengthtype'],$sizeinfo['mtweight'],$sizeinfo['ftweight'],$qty[$xx],$qtyunit[$xx],$sizeinfo['bundleweight']);
                }
                //var_Dump($res);
               
            if(empty($sosize[$xx])){
               // echo "insert<br>";
               if($res[1]!=0 && $res[0]!=0){
                    $xsa = "insert into saleordersizes set
                    sosaleid = '$slidmain',
                    soprid='$item[$xx]',
                    sogradeid = '$grade[$xx]',
                    sobrandid='$brand[$xx]',
                    sosizeid='$size[$xx]',
                    qty = '$qty[$xx]',
                    qtytype='$qtyunit[$xx]',
                    weightintons='$res[1]',
                    pcs = '$res[0]',
                    itemremarks='".htmlentities($itemremarks[$xx])."',
                    soprice='$price[$xx]',
                    createdon='$createdon',
                    createdby='$createdby'";
                    //echo $xsa.'<br><br><br>';
                    $inqq=mysqli_query($conn,$xsa);
                }else{
                    $er = $er+1;
                }
                
            }elseif(!empty($sosize[$xx])){
                if($res[1]!=0 && $res[0]!=0){
                    $xsa = "update saleordersizes set
                    soprid='$item[$xx]',
                    sogradeid = '$grade[$xx]',
                    sobrandid='$brand[$xx]',
                    sosizeid='$size[$xx]',
                    qty = '$qty[$xx]',
                    qtytype='$qtyunit[$xx]',
                    weightintons='$res[1]',
                    pcs = '$res[0]',
                    itemremarks='".htmlentities($itemremarks[$xx])."',
                    soprice='$price[$xx]',
                    modifiedon='$UpdatedOn',
                    modifiedby='$modifiedBy'
                    where sosize='$sosize[$xx]'";
                    //echo $xsa.'<br><br><br>';
                $inqq=mysqli_query($conn,$xsa);
                }else{
                    $er = $er+1;
                }
            }

        }
        if($er>0){
            echo '<script>window.location.href="main.php?paction=sale_order_view&msg=Order Updated successfully But Some Sized were not Added/Updates due to Short order qty."</script>';    
        }else{
            echo '<script>window.location.href="main.php?paction=sale_order_view&msg=Order Updated successfully."</script>';
        }
        
}

    $cxsql = "SELECT saleorder.cid,saleorder.remarks,contacts.cid as contid, contacts.cperson,contacts.countrycode, contacts.contact, contacts.designation,saleorder.slid,
    contacts.email FROM saleorder INNER JOIN contacts ON contacts.cid = saleorder.cperson where saleorder.slid =$sid";
    $cxqq = mysqli_query($conn,$cxsql);
    $crx = mysqli_fetch_assoc($cxqq);

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
                    <input type="hidden" name="slidmain" value="<?php echo $crx['slid']; ?>">
                    <input type="hidden" name="slid" value="<?php echo $crx['slid']; ?>">
                    <h6 class="bold text-uppercase mb-3">Customer Details</h6>
                    <div class="row">
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="customer">Customer Name</label>
                                <?php if(isset($_GET['slid'])){ ?>
                                    <input type ="hidden" name="customer" value="<?php echo $crx['cid']; ?>">
                                <?php } ?>
                                <select name="customer" class="form-control select2me" id="customer" disabled>
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
                                <select name="contactPerson" class="form-control" id="contactPerson" disabled required>
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
                                <!-- <div class="input-group-prepend">
                                    <select name="countrycode" class="form-control" readonly>
                                        <option value="">Country Code</option>
                                        <?php
                                        // $ctsql = "select id,phonecode from countries";
                                        // $ctqq = mysqli_query($conn,$ctsql);
                                        // while($ctrw = mysqli_fetch_assoc($ctqq)){
                                        //     echo '<option value="'.$ctrw['id'].'"'.($ctrw['id']==$crx['countrycode']?'selected':'').'>+'.$ctrw[phonecode].'</option>';
                                        // }
                                        ?>
                                    </select>
                                </div> -->
                                <input type="hidden" name="countrycode"  value="101">
                                <input type="number" min="0" name="contactNumber" class="form-control" id="contactNumber" value="<?php echo $crx['contact'];?>" readonly>
                            </div>
                            
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="email">Remarks</label>
                                <input type="text" name="remarks" class="form-control" id="remarks" value="<?php echo htmlspecialchars_decode($crx['remarks']);?>" >
                            </div>
                        </div>

                    </div>
                    <h6 class="bold text-uppercase mb-3 mt-4">Add Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-med itemtable">
                            <thead>
                                <?php
                                // $qa = "SELECT saleorderproducts.sopid, saleorderproducts.slid,saleorderproducts.gradeid, saleorderproducts.price, products.productname, grade.grade,saleorderproducts.prodid,(select count(*) from saleordersizes where sosaleid=saleorderproducts.slid and soprid=saleorderproducts.prodid) as scnt
                                // FROM saleorderproducts INNER JOIN products ON products.prid = saleorderproducts.prodid INNER JOIN grade ON grade.gid = saleorderproducts.gradeid where slid=$_GET[slid]";
                                $qa = "select * from saleordersizes where sosaleid=$_GET[slid]";
                                $qaq = mysqli_query($conn,$qa);
                                ?>    
                            <tr>
                                <th>S. No.</th>
                                <th>Item Name <span class="text-danger">*</span></th>
                                <th>Grade <span class="text-danger">*</span></th>
                                <th>Brand <span class="text-danger">*</span></th>
                                <th style="width: 11rem">Size <span class="text-danger">*</span></th>
                                <th>Rate/Ton </th>
                                <th>Avl Qty</th>
                                <th>Booked</th>
                                <th>Qty<span class="text-danger">*</span></th>
                                <th> Item Remarks</th>
                                <th><button type="button" class="btn action-btn btn-warning additem"><i class="fa fa-plus"></i></button></th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php 
                                    $as = 0;
                                while($qrw=mysqli_fetch_assoc($qaq)){
                                    ++$as;
                                    $sizeqtysql = "select currentstock,sizes.bundleweight,(select (sum(weightintons)-sum(dispatched)) from saleordersizes where sosizeid=sizes.sid) as pending from sizes where sid=$qrw[sosizeid]";
                                    $sizeqtyqq=mysqli_query($conn,$sizeqtysql);
                                    $sizeqtyrw=mysqli_fetch_all($sizeqtyqq,MYSQLI_ASSOC);
                                    ?>
                                <tr>
                                    <td class="order"><?php echo $as; ?></td>
                                    <td>
                                        <select class="form-control select2me product" name="item[]" required>
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select prid,productname from products order by productname ASC";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prorw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prorw['prid'].'" '.($qrw['soprid']==$prorw['prid']?'selected':'').'>'.$prorw['productname'].'</option>';
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
                                                    echo '<option value="'.$prodrw['gid'].'" '.(($qrw['sogradeid']==$prodrw['gid'] || (empty($qrw['sogradeid']) && $prodrw['gid']=='1'))?'selected':'').'>'.ucwords($prodrw['grade']).'</option>';
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
                                                    echo '<option value="'.$prodrw['brid'].'" '.(($qrw['sobrandid']==$prodrw['brid'] || (empty($qrw['sobrandid']) && $prodrw['brid']=='1'))?'selected':'').'>'.ucwords($prodrw['brandname']).'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2me size" name="size[]">
                                            <option value="">Select From List</option>
                                            <?php
                                            $sizesql1 = "select sid,size from sizes where grade='$qrw[sogradeid]' and brand='$qrw[sobrandid]' and prid='$qrw[soprid]'"; 
                                            $sizeqq1 = mysqli_query($conn,$sizesql1);
                                            while($sizerw1 = mysqli_fetch_assoc($sizeqq1)){
                                                echo '<option value="'.$sizerw1['sid'].'"'.($sizerw1['sid']==$qrw['sosizeid']?' selected':'').'>'.$sizerw1['size'].'</option>';
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control" name="price[]"  value="<?php echo $qrw['soprice']; ?>">
                                        <td class="availqty">
                                        <?php echo $sizeqtyrw[0]['currentstock']; ?>
                                    </td>
                                    <td class="pendingqty">
                                        <?php echo $sizeqtyrw[0]['pending']; ?>
                                    </td>
                                    <td>
                                        <input type="hidden" name="sosize[]" value="<?php echo $qrw['sosize']; ?>">
                                        <div class="input-group">    
                                            <input type="number" min="0" class="form-control " style="width:5rem" name="qty[]" step="0.001" value="<?php echo $qrw['qty']; ?>" required>
                                            <div class="input-group-append">
                                                <select name="qtyunit[]" class="form-control qtyunit1" required>
                                                    <option value="">Unit</option>
                                                    <option value="1"<?php echo ($qrw['qtytype']=='1'?' selected':''); ?>>Tons</option>
                                                   <?php  if($sizeqtyrw[0]['bundleweight']=='0' || $sizeqtyrw[0]['bundleweight']==''){ ?>
                                                        <option value="2"<?php echo ($qrw['qtytype']=='2'?' selected':''); ?>>Pcs</option>
                                                   <?php }else{ ?>
                                                        <option value="4"<?php echo ($qrw['qtytype']=='4'?' selected':''); ?>>Bundle</option>
                                                    <?php } ?>
                                                    <option value="3"<?php echo ($qrw['qtytype']=='3'?' selected':''); ?>>Quintal</option>
                                                    <option value="5"<?php echo ($qrw['qtytype']=='5'?' selected':''); ?>>Feet</option>
                                                    <option value="6"<?php echo ($qrw['qtytype']=='6'?' selected':''); ?>>Meter</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control itemremarks" name="itemremarks[]" value="<?php echo htmlspecialchars_decode($qrw['itemremarks']); ?>">
                                    </td>
                                    <td>
                                        <?php #var_dump($qrw); ?>
                                        <a href="main.php?paction=edit_sale_order&slid=<?php echo $qrw['sosaleid']; ?>&sosize=<?php echo $qrw['sosize']; ?>" class="btn action-btn btn-danger delitem"><i class="fa fa-trash"></i></a>
                                        <!-- <button type="button" class="btn action-btn btn-danger delitem"><i class="fa fa-trash"></i></button> -->
                                    </td>
                                </tr>
                                <?php } ?>
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
                                    <input type="hidden" name="sosize[]" value="">
                                        <div class="input-group">
                                            <input type="number" min="0" class="form-control " style="width:2rem" name="qty[]" step="0.001" value="" required disabled>
                                            
                                            <div class="input-group-append">
                                                <select name="qtyunit[]" class="form-control qtyunit1" required disabled>
                                                    <option value="">Unit</option>
                                                    <option value="1">Tons</option>
                                                    <option value="2">Pcs</option>
                                                    <option value="3">Quintal</option>
                                                    <option value="4">Bundle</option>
                                                    <option value="5">Feet</option>
                                                    <option value="6">Meter</option>
                                                </select>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control itemremarks" name="itemremarks[]" value="">
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
//     document.onkeydown = function(e) {
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
$(document).bind('keypress', function(event) {
    if( event.which === 65 && event.shiftKey ) {
        //alert('you pressed SHIFT+A');
        $('.additem').trigger('click');
    }
});
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
                    html+='<option value="'+di[x].cid+'">'+di[x].cperson+'</option>';
                }
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
        let sid = $(this).val();
        let that = $(this);
        if(sid != ''){
            sid = parseInt(sid);
            console.log(sid);
            $.ajax({
                type:"post",
                url:"Ajax.php",
                data:{sizeid:sid, doAction:'sizeqtyavailableandpending'},
                success:function(data){
                    let di3 = JSON.parse(data);
                    console.log(data);
                    //console.log(that.closest('tr').find('.availqty').html());
                    that.closest('tr').find('.availqty').text(di3[0].currentstock);
                    that.closest('tr').find('.pendingqty').text(di3[0].pending);
                    let options = '';
                    if(di3[0].bundleweight !='' && di3[0].bundleweight !='0' && di3[0].bundleweight!= undefined){
                        options +='<option value="">Unit</option>';
                        options +='<option value="1" selected>Tons</option>';
                        options +='<option value="3">Quintal</option>';
                        options +='<option value="4">Bundle</option>';
                        // options +='<option value="5">Feet</option>';
                        // options +='<option value="6">Meter</option>';
                    }
                    // else{
                    //     options +='<option value="">Unit</option>';
                    //     options +='<option value="1" selected>Tons</option>';
                    //     options +='<option value="2">Pcs</option>';
                    //     options +='<option value="3">Quintal</option>';
                    //     options +='<option value="5">Feet</option>';
                    //     options +='<option value="6">Meter</option>';
                    // }
                    // that.closest('tr').find('.qtyunit1').html(options);
                }
            });
        }else{
            console.log('size change else part')
            that.closest('tr').find('.availqty').text('');
            that.closest('tr').find('.pendingqty').text('');
            // if(di3[0].bundleweight !='' && di3[0].bundleweight !='0' && di3[0].bundleweight!= undefined){
            //     options +='<option value="">Unit</option>';
            //     options +='<option value="1" selected>Tons</option>';
            //     options +='<option value="3">Quintal</option>';
            //     options +='<option value="4">Bundle</option>';
            //     // options +='<option value="5">Feet</option>';
            //     // options +='<option value="6">Meter</option>';
            // }else{
            //     options +='<option value="">Unit</option>';
            //     options +='<option value="1" selected>Tons</option>';
            //     options +='<option value="2">Pcs</option>';
            //     options +='<option value="3">Quintal</option>';
            //     options +='<option value="5">Feet</option>';
            //     options +='<option value="6">Meter</option>';
            // }
            that.closest('tr').find('.qtyunit1').html(options);
        }
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
    // $(document).on('click','.delitem', function(){
    //     let confirm = window.confirm('Are you sure to delete this item?');
    //     if(confirm){
    //         $(this).closest('tr').remove()
    //     }
    // });

    $(document).on('change','.product', function(){
        let productId = $(this).val();
        let brandId = $(this).closest('tr').find('.brand').val();
        let gradeId = $(this).closest('tr').find('.grade').val();
        let sizes = getsizebyPrdidBridGrid(productId, brandId, gradeId, $(this));
        //$(this).closest('tr').find('.size').val(sizes);
    })
    $(document).on('change','.brand', function(){
        let productId = $(this).closest('tr').find('.product').val();
        let brandId = $(this).val();
        let gradeId = $(this).closest('tr').find('.grade').val();
        let sizes = getsizebyPrdidBridGrid(productId, brandId, gradeId, $(this));
        //$(this).closest('tr').find('.size').val(sizes);
    })

    $(document).on('change','.grade', function(){
        let productId = $(this).closest('tr').find('.product').val();
        let brandId = $(this).closest('tr').find('.brand').val();
        let gradeId = $(this).val();
        let sizes = getsizebyPrdidBridGrid(productId, brandId, gradeId, $(this));
        //$(this).closest('tr').find('.size').val(sizes);

    })

    function getsizebyPrdidBridGrid(prdid, brid, grid, elsm){
        let option = '<option value="">Select Size</option>'
        if((prdid != '' && prdid != undefined) && (brid != '' && brid != undefined) && (grid != '' && grid != undefined)){
           // console.log(prdid+'-'+brid+'-'+grid);
            //let that = $(this);
            $.ajax({
            type:"post",
            url:"Ajax.php",
            data:{prdid:prdid,brid:brid,grid:grid,doAction:'sizeaspercondition'},
            success:function(data){
                let di3 = JSON.parse(data);
                for(let r=0; r<di3.length;r++){
                    option +="<option value='"+di3[r].sid+"'>"+di3[r].size+"</option>";
                    //console.log('html3:'+di3[r]);
                }
               // console.log('html3:'+di3);
                //that.closest('tr').find('size').html(html3);
                // console.log(option);
                elsm.closest('tr').find('.size').html(option).trigger('change');
                //elsm.html(html3);
                // // console.log(di3.stdlength);
                // $('#length').val(di3.stdlength);
                // $('#lengthUnit').val(di3.lengthtype);
            }
        });
        }else{
            console.log('pgb change else part')
            elsm.closest('tr').find('.size').html(option).trigger('change');
        }
    }
</script>
