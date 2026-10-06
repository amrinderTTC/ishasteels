<?php

$perm=check_permission("A");
$sid=mysqli_real_escape_string($conn,$_GET['slid']);

if($_GET['msg']){
    $msg=$_GET['msg'];
}

if($_GET['errmsg']){
    $errmsg=$_GET['errmsg'];
}

if(isset($_GET['slid']) && !empty($_GET['slid']) && is_numeric($_GET['slid'])){

    if($_POST['doAction']=='updateso'){
        $soid=mysqli_real_escape_string($conn,$_POST['soid']);

        //total quantity ordered
        $orderqty = mysqli_real_escape_string($conn,$_POST['orderQty']);
        $orderremarks = mysqli_real_escape_string($conn,$_POST['orderremarks']);
        $updatedeal = "update dealorder set orderqty='$orderqty',orderremarks='$orderremarks' where slid='$soid'";
        //echo $updatedeal.'<br><br><br>';
        mysqli_query($conn,$updatedeal);
        //deal item update
        $ditem = $_POST['item']; // itemid
        $dgrade = $_POST['grade']; // grade
        $ditemid=$_POST['ditemid']; //Item Id
        $dprice = $_POST['price']; //Item Price
        $dremarks = $_POST['remarks']; // Remarks
        if(count($dprice)>0){
            for($y=0;$y<count($dprice);$y++){
                if(empty($ditemid[$y])){// if ditemid is null then insert
                    $dealitem="insert into dealitems set
                    dealid='$soid',
                    itemid='$ditem[$y]',
                    gradeid='$dgrade[$y]',
                    price='$dprice[$y]',
                    remarks='$dremarks[$y]'";
                    mysqli_query($conn,$dealitem);
                }else{// if ditemid is not null then update
                    $updatedealitem = "update dealitems set price='$dprice[$y]',remarks='$dremarks[$y]' where ditemid='$ditemid[$y]'";
                    //echo $updatedealitem.'<br><br><br>';
                    mysqli_query($conn,$updatedealitem);
                }
            }
             echo '<script>window.location.href="main.php?paction=sale_deals_view&msg=Deal Updated Successfully"</script>';
        }else{
            echo '<script>window.location.href="main.php?paction=sale_deals_view&errmsg=No Item In Deal Please Insert Item."</script>';
        }
       
       
    }
    
    $dealsql="SELECT
    dealorder.slid,
    dealorder.cid,
    dealorder.cperson,
    dealorder.`status`,
    dealorder.orderqty,
    dealorder.dispatchedqty,
    dealorder.orderremarks,
    contacts.cid,
    contacts.cust_id,
    contacts.designation,
    contacts.email,
    contacts.countrycode,
    contacts.contact,
    contacts.prime
    FROM dealorder INNER JOIN contacts ON contacts.cid = dealorder.cperson
    where slid='$_GET[slid]'";
    //echo $dealsql;
    $dealqq=mysqli_query($conn,$dealsql);
    $crx=mysqli_fetch_assoc($dealqq);
    //var_dump($crx);
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
                                <select name="customer" class="form-control select2me" id="customer" disabled <?php #echo(isset($_GET['slid'])?'disabled':'required'); ?>>
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
                                <select name="contactPerson" class="form-control" id="contactPerson" required disabled>
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
                                <?php #if(isset($_GET['slid'])){ ?>
                                    <input type="number" min="0" step="0.001" name="orderQty" class="form-control" id="orderQty" value="<?php echo $crx['orderqty'];?>" required>
                                <?php #} else{ ?>
                                    <!--<input type="number" min="0" step="0.001" name="orderQty" class="form-control" id="orderQty" value="" >-->
                                <?php #} ?>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="orderremarks">Deal Remarks</label>
                                <input type="text" name="orderremarks" class="form-control" id="orderremarks" value="<?php echo $crx['orderremarks']; ?>" >
                            </div>
                        </div>
                    </div>
                    
                    <h6 class="bold text-uppercase mb-3 mt-4">Add Items</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered table-med itemtable">
                            <thead>
                                <?php
                                $qa ="SELECT
                                dealitems.ditemid,
                                dealitems.itemid,
                                dealitems.gradeid,
                                dealitems.price,
                                dealitems.qtydispatched,
                                dealitems.remarks
                                FROM
                                dealitems
                                where dealid=$_GET[slid]";
                                $qaq = mysqli_query($conn,$qa);
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
                                <?php while($qrw = mysqli_fetch_assoc($qaq)){
                                    //var_dump($qrw);
                                    $qdisp = (!empty($qrw['qtydispatched'])?$qrw['qtydispatched']:'0'); // qty already dispatched
                                    ?>
                                <tr>
                                    <td>
                                        <select class="form-control select2me item" name="item[]" disabled>
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select prid,productname from products order by productname ASC";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prorw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prorw['prid'].'" '.($qrw['itemid']==$prorw['prid']?'selected':'').'>'.ucwords($prorw['productname']).'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>

                                    <td>
                                        <select class="form-control select2me grade" name="grade[]"  disabled>
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select gid,grade from grade order by grade asc";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prodrw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prodrw['gid'].'" '.($qrw['gradeid']==$prodrw['gid']?'selected':'').'>'.ucwords($prodrw['grade']).'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="hidden" name="ditemid[]" value="<?php echo $qrw['ditemid'];  ?>">
                                        <input type="hidden" name="qtydispatched" value="<?php echo $qrw['qtydispatched'];  ?>">
                                        <input type="number" min="0" class="form-control" name="price[]" value="<?php echo $qrw['price']; ?>">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="remarks[]" value="<?php echo $qrw['remarks']; ?>">
                                    </td>
                                    <td>
                                        <?php if($qrw['qtydispatched']=='0' ||empty($qrw['qtydispatched'])){ ?>
                                        <button type="button" data-itemid="<?php echo $qrw['ditemid'];  ?>" class="btn action-btn btn-danger delitem"><i class="fa fa-trash"></i></button>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                                <!-- Leave this row intact as it is -->
                                <tr class="clonable-row d-none">
                                    <td>
                                        <select class="form-control selectc item" name="item[]" disabled>
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
                                        <select class="form-control selectc grade" name="grade[]" disabled>
                                            <option value="">Select From List</option>
                                            <?php
                                                $prosql = "select gid,grade from grade order by grade asc";
                                                $proqq = mysqli_query($conn,$prosql);
                                                while($prodrw = mysqli_fetch_assoc($proqq)){
                                                    echo '<option value="'.$prodrw['gid'].'">'.$prodrw['grade'].'</option>';
                                                }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <?php echo $qrw['ditemid'];  ?>
                                        <input type="hidden" name="ditemid[]" value="<?php echo $qrw['ditemid'];  ?>">
                                        <input type="hidden" name="qtydispatched" value="<?php echo $qrw['qtydispatched'];  ?>">
                                        <input type="number" min="0" class="form-control price" name="price[]" value="" disabled>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="remarks[]" value="">
                                    </td>
                                    <td>
                                        <button type="button" data-itemid="<?php echo $qrw['ditemid'];  ?>" class="btn action-btn btn-danger delitem"><i class="fa fa-trash"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
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
    // $(document).on('click', '.delitem', function(){
    //     console.log($(this).data('itemid'));
    //     if($(this).data('itemid')!=undefined || $(this).data('itemid')!= ''){
    //         let that =$(this)
    //         let confirm = window.confirm('Are you sure to delete this item from deal?');
    //         if(confirm){
    //             that.closest('tr').remove()
    //         }else{
    //             return false
    //         }
    //     }else{
    //         $(this).closest('tr').remove()
    //     }
    // });
    
    $(document).on('click', '.delitem', function(){
        console.log($(this).data('itemid'));
        let dealitemid = $(this).data('itemid');
        if($(this).data('itemid')!=undefined || $(this).data('itemid')!= ''){
            let that =$(this)
            let confirm = window.confirm('Are you sure to delete this item from deal?');
            if(confirm){
                
                $.ajax({
                    type:'post',
                    url:"Ajax.php",
                    data:{dealitemid:dealitemid,doAction:"removedealitem"},
                    success:function(data){
                        console.log(data);
                        that.closest('tr').remove();
                        alert("Selected Item Deleted Successfully.");
                    },error: function (error) {
                        alert("Unable to Delete Selected Item.");
                    }
                });
            }else{
                return false
            }
        }else{
            $(this).closest('tr').remove()
        }
    });
</script>
