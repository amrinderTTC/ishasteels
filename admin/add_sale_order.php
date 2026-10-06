<?php
$perm=check_permission("A","OF");
$sid=mysqli_real_escape_string($conn,$_GET['slid']);

if($_POST['doAction'] == 'createso'){
    $cid=mysqli_real_escape_string($conn,$_POST['customer']);
    $cperson= mysqli_real_escape_string($conn,$_POST['contactPerson']);
    $orderqty= mysqli_real_escape_string($conn,$_POST['orderQty']);
    $isql="insert into saleorder set cid = '$cid',cperson = '$cperson',orderqty ='$orderqty',
    createdon = '$createdon',createdby = '$createdby'";
    $inqq=mysqli_query($conn,$isql);
    $slid=mysqli_insert_id($conn);
    if($slid>0){
        echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$slid.'&msg=Sale Order Created Successfully. Add Items Now."</script>';
    }
}else if($_POST['doAction']=='updateso'){
    $cid=mysqli_real_escape_string($conn,$_POST['customer']);
    $cperson=mysqli_real_escape_string($conn,$_POST['contactPerson']);
    $isql="update saleorder set cid='$cid',cperson='$cperson',
    modifiedon='$createdon',modifiedby='$createdby'";
    $inqq=mysqli_query($conn,$isql);
    $slid=mysqli_insert_id($conn);
    echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$sid.'&msg=Contact Person Updated Successfully."</script>';
}else if($_POST['doAction'] =='addproductprice'){
    $cid=mysqli_real_escape_string($conn,$_POST['cid']); // customer id
    $slid=mysqli_real_escape_string($conn,$_POST['slid']); // sale order id
    $prod=mysqli_real_escape_string($conn,$_POST['item']); // product id
    $grade = mysqli_real_escape_string($conn,$_POST['grade']); // grade id
    $price = mysqli_real_escape_string($conn,$_POST['price']); // price per tons
    $orderprod = "insert into saleorderproducts set slid = '$slid', prodid = '$prod', gradeid = '$grade',  price = '$price',createdon = '$createdon',createdby='$createdby'";
    mysqli_query($conn,$orderprod);
    //echo $orderprod;
    //var_Dump(mysqli_error($conn));
    if(mysqli_insert_id($conn)>0){
        echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$slid.'&msg=Product Added For Selected Order Successfully"</script>';
    }else{
        echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$slid.'&errmsg=Something Went Wrong"</script>';
    }

}else if($_POST['doAction'] =='editproductprice'){ // edit selected product sopid
    // var_Dump($_POST);
    $rowid=mysqli_real_escape_string($conn,$_POST['rowid']); // row id to update
    $cid=mysqli_real_escape_string($conn,$_POST['cid']); // customer id
    $slid=mysqli_real_escape_string($conn,$_POST['slid']); // sale order id
    $prod=mysqli_real_escape_string($conn,$_POST['item']); // product id
    $grade = mysqli_real_escape_string($conn,$_POST['grade']); // grade id
    $price = mysqli_real_escape_string($conn,$_POST['price']); // price per tons
    $orderprod = "update saleorderproducts set slid = '$slid', prodid = '$prod', gradeid='$grade', price ='$price',modifiedon='$createdon',modifiedby='$createdby' where sopid='$rowid'";

     $orderprodqq=mysqli_query($conn,$orderprod);
    echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$slid.'&msg=Item Updated Successfully"</script>';
}else if($_POST['doAction'] =='additem'){
    $slid=mysqli_real_escape_string($conn,$_POST['slid']);
    $prodid=mysqli_real_escape_string($conn,$_POST['product']);
    $size=mysqli_real_escape_string($conn,$_POST['size']);
    $gradeid=mysqli_real_escape_string($conn,$_POST['grade']);
    $brandid=mysqli_real_escape_string($conn,$_POST['brand']);
    $len=mysqli_real_escape_string($conn,$_POST['length']);
    $lenunit=mysqli_real_escape_string($conn,$_POST['lengthUnit']);
    $lentyp=mysqli_real_escape_string($conn,$_POST['lengthType']);
    $qty=mysqli_real_escape_string($conn,$_POST['qty']);
    $qtyunit=mysqli_real_escape_string($conn,$_POST['qtyUnit']);
    $price=mysqli_real_escape_string($conn,$_POST['price']);
    $gst=mysqli_real_escape_string($conn,$_POST['gst']);
    /**
     * Get size std weight mtwght ftwght
     */
        $wtsql = "select mtweight,ftweight from sizes where sid = $size";
        $wtqq = mysqli_query($conn,$wtsql);
        $wtrw = mysqli_fetch_assoc($wtqq);
        
    
    
    /**
     * res[0] = pcs;
     * res[1] = weight;
     */
    $res = wtpcconvert($len,$lenunit,$wtrw['mtweight'],$wtrw['ftweight'],$qty,$qtyunit);
    
    // $insitm = "insert into saleitems set slid = '$slid', prodid = '$prodid',size = '$size', gradeid = '$gradeid',brandid = '$brandid', 
    // length = '$len', lenunit = '$lenunit', lentyp = '$lentyp', qty = '$qty',qtyunit = '$qtyunit',weight='".$res[1]."',pcs='".$res[0]."', 
    // price = '$price', gst = '$gst',createdon='$createdon',createdby='$createdby'";
    // //echo $insitm;
    // $insqq=mysqli_query($conn,$insitm);
    // $sitm = mysqli_insert_id($conn);
    
    // if($sitm>0){
    //    echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$slid.'&msg=Item Added to Sales Order Successfully"</script>';
    // }
}else if($_POST['doAction']=='updateitem'){
    $slid=mysqli_real_escape_string($conn,$_POST['slid']);
    $slitemid=mysqli_real_escape_string($conn,$_POST['slitemid']);
    $prodid=mysqli_real_escape_string($conn,$_POST['product']);
    $size=mysqli_real_escape_string($conn,$_POST['size']);
    $gradeid=mysqli_real_escape_string($conn,$_POST['grade']);
    $brandid=mysqli_real_escape_string($conn,$_POST['brand']);
    $len=mysqli_real_escape_string($conn,$_POST['length']);
    $lenunit=mysqli_real_escape_string($conn,$_POST['lengthUnit']);
    $lentyp=mysqli_real_escape_string($conn,$_POST['lengthType']);
    $qty=mysqli_real_escape_string($conn,$_POST['qty']);
    $qtyunit=mysqli_real_escape_string($conn,$_POST['qtyUnit']);
    $price=mysqli_real_escape_string($conn,$_POST['price']);
    $gst=mysqli_real_escape_string($conn,$_POST['gst']);
    
    /**
     * Get size std weight mtwght ftwght
     */
    // $wtsql = "select mtweight,ftweight from sizes where sid = $size";
    // $wtqq = mysqli_query($conn,$wtsql);
    // $wtrw = mysqli_fetch_assoc($wtqq);
    // $res = wtpcconvert($len,$lenunit,$wtrw['mtweight'],$wtrw['ftweight'],$qty,$qtyunit);
    /**
     * Qty ordered 1: in tons 2: in pcs.
     * Weight 1: per meter 2:feets
     * Ordered length 1meter 2: feets
     * if qtyunit 1 put qty in weightordered
     * if qtyunit 2 calculate the weight in tons and save to weightordered
     */
    // $perpcwt = 0;
    // $totwtinkg = 0;
    // $totwtinton = 0;
    // if($qtyunit==2){ //order in pcs.
    //     if($lenunit=='1'){ //meters
    //         $perpcwt = $wtrw['mtweight']*$len;
    //     }else if($lenunit=='2'){ //feets
        
    //         $perpcwt = $wtrw['ftweight']*$len;
    //     }
    //     $totwtinkg =$perpcwt*$qty;
    //     $totwtinton = $totwtinkg/1000;
        
    // }else if($qtyunit==1){
    //     if($lenunit=='1'){ //meters
    //         $perpcwt = ($wtrw['mtweight']*$len)/1000;
    //     }else if($lenunit=='2'){ //feets
    //         $perpcwt = ($wtrw['ftweight']*$len)/1000;
    //     }
    //     $pcs = $qty/$perpcwt;
    //     $totwtinton = $qty;
    // }
    
    /**
     * res[0] = pcs;
     * res[1] = weight;
     */
   
    //var_Dump($res);
    //$upditm = "update saleitems set slid='$slid', prodid='$prodid', size='$size', gradeid='$gradeid',brandid='$brandid', length='$len', lenunit='$lenunit',lentyp='$lentyp', qty='".$qty."',weight='".$res[1]."',pcs='".$res[0]."', price='$price', gst='$gst' where slitemid='$slitemid'";
    //$updqq=mysqli_query($conn,$upditm);
    //$updres = mysqli_affected_rows($conn);
    //echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$slid.'&msg=Sales Order Item Updated Successfully."</script>';

}else if($_POST['todo']=='1'){ // size to sale order
//else if($_POST['doAction']=='addsizetoorder'){ // size to sale order
    
    $sosaleid=mysqli_real_escape_string($conn,$_POST['saleorderid']); // sale order id
    $soprid=mysqli_real_escape_string($conn,$_POST['prodid']); // product id
    $sogradeid=mysqli_real_escape_string($conn,$_POST['gradeid']); // grade id
    $soitemid=mysqli_real_escape_string($conn,$_POST['itemid']); // sale order product added row id
    $sobrandid = mysqli_real_escape_string($conn,$_POST['modalbrand']); // brand id
    $sosizeid = mysqli_real_escape_string($conn,$_POST['modalsize']); // size id
    $qty = mysqli_real_escape_string($conn,$_POST['qty']); // qty
    $qtytype = mysqli_real_escape_string($conn,$_POST['qtyunit']); // qty
    $qtysql = "select orderqty as totalqty,(select sum(weightintons) from saleordersizes where sosaleid=saleorder.slid)as orderedqty  from saleorder where slid=$sosaleid";
    $qtyqq = mysqli_query($conn,$qtysql);
    $qtyrw = mysqli_fetch_assoc($qtyqq);
    //echo $qtysql;
    $totalqty = $qtyrw['totalqty'];
    $orderedqty = $qtyrw['orderedqty'];
    $newqty = ($orderedqty+$qty);
    /**
     * If total size qty ordered is grater then the total order qty.
     * then dont insert new size order and redirect with error msg
     */
    if($newqty>$totalqty){ 
        echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$sosaleid.'&errmsg=Size Quantity You Are Trying to Add Exceeds Total Order Quantity"</script>';
        die();
    }else{
        //echo '<br><br>';
        $sizesql = "select mtweight,ftweight,stdlength,lengthtype from sizes where sizes.sid = $sosizeid";
        // echo $sizesql;
        $sizeqq = mysqli_query($conn,$sizesql);
        $sizeinfo = mysqli_fetch_assoc($sizeqq);
        //var_Dump($sizeinfo);
        if($qtytype==3){//quintals
            $weightintons=quintaltotons($qty);
           $res = wtpcconvert($sizeinfo['stdlength'],$sizeinfo['lengthtype'],$sizeinfo['mtweight'],$sizeinfo['ftweight'],$weightintons,'1');
        }else{
            $res = wtpcconvert($sizeinfo['stdlength'],$sizeinfo['lengthtype'],$sizeinfo['mtweight'],$sizeinfo['ftweight'],$qty,$qtytype);
        }

        $insertsize = "insert into saleordersizes set
        sosaleid='$sosaleid',
        soprid='$soprid',
        sogradeid='$sogradeid',
        soitemid='$soitemid',
        sobrandid='$sobrandid',
        sosizeid='$sosizeid',
        qty='$qty',
        qtytype='$qtytype',
        weightintons='$res[1]',
        pcs = '$res[0]',
        createdon='$createdon',
        createdby='$createdby'";
        //echo $insertsize;
        $insizeqq = mysqli_query($conn,$insertsize);
        $insertid = mysqli_insert_id($conn);
        if($insertid > 0 ){
            echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$sosaleid.'&msg=Size Added to Product Successully"</script>';
        }else{
            echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$sosaleid.'&errmsg=Something Went Wrong"</script>';
        }
    }
    
}else if($_POST['todo']=='2'){ // update size to sale order 

    if($_POST['addtosize']=='Add Size'){ // updates button is clicked
        // if update button is clicked.
        $rowidtoedit = mysqli_real_escape_string($conn,$_POST['sosize']); // row id to update
        $sosaleid=mysqli_real_escape_string($conn,$_POST['saleorderid']); // sale order id
        $soprid=mysqli_real_escape_string($conn,$_POST['prodid']); // product id
        $sogradeid=mysqli_real_escape_string($conn,$_POST['gradeid']); // grade id
        $soitemid=mysqli_real_escape_string($conn,$_POST['itemid']); // sale order product added row id
        $sobrandid = mysqli_real_escape_string($conn,$_POST['modalbrand']); // brand id
        $sosizeid = mysqli_real_escape_string($conn,$_POST['modalsize']); // size id
        $qty = mysqli_real_escape_string($conn,$_POST['qty']); // qty
        $qtytype = mysqli_real_escape_string($conn,$_POST['qtyunit']); // qty
        //get total weight ordered
        // $qtysql = "select orderqty as totalqty,(select sum(weightintons) from saleordersizes where sosaleid=saleorder.slid)as orderedqty  from saleorder where slid=$sosaleid";
        // echo $qtysql;
        //get basic size info
        $sizesql = "select mtweight,ftweight,stdlength,lengthtype from sizes where sizes.sid = $sosizeid";
        $sizeqq = mysqli_query($conn,$sizesql);
        $sizeinfo = mysqli_fetch_assoc($sizeqq);
        
        //weight conversion
        if($qtytype==3){//quintals
            //qunital to tons conversion
            $weightintons=quintaltotons($qty);
            //weigth and pcs conversion  returns array[pcs,weight]
           $res = wtpcconvert($sizeinfo['stdlength'],$sizeinfo['lengthtype'],$sizeinfo['mtweight'],$sizeinfo['ftweight'],$weightintons,'1');
        }else{
            //weigth and pcs conversion  returns array[pcs,weight]
            $res = wtpcconvert($sizeinfo['stdlength'],$sizeinfo['lengthtype'],$sizeinfo['mtweight'],$sizeinfo['ftweight'],$qty,$qtytype);
        }

        $insertsize = "update saleordersizes set
        sosaleid='$sosaleid',
        soprid='$soprid',
        sogradeid='$sogradeid',
        soitemid='$soitemid',
        sobrandid='$sobrandid',
        sosizeid='$sosizeid',
        qty='$qty',
        qtytype='$qtytype',
        weightintons='$res[1]',
        pcs = '$res[0]',
        modifiedon='$createdon',
        modifiedby='$createdby' where sosize=$rowidtoedit";
         $insizeqq = mysqli_query($conn,$insertsize);
         mysqli_affected_rows($conn);
         echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$sosaleid.'&msg=Size updated successfully."</script>';
    }else if($_POST['deletesize']=='Delete Size'){ // delete button is clicked 
        $rowidtoedit = mysqli_real_escape_string($conn,$_POST['sosize']); // row id to update
        $sosaleid=mysqli_real_escape_string($conn,$_POST['saleorderid']); // sale order id
        $delsize = "delete from saleordersizes where sosize=$rowidtoedit";
        //var_Dump($delsize);
        // var_Dump($sosaleid);
        mysqli_query($conn,$delsize);
        echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$sosaleid.'&msg=Size deleted successfully."</script>';
    }
    

    
}
if(isset($_GET['slid']) && !empty($_GET['slid']) && is_numeric($_GET['slid'])){
    // delete product
    if(isset($_GET['prodid']) &&  !empty($_GET['prodid']) && is_numeric($_GET['prodid'])){
        $dsql="delete from saleorderproducts where sopid =$_GET[prodid]";
        //echo $dsql;
        $dqq = mysqli_query($conn,$dsql);
        //var_Dump(mysqli_error($conn));
        $dsql = "delete from saleordersizes where sosaleid ='$_GET[slid]' and soitemid ='$_GET[prodid]'";
        $dqq = mysqli_query($conn,$dsql);
        //var_Dump(mysqli_error($conn));
        // if(mysqli_affected_rows($conn)>0){
        //     //delete from saleordersizes where soitemid=$_GET[prodid] and slid = $_GET['slid']
        //     $dsql = "delete from saleordersizes where sosaleid ='$_GET[slid]' and soitemid ='$_GET[prodid]'";
        //     $dqq = mysqli_query($conn,$dsql);
        //     echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$_GET['slid'].'&msg=Order Products Deleted Successfully"</script>';
        // }else{
        //     echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$_GET['slid'].'&errmsg=Unable to delete Product"</script>';
        // }
        
    }

    //update product
    if(isset($_GET['sopid']) &&  !empty($_GET['sopid']) && is_numeric($_GET['sopid'])){
        // Get record from database
        $ugsql = "select * from saleorderproducts where slid=$_GET[slid] and sopid=$_GET[sopid]";
        $ugqq = mysqli_query($conn,$ugsql);
        $ugcnt = mysqli_num_rows($ugqq);
        $ugrw = mysqli_fetch_assoc($ugqq);
        //  var_Dump($ugcnt);
        //  var_Dump($ugrw);
         
    }


    // if($_GET['doAction']=='addproductprice'){
        
    // }
    // if(isset($_GET['delprodid'])){
    //     $dslid=mysqli_real_escape_string($conn,$_GET['delprodid']);
    //     $delsql="delete from saleitems where slitemid='$dslid'";
    //     //echo $delsql;
    //     $dqq = mysqli_query($conn,$delsql);
    //     if(mysqli_affected_rows($conn)>0){
    //         echo '<script>window.location.href="main.php?paction=add_sale_order&slid='.$sid.'&msg=Item Deleted From Sales Order Successfully"</script>';
    //     }
    // }

    // if(isset($_GET['prodid'])){
    //     if(!empty($_GET['prodid']) && is_numeric($_GET['prodid'])){
    //         $pid=mysqli_real_escape_string($conn,$_GET['prodid']);
    //         $spid = "SELECT saleitems.slitemid, saleitems.slid, saleitems.prodid,saleitems.size, saleitems.gradeid, saleitems.brandid,
    //         saleitems.length, saleitems.lenunit, saleitems.lentyp, saleitems.qty,saleitems.qtyunit, saleitems.price, saleitems.gst FROM saleitems
    //         where slitemid = $pid";
    //         $spqq = mysqli_query($conn,$spid);
    //         $sprw = mysqli_fetch_assoc($spqq);
    //     }
    // }

    $cxsql = "SELECT saleorder.cid,saleorder.orderqty,contacts.cid as contid, contacts.cperson,contacts.countrycode, contacts.contact, contacts.designation,saleorder.slid,
    contacts.email FROM saleorder INNER JOIN contacts ON contacts.cid = saleorder.cperson where saleorder.slid =$sid";
    $cxqq = mysqli_query($conn,$cxsql);
    $crx = mysqli_fetch_assoc($cxqq);
    //var_Dump($crx);
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
                            <label for="contactNumber">Contact Number</label>
                            <div class="input-group">
                                <input type="hidden" name="countrycode"  value="101">
                                <input type="number" min="0" name="contactNumber" class="form-control" id="contactNumber" value="<?php echo $crx['contact'];?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <label for="contactNumber">Remarks</label>
                            <div class="input-group">
                                <input type="hidden" name="countrycode"  value="101">
                                <input type="text" min="0" name="remarks" class="form-control" id="remarks" value="<?php echo $crx['remarks'];?>" readonly>
                            </div>
                        </div>
                        <div class="col-lg-4 col-sm-6">
                            <div class="form-group">
                                <label for="orderQty">Order Qty (Tons)</label><!-- value in tons -->
                                <?php if(isset($_GET['slid'])){ ?>
                                    <input type="number" min="0" step="0.001" name="orderQty" class="form-control" id="orderQty" value="<?php echo $crx['orderqty'];?>" readonly>
                                <?php } else{ ?>
                                    <input type="number" min="0" step="0.001" name="orderQty" class="form-control" id="orderQty" value="" >
                                <?php } ?>
                            </div>
                        </div>
                        <?php if(isset($_GET['slid'])){ ?>
                            <input type="hidden" name="soid" value="<?php echo $crx['slid']; ?>">
                        <?php } ?>
                        <div class="btns text-right mt-4">
                            <button type="submit" name="submt_btn" value="1" class="btn btn-basic"><i class="fa fa-check"></i> 
                            <?php echo ($_GET['slid']!=""?"Update":"Add")?> Sale Order</button>
                        </div>
                        
                    </div>
                </form>
                <?php if(isset($_GET['slid']) && !empty($_GET['slid']) && is_numeric($_GET['slid'])){ ?>

                    <form action="" method="post">
                        <?php if(!empty($_GET['sopid']) && is_numeric($_GET['sopid'])){ ?>
                            <input type ="hidden" name="doAction" value="editproductprice">
                            <input type="hidden" name="rowid"  value="<?php echo $ugrw['sopid']; ?>">
                        <?php }else{ ?>
                            <input type ="hidden" name="doAction" value="addproductprice">
                        <?php } ?>
                        
                        <input type="hidden" name="slid" value="<?php echo $_GET['slid']; ?>">
                        <input type="hidden" name="cid" value="<?php echo $crx['cid']; ?>">
                        
                        <h6 class="bold text-uppercase mb-3 mt-4">Add Items</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-med">
                                <thead>
                                    <?php
                                    // $qa = "SELECT saleorderproducts.sopid, saleorderproducts.slid,saleorderproducts.gradeid,
                                    // saleorderproducts.price, products.productname, grade.grade,saleorderproducts.prodid
                                    // FROM saleorderproducts INNER JOIN products ON products.prid = saleorderproducts.prodid
                                    // INNER JOIN grade ON grade.gid = saleorderproducts.gradeid where slid=$_GET[slid]";
                                    $qa = "SELECT saleorderproducts.sopid, saleorderproducts.slid,saleorderproducts.gradeid, saleorderproducts.price, products.productname, grade.grade,saleorderproducts.prodid,(select count(*) from saleordersizes where sosaleid=saleorderproducts.slid and soprid=saleorderproducts.prodid) as scnt
                                    FROM saleorderproducts INNER JOIN products ON products.prid = saleorderproducts.prodid INNER JOIN grade ON grade.gid = saleorderproducts.gradeid where slid=$_GET[slid]";
                                    //echo $qa;
                                    $qaq = mysqli_query($conn,$qa);
                                    ?>
                                    
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
                                            <select class="form-control select2me" name="grade">
                                                <option value="">Select From List</option>
                                                <?php
                                                    $prosql = "select gid,grade from grade order by grade asc";
                                                    $proqq = mysqli_query($conn,$prosql);
                                                    while($prodrw = mysqli_fetch_assoc($proqq)){
                                                        echo '<option value="'.$prodrw['gid'].'" '.($ugrw['gradeid']==$prodrw['gid']?'selected':'').'>'.$prodrw['grade'].'</option>';
                                                    }
                                                ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="number" min="0" class="form-control" name="price" value="<?php echo $ugrw['price']; ?>">
                                        </td>
                                        <td>
                                        <?php if(!empty($_GET['sopid']) && is_numeric($_GET['sopid'])){ ?>
                                            <input type="submit" class="btn btn-basic btn-sm" name="itemsubmit" value="Update Items">
                                            <?php }else{?>
                                                <input type="submit" class="btn btn-basic btn-sm" name="itemsubmit" value="Add To Items">
                                                <?php } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                <?php 
                $qqrcnt = mysqli_num_rows($qaq);
                if($qqrcnt>0){ ?>
                <!-- <form action="#"> -->
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
                            <?php 
                               while($pq = mysqli_fetch_assoc($qaq)){
                                   
                                  ?>
                                <tr>
                                    <th>
                                        <div class="reportThumb my-1 mx-2">
                                            <?php if($pq['scnt']==0){ ?>
                                            <a href="main.php?paction=add_sale_order&slid=<?php echo $pq['slid']; ?>&sopid=<?php echo $pq['sopid']; ?>" data-toggle="tooltip" class="btn action-btn btn-primary no-print" title="" data-original-title="Edit"><i class="bi bi-pencil-square"></i></a>
                                            <?php }else if($pq['scnt']>0){ ?>
                                                <a href="main.php?paction=add_sale_order&slid=<?php echo $pq['slid']; ?>&prodid=<?php echo $pq['sopid']; ?>" data-toggle="tooltip" class="btn action-btn btn-danger no-print" title="" data-original-title="Delete"><i class="bi bi-trash"></i></a>
                                            <?php } ?>
                                            <span class="pr-4"><?php echo ucwords($pq['productname']); ?></span><br>
                                            <span class="mb-0">Rs <?php echo $pq['price']; ?>/Tons | <?php echo ucwords($pq['grade']); ?></span>
                                        </div>
                                    </th>
                                    <td style="vertical-align: middle;">
                                        <div class="row no-gutters">
                                        <?php     
                                                $esql = "SELECT saleordersizes.sosize, saleordersizes.sogradeid, saleordersizes.soitemid, saleordersizes.sobrandid, saleordersizes.sosizeid,
                                                saleordersizes.qty, saleordersizes.qtytype, sizes.size, brands.brandname, sizes.stdlength, sizes.lengthtype,saleordersizes.soprid, saleordersizes.sosaleid
                                                FROM saleordersizes INNER JOIN sizes ON sizes.sid = saleordersizes.sosizeid INNER JOIN brands ON brands.brid = saleordersizes.sobrandid
                                                where sosaleid ='$pq[slid]' and soitemid='$pq[sopid]'";
                                                    $eqq = mysqli_query($conn,$esql);
                                                    while($erw = mysqli_fetch_assoc($eqq)){
                                                ?>
                                            <div class="col-xl-2 col-lg-3 col-md-4 col-4">
                                                <div class="reportThumb my-1 mx-2">
                                                
                                                <button data-toggle="tooltip" class="btn action-btn btn-primary editsize no-print" title="" data-sosaleid="<?php echo $erw['sosaleid']; ?>" data-soprid="<?php echo $erw['soprid']; ?>" data-sosize="<?php echo $erw['sosize']; ?>" data-sogradeid="<?php echo $erw['sogradeid']; ?>"  data-solengthtypeid="<?php echo $erw['lengthtype']; ?>" data-sostdlengthid="<?php echo $erw['stdlength']; ?>"  data-soqtytypeid="<?php echo $erw['qtytype']; ?>" data-soqtyid="<?php echo $erw['qty']; ?>" data-sosizeid="<?php echo $erw['sosizeid']; ?>" data-sobrandid="<?php echo $erw['sobrandid']; ?>" data-soitemid="<?php echo $erw['soitemid']; ?>" data-original-title="Edit"><i class="bi bi-pencil-square "></i></button>
                                                    <span class="pr-4"><?php echo $erw['size']; ?> | <?php echo $erw['stdlength']; 
                                                    switch($erw['lengthtype']){
                                                        case 1:
                                                            echo ' Mtr.';
                                                        break;
                                                        case 2:
                                                            echo ' Ft.';
                                                        break;
                                                    }?></span><br>
                                                    <span class="mb-0"><?php echo ucwords($erw['brandname']); ?> | <?php echo $erw['qty']; 
                                                    switch($erw['qtytype']){
                                                        case 1:
                                                            echo ' Tons.';
                                                        break;
                                                        case 2:
                                                            echo ' Pcs.';
                                                        break;
                                                        case 3:
                                                            echo ' Qunital';
                                                        break;
                                                    }
                                                    ?> </span>
                                                </div>
                                            
                                            </div>
                                            <?php } ?>
                                            <div class="col-lg-1 col-sm-2 col-2 justify-content-center">
                                                <button type="button" data-saleorderid ="<?php echo $pq['slid']; ?>" data-gradeid ="<?php echo $pq['gradeid']; ?>" data-itemid="<?php echo $pq['sopid']; ?>" data-prodid="<?php echo $pq['prodid']; ?>" class="btn bg-success btn-round addsize text-white mx-auto"><i class="bi bi-plus"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <!-- </form> -->
               <?php }
                } ?>
               
            </div>
        </div>
    </div>
    <?php }?>
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
                <form action="" method="post">
                    <input type="hidden" name="doAction" id="doAction" value="">
                    <input type="hidden" name="sosize" id="sosize" value=""> <!--row id from saleordersize-->
                    <input type="hidden" name="saleorderid" id="saleorderid" value="">
                    <input type="hidden" name="prodid" id="prodid" value="">
                    <input type="hidden" name="gradeid" id="gradeid" value="">
                    <input type="hidden" name="itemid" id="itemid" value="">
                    <input type="hidden" name="todo" id="todo" value="">
                        
                    <div class="row">
                    <div class="col-sm-6">
                            <div class="form-group w-100">
                                <label for="modalbrand" class="d-block">Brand</label>
                                <select name="modalbrand" id="modalbrand" class="form-control select2me w-100" required>
                                    <option value="">Select From List</option>
                                    <?php 
                                        
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="size" class="d-block">Size | Std Length</label>
                                <select name="modalsize" id="modalsize" class="form-control select2me" required>
                                    <option value="">Select From List</option>
                                    
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="qty">Qty</label>
                                <div class="input-group">
                                    <input type="number" name="qty" id="qty" class="form-control" required>
                                    <div class="input-group-append">
                                        <select name="qtyunit" id="qtyunit" class="form-control" required>
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
                    <div class="text-right ">
                        <input type="submit" name="deletesize" id="deletesize" class="btn btn-danger d-none" value="Delete Size">
                        <input type="submit" name="addtosize" class="btn btn-basic" value="Add Size">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).on('click','.editsize',function(){
        let sosize = $(this).data('sosize');
        let sogradeid = $(this).data('sogradeid');
        let solengthtypeid = $(this).data('solengthtypeid');
        let sostdlengthid = $(this).data('sostdlengthid');
        let soqtytypeid = $(this).data('soqtytypeid');
        let soqtyid = $(this).data('soqtyid');
        let sosizeid = $(this).data('sosizeid');
        let sobrandid =$(this).data('sobrandid');
        let soitemid = $(this).data('soitemid');
        let sosaleid = $(this).data('sosaleid');
        let soprid = $(this).data('soprid');
        $('#addsizeModal').find('#todo').val(2); //edit
        $('#addsizeModal').find('#itemid').val(soitemid);
        $('#addsizeModal').find('#prodid').val(soprid);
        $('#addsizeModal').find('#gradeid').val(sogradeid);
        $('#addsizeModal').find('#saleorderid').val(sosaleid);
        $('#addsizeModal').find('#sosize').val(sosize);
        //console.log('getbrandandsize',sogradeid,sobrandid,soprid,sosizeid);
        $('#addsizeModal').find('#deletesize').removeClass("d-none");
        $.ajax({ // get list of all brand for product
            type:'post',
            url:"Ajax.php",
            data:{productid:soprid,gradeid:sogradeid,brandid:sobrandid,sizeid:sosizeid, doAction:'getbrandandsize'},
            success:function(data){
                let dibs = JSON.parse(data);
                brandlst = dibs['brand'];
                sizelst = dibs['sizes'];
                htmlbr = '<option value="">Select Size</option>';
                for(let a=0;a<brandlst.length;a++){
                    htmlbr += '<option value="'+brandlst[a].brid+'"'+(sobrandid==brandlst[a].brid?"selected":"")+'>'+brandlst[a].brandname+'</option>';
                }

                $('#addsizeModal').find('#modalbrand').html(htmlbr);
                htmlsize = '<option value="">Select Size</option>';
                for(let s=0; s<sizelst.length;s++){
                    htmlsize +='<option value="'+sizelst[s].sid+'"'+(sosizeid==sizelst[s].sid?"selected":"")+'>'+sizelst[s].size+'</option>';
                }

                $('#addsizeModal').find('#modalsize').html(htmlsize);   
            }
        });
        
        $('#addsizeModal').find('#qty').val(soqtyid);
        $('#addsizeModal').find('#qtyunit').val(soqtytypeid);
        $('#addsizeModal').modal();
    });

    $(document).on('click', '.addsize', function(){
        $('#addsizeModal').find('#modalbrand').val('');
        $('#addsizeModal').find('#modalsize').val(null).trigger('change');
        $('#addsizeModal').find('#qty').val('');
        $('#addsizeModal').find('#qtyunit').val('');

        $('#addsizeModal').find('#todo').val(1); //add
        $('#addsizeModal').find('#deletesize').addClass("d-none");
        //$('#addsizeModal').find('doAction').val('addsizetoorder');
        let itemid = $(this).data('itemid');
        let prodid= $(this).data('prodid');
        let gradeid= $(this).data('gradeid');
        let saleorderid= $(this).data('saleorderid');    

        $('#addsizeModal').find('#itemid').val(itemid);
        $('#addsizeModal').find('#prodid').val(prodid);
        $('#addsizeModal').find('#gradeid').val(gradeid);
        $('#addsizeModal').find('#saleorderid').val(saleorderid);

        $.ajax({
            type:'post',
            url:"Ajax.php",
            data:{productid:prodid,gradeid:gradeid,doAction:'getbrands'},
            success:function(data){
                console.log(data);
                let dibr = JSON.parse(data);
                htmlbr = '<option value="">Select Size</option>';
                for(let a=0;a<dibr.length;a++){
                    htmlbr += '<option value="'+dibr[a].brid+'">'+dibr[a].brandname+'</option>';
                }
                
                $('#addsizeModal').find('#modalbrand').html(htmlbr);
                $('#addsizeModal').find('#modalbrand').trigger('change');
                //console.log(htmlbr);
            }
        });        
        
        $('#addsizeModal').modal();
    });



    $('#modalbrand').change(function(){
        let bid = $('#modalbrand').val();
        let prodid = $('#addsizeModal').find('#prodid').val();
        let gradeid = $('#addsizeModal').find('#gradeid').val();
        // console.log(bid);
        // console.log(prodid);
        // console.log(gradeid);
        $.ajax({
             type:'post',
             url:"Ajax.php",
             data:{productid:prodid,gradeid:gradeid,brandid:bid,doAction:'getsizefrombrand'},
             success:function(data){
                //console.log(data);
                let disize = JSON.parse(data);
                htmlsize = '<option value="">Select Size</option>';
                for(let s=0; s<disize.length;s++){
                    htmlsize +='<option value="'+disize[s].sid+'">'+disize[s].size+'</option>';
                }
                $('#addsizeModal').find('#modalsize').html(htmlsize);
            }
        });
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
    
    $('#product').change(function(){
        let pid = $('#product').val();
        //console.log(pid);
        $.ajax({  
            type:'post',
            url:"Ajax.php",
            data:{prid:pid,doAction:'prodsize'},
            success:function(data){
                
                let di2 = JSON.parse(data);
                console.log(di2);
                console.log(di2.length);
                let html = '<option value="">Select Size</option>';

                for(let y=0;y<di2.length;y++){
                    html +='<option value="'+di2[y].sid+'">'+di2[y].size+'</option>';
                }
                console.log(html);
                $('#size').html(html);
            }
        });
    });

    $('#size').change(function(){
        let sid = $('#size').val();
        console.log(sid);
        $.ajax({
            type:"post",
            url:"Ajax.php",
            data:{sizeid:sid, doAction:'sizelength'},
            success:function(data){
                let di3 = JSON.parse(data);
                console.log(data);
                console.log(di3.stdlength);
                $('#length').val(di3.stdlength);
                $('#lengthUnit').val(di3.lengthtype);
            }
        });
    });
</script>
