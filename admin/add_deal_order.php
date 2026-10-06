<?php

$perm=check_permission("A","OF");
$sid=mysqli_real_escape_string($conn,$_GET['slid']);

if($_POST['doAction'] == 'createso'){
    //var_Dump($_POST);
    $cid=mysqli_real_escape_string($conn,$_POST['customer']);
    $cperson= mysqli_real_escape_string($conn,$_POST['contactPerson']);
    $orderremarks= mysqli_real_escape_string($conn,$_POST['orderremarks']);
    $orderqty= mysqli_real_escape_string($conn,$_POST['orderQty']);
    if(sizeof($_POST['item'])>0){
        $isql="insert into dealorder set cid = '$cid',cperson = '$cperson',orderqty ='$orderqty',orderremarks='$orderremarks',
        createdon = '$createdon',createdby = '$createdby'";
        //echo $isql.'<br>';
        $inqq=mysqli_query($conn,$isql);
        $dealid=mysqli_insert_id($conn);
       // echo $dealid;
        if($dealid>0){
            $item = $_POST['item'];
            $grade = $_POST['grade'];
            $price = $_POST['price'];
            $qty = $_POST['qty'];
            $remarks = $_POST['remarks'];
            $qtyunit = $_POST['qtyunit'];

            for($x=0;$x<sizeof($item);$x++){
                $dealitem="insert into dealitems set
                dealid='$dealid',
                itemid='$item[$x]',
                gradeid='$grade[$x]',
                price='$price[$x]',
                remarks = '$remarks[$x]',
                qty='$qty[$x]',
                qtyunit='$qtyunit[$x]'";
                $dqq = mysqli_query($conn,$dealitem);
                //echo $dealitem.'<br>';
                
                // $drw = mysqli_insert_id($conn);
                // var_Dump($drw);
                // if($drw>0){
                //     echo '<script>window.location.href="main.php?paction=sale_deals_view&slid='.$slid.'&msg=Deal Created Successfully."</script>';     
                // }
            }
            echo '<script>window.location.href="main.php?paction=sale_deals_view&msg=Deal Created Successfully."</script>'; 
        }
    }else{
        //echo '<script>window.location.href="main.php?paction=sale_deals_view&errmsg=No Deal Created As No Product was selected."</script>';
    }
}else if($_POST['doAction']=='updateso'){
    $cid=mysqli_real_escape_string($conn,$_POST['customer']);
    $cperson=mysqli_real_escape_string($conn,$_POST['contactPerson']);
    $isql="update dealorder set cid='$cid',cperson='$cperson',
    modifiedon='$createdon',modifiedby='$createdby'";
    $inqq=mysqli_query($conn,$isql);
    $slid=mysqli_insert_id($conn);
    echo '<script>window.location.href="main.php?paction=add_deal_order&slid='.$sid.'&msg=Contact Person Updated Successfully."</script>';
}

// if(isset($_GET['sdid']) && !empty($_GET['sdid']) && is_numeric($_GET['slid'])){
//     $cxsql = "SELECT dealorder.cid,dealorder.orderqty,contacts.cid as contid, contacts.cperson,contacts.countrycode, contacts.contact, contacts.designation,dealorder.slid,
//     contacts.email FROM dealorder INNER JOIN contacts ON contacts.cid = dealorder.cperson where dealorder.slid =$sid";
//     $cxqq = mysqli_query($conn,$cxsql);
//     $crx = mysqli_fetch_assoc($cxqq);
// }

if($_GET['msg']){
    $msg=$_GET['msg'];
}

if($_GET['errmsg']){
    $errmsg=$_GET['errmsg'];
}

if(isset($_GET['slid']) && !empty($_GET['slid']) && is_numeric($_GET['slid'])){
    $dealsql="SELECT *,
    contacts.cid,
    contacts.cust_id,
    contacts.designation,
    contacts.email,
    contacts.countrycode,
    contacts.contact,
    contacts.prime,
    contacts.createdon,
    contacts.createdby
    FROM
    dealorder
    INNER JOIN contacts ON contacts.cid = dealorder.cperson
    where slid='$_GET[slid]'";
    echo $dealsql;
    $dealqq=mysqli_query($conn,$dealsql);
    $crx=mysqli_fetch_assoc($dealqq);
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
        <h1 class="page-heading ebold heading5"><?php echo($_GET['slid']?"Edit ":"Add "); #echo($typ); ?> Sale Deal</h1>
        <ul class="list-inline breadcrumb breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Manage Deal</li>
            <li class="breadcrumb-item"><?php echo($_GET['slid']?"Edit ":"Add "); echo($typ);?> Sale Deal</li>
        </ul>
    </div>
    
    <div class="page-content container-max">
        <div class="article">
            <div class="article-heading flex-heading">
                <h5 class="text-center"><?php echo($_GET['slid']?"Edit ":"Add "); ?> Deal Deal</h5>
                <a href="main.php?paction=sale_deals_view" class="btn btn-basic btn-sm">Deal Deals</a>
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
                                        echo '<option value="'.$crw['cust_id'].'"'.($crw['cust_id']==$crx['cust_id']?'selected':'').'>'.$crw['name'].'</option>';
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
                                        echo '<option value="'.$cprw['cid'].'"'.($cprw['cid']==$crx['cperson']?'selected':'').'>'.$cprw['cperson'].'</option>';
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
                                <label for="orderQty">Deal Qty (Tons)</label>
                                <?php if(isset($_GET['slid'])){ ?>
                                    <input type="number" min="0" step="0.001" name="orderQty" class="form-control" id="orderQty" value="<?php #echo $crx['orderqty'];?>" readonly>
                                <?php } else{ ?>
                                    <input type="number" min="0" step="0.001" name="orderQty" class="form-control" id="orderQty" value="" >
                                <?php } ?>
                            </div>
                        </div>

                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="orderremarks">Deal Remarks</label>
                                <input type="text" name="orderremarks" class="form-control" id="orderremarks" value="" >
                            </div>
                        </div>
                        
                    </div>
                    
                    <!--Amrinder MIshra Start-->
                    <?php 
                    // if(isset($_GET['slid']) && !empty($_GET['slid']) && is_numeric($_GET['slid'])){  
                    ?>
                    <h6 class="bold text-uppercase mb-3 mt-4">Add Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-med itemtable">
                            <thead>
                                <?php
                                if(isset($_GET['slid']) && !empty($_GET['slid']) && is_numeric($_GET['slid'])){ 
                                    $qa = "SELECT saleorderproducts.sopid, saleorderproducts.slid,saleorderproducts.gradeid, saleorderproducts.price, products.productname, grade.grade,saleorderproducts.prodid,(select count(*) from saleordersizes where sosaleid=saleorderproducts.slid and soprid=saleorderproducts.prodid) as scnt
                                    FROM saleorderproducts INNER JOIN products ON products.prid = saleorderproducts.prodid INNER JOIN grade ON grade.gid = saleorderproducts.gradeid where slid=$_GET[slid]";
                                    $qaq = mysqli_query($conn,$qa);
                                }
                                ?>
                                
                            <tr>
                                <th>Item Name <span class="text-danger">*</span></th>
                                <th>Grade <span class="text-danger">*</span></th>
                                <th>Rate/Ton <span class="text-danger">*</span></th>
                                <th>Remarks</th>
                                <th>
                                    <button type="button" class="btn action-btn btn-warning additemrow"><i class="fa fa-plus"></i></button>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <select class="form-control select2me item" name="item[]">
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select prid,productname from products order by productname ASC";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prorw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prorw['prid'].'" '.($ugrw['prodid']==$prorw['prid']?'selected':'').'>'.ucwords($prorw['productname']).'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-control select2me grade" name="grade[]">
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select gid,grade from grade order by grade asc";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prodrw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prodrw['gid'].'" '.(($ugrw['gradeid']==$prodrw['gid'] || $prodrw['gid']=='1')?'selected':'').'>'.ucwords($prodrw['grade']).'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                   
                                    <td>
                                        <input type="number" min="0" class="form-control" name="price[]" value="<?php echo $ugrw['price']; ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="remarks[]" value="">
                                    </td>
                    
                                    <td>
                                        <button type="button" data-itemid="" class="btn action-btn btn-danger delitem"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                                
                                <tr class="clonable-row d-none"><!-- Leave this row intact as it is -->
                                    <td>
                                        <select class="form-control selectc item" name="item[]" disabled>
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
                                        <select class="form-control selectc grade" name="grade[]" disabled>
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select gid,grade from grade order by grade asc";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prodrw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prodrw['gid'].'" '.($prodrw['gid']=='1'?'selected':'').'>'.$prodrw['grade'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>

                                    <td>
                                        <input type="number" min="0" class="form-control price" name="price[]" value="" disabled>
                                    </td>

                                    <td>
                                        <input type="text" class="form-control" name="remarks[]" value="">
                                    </td>
                                    <td>
                                        <button type="button" data-itemid="" class="btn action-btn btn-danger delitem"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    // } 
                    ?>
                    <!--Amrinder Mishra End-->
                    <?php if(isset($_GET['slid'])){ ?>
                        <input type="hidden" name="soid" value="<?php echo $crx['slid']; ?>">
                    <?php } ?>
                    <div class="btns text-right mt-4">
                        <button type="submit" name="submt_btn" value="1" class="btn btn-basic"><i class="fa fa-check"></i> 
                        <?php echo ($_GET['slid']!=""?"Update":"Add To")?> Deals</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php }?>
</div>


<script type="text/javascript">
    $(document).ready(function(){
        $('#customer').trigger('change'); 
    })

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
                        html +=' selected';
                    }
                    html +='>'+di[x].cperson+'</option>';
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
    $('.additemrow').click(function(){
        let trclone = $('.itemtable').find('.clonable-row').clone();
        trclone.removeClass('clonable-row d-none');
        trclone.find('.form-control').each(function(){
            $(this).prop('disabled', false).trigger('change');
            if($(this).hasClass('selectc')){
                $(this).select2();
                $(this).siblings('.select2-container').css('width', '100%');
            }
        });

        $('.itemtable').children('tbody').prepend(trclone);
        
    })
    $(document).on('click', '.delitem', function(){
        console.log($(this).data('itemid'));
        if($(this).data('itemid')!=undefined || $(this).data('itemid')!= ''){
            let that =$(this)
            let confirm = window.confirm('Are you sure to delete this item from deal?');
            if(confirm){
                that.closest('tr').remove()
            }else{
                return false
            }
        }else{
            $(this).closest('tr').remove()
        }
    });
</script>
