<?php 
include_once "../conn.php";
$createdon = date('Y-m-d h:i:s'); // current timestamp

// change user status enabled / disabled
if($_POST['doAction']=='changestatus'){
    $uid = mysqli_real_escape_string($conn,$_POST['uid']);
    $update_statussql = "UPDATE `admin` SET `status` = `status` ^ 1 where admin_id='$uid'";
    $q=mysqli_query($conn, $update_statussql) or die(mysqli_error($conn));
    $stat = mysqli_affected_rows($conn);
    $currentstatussql = "select status from admin where admin_id='$uid'";
    $qq=mysqli_query($conn, $currentstatussql) or die(mysqli_error($conn));
    $rw=mysqli_fetch_assoc($qq);
    $data['status'] =$rw['status'];
    $data['uid']  = $uid;
    echo json_encode($data);
}

//list all states for the selected country
if($_POST['typs']=='S' && !empty($_POST['countryid'])){ //states
    $qq="select * from states where country_id='$_POST[countryid]' order by name asc";
    //echo json_encode($qq);
	 $qq2=mysqli_query($GLOBALS["conn"], $qq) or die (mysqli_error($GLOBALS["conn"]));
     while($row = mysqli_fetch_array($qq2,MYSQLI_ASSOC)){
         echo '<option value="'.$row['id'].'">'.ucwords($row['name']).'</option>';
     }
    echo json_encode($row = mysqli_fetch_all($qq2,MYSQLI_ASSOC));
}

// list all sizes for selected product
if(!empty($_GET['sprodid']) && is_numeric($_GET['sprodid'])){
    $spsql ="select sid,size from sizes where prid=$_GET[sprodid]  order by sid DESC";
    $spqq = mysqli_query($conn,$spsql);
    $sp = array();
    $size = array();
    while($row = mysqli_fetch_assoc($spqq)){
        array_push($size,$row);
    }    
    echo json_encode($size);
}
// get all contact persons list from selected contact
if($_POST['doAction']=='getcontacts' && !empty($_POST['custid'])){

    //$cstsql = "select cid,cperson from contacts where cust_id='$_POST[custid]'";
    $cstsql = "select cid,cperson,prime from contacts where cust_id='$_POST[custid]'";
    $cstqq = mysqli_query($conn,$cstsql);
    $csta = array();
    while($cstrow = mysqli_fetch_assoc($cstqq)){
        array_push($csta,$cstrow);
    }
    echo json_encode($csta);
}

// get details of the contact person from selected contact
if($_POST['doAction']=='contactdetail' && !empty($_POST['csid'])){
    $cssql = "select * from contacts where cid=$_POST[csid]";
    $csqq = mysqli_query($conn,$cssql);
    echo json_encode($csrow = mysqli_fetch_assoc($csqq));
}

// get list of all sizes (salesorder) in reference to product id

if($_POST['doAction']=='prodsize' && !empty($_POST['prid'])){
    $pssql = "select sid,size from sizes where prid = $_POST[prid]";
    $psqq = mysqli_query($conn,$pssql);
    $psq = array();
    $psqq = mysqli_fetch_all($psqq,MYSQLI_ASSOC);
    echo json_encode($psqq);
}

//Get list of all sizes(stock) in reference to gradeid
if($_POST['doAction']=='gradesize' && !empty($_POST['gradid'])){
    $gsize="select sid,size from sizes where grade=$_POST[gradid]";
    $gqq=mysqli_query($conn,$gsize);
    $grw = mysqli_fetch_all($gqq,MYSQLI_ASSOC);
    echo json_encode($grw);
}
if($_POST['doAction']=='stockviewsizelist' && !empty($_POST['gradid'])&& !empty($_POST['brandid'])&& !empty($_POST['productid'])){
    
    $gsize="select sid,size from sizes where grade='$_POST[gradid]' and prid='$_POST[productid]' and brand='$_POST[brandid]'";
    $gqq=mysqli_query($conn,$gsize);
    $grw = mysqli_fetch_all($gqq,MYSQLI_ASSOC);
    echo json_encode($grw);
}

//Get Length from selected size
if($_POST['doAction']=='sizelength' && !empty($_POST['sizeid'])){
    $sizesql ="select stdlength,lengthtype FROM SIZEs where sid = '$_POST[sizeid]'";
    $sizeqq = mysqli_query($conn,$sizesql);
    $sizerw = mysqli_fetch_all($sizeqq,MYSQLI_ASSOC);
    echo json_encode($sizerw);
}

//Get all unfinished orders for seletected customer
if($_POST['doAction']=='getordersforcust' && !empty($_POST['custid'])){
    if(!empty($_POST['custid'])){
        $custorders = "SELECT saleitems.slitemid, saleitems.slid, saleitems.prodid, saleitems.size, saleitems.gradeid, 
        saleitems.brandid, saleitems.length, saleitems.lenunit, saleitems.lentyp, saleitems.qty, saleitems.qtyunit,
        saleitems.price, saleitems.gst, saleorder.cid, products.productname, grade.grade, sizes.size,
        (SELECT brandname from brands where brid=saleitems.brandid) as brand FROM saleitems
        INNER JOIN saleorder ON saleorder.slid = saleitems.slid INNER JOIN products ON products.prid = saleitems.prodid
        INNER JOIN grade ON grade.gid = saleitems.gradeid INNER JOIN sizes ON sizes.sid = saleitems.size
        where saleorder.cid='".$_POST['custid']."'";
        $custorqq=mysqli_query($conn,$custorders);
        $custorrw = mysqli_fetch_all($custorqq,MYSQLI_ASSOC);
        echo json_encode($custorrw);
    }
}
/**
 * Dispatch section
 */
// redirect to dispatchslip add
if($_POST['doAction']=='redirecttodispatchslipadd' && !empty($_POST['custid']) && !empty($_POST['vechileid'])){
    echo json_encode(array($_POST['vechileid'],$_POST['custid']));
    //echo json_encode('<script>window.location.href=main.php?paction=dispatch_slip_add&vid='.$_POST['vechileid'].'&custid='.$_POST['custid'].'</script>');
}   
// Get sale order item details for dispatch
if($_POST['doAction']=='getorderitemdetails' && !empty($_POST['saleitemid'])){
    //echo json_encode('opps');
    // $saleitemid = "SELECT saleitems.slitemid, saleitems.slid, saleitems.prodid, saleitems.size, saleitems.gradeid, saleitems.brandid, saleitems.dispatched, saleitems.length, saleitems.lenunit, saleitems.lentyp, saleitems.qty, saleitems.qtyunit, saleitems.price, sizes.mtweight, sizes.ftweight, sizes.weighttype,
    // sizes.stdlength, sizes.lengthtype,(select sum(weight) availweight from stock where prid=saleitems.prodid and sizeid=saleitems.size and gradeid=saleitems.gradeid and brandid=saleitems.brandid) as availweight FROM saleitems INNER JOIN sizes ON sizes.sid = saleitems.size where slitemid = '".$_POST['saleitemid']."'";
    $saleitemid = "SELECT saleitems.slitemid, saleitems.slid, saleitems.prodid, saleitems.size, saleitems.gradeid, saleitems.brandid, saleitems.dispatched,
    saleitems.length, saleitems.lenunit, saleitems.lentyp, saleitems.qty, saleitems.qtyunit, saleitems.price, sizes.mtweight, sizes.ftweight, sizes.weighttype, 
    sizes.stdlength, sizes.lengthtype, saleitems.weight, saleitems.pcs, sizes.currentstock  AS availweight FROM saleitems INNER JOIN sizes ON sizes.sid = saleitems.size
    where slitemid = '".$_POST['saleitemid']."'";
    $sqleitemidqq = mysqli_query($conn,$saleitemid);
    $saleitemrw = mysqli_fetch_all($sqleitemidqq,MYSQLI_ASSOC);
    echo json_encode($saleitemrw);   
}
// Get size for products on page add_size_order.php
if($_POST['doAction']=='getsizefrompid' && !empty($_POST['productid'])){
    $psize = "select sid,size from sizes where prid = $_POST[productid]";
    $psizeqq = mysqli_query($conn,$psize);
    $psizerw = mysqli_fetch_all($psizeqq,MYSQLI_ASSOC);
    echo json_encode($psizerw);
}
// Get Brand For Product
if($_POST['doAction']=='getbrands'  && !empty($_POST['productid']) && !empty($_POST[gradeid])){
    //echo json_encode($_POST);
    $brandsql ="select brid,brandname from brands where brid in (select DISTINCT brand from sizes where prid='$_POST[productid]' and grade='$_POST[gradeid]')";
    $brandqq = mysqli_query($conn,$brandsql);
    $brandrw =mysqli_fetch_all($brandqq,MYSQLI_ASSOC);
    //return json_encode($brandsql);
    echo json_encode($brandrw);
}

if($_POST['doAction']=='getsizefrombrand'  && !empty($_POST['productid']) && !empty($_POST['gradeid']) && !empty($_POST['brandid'])){
    $sizsql = "select sid,size from sizes where prid=$_POST[productid] and grade=$_POST[gradeid] and brand=$_POST[brandid]";
    $sizqq=mysqli_query($conn,$sizsql);
    $sizrw = mysqli_fetch_all($sizqq,MYSQLI_ASSOC);
    echo json_encode($sizrw);
}

if($_POST['doAction']=='getbrandandsize'  && !empty($_POST['gradeid']) && !empty($_POST['brandid']) && !empty($_POST['productid']) && !empty($_POST['sizeid'])){
// $brandsql ="select brid,brandname from brands where brid in (select DISTINCT brand from sizes where prid='$_POST[productid]' and grade='$_POST[gradeid]')";
// echo json_encode($brandsql);
// $a = array($_POST['doAction'],$_POST['gradeid'],$_POST['brandid'],$_POST['productid'],$_POST['sizeid']);
//echo json_encode($a);

//get list of all brands for selected product
$brandsql ="select brid,brandname from brands where brid in (select DISTINCT brand from sizes where prid='$_POST[productid]' and grade='$_POST[gradeid]')";
$brandqq = mysqli_query($conn,$brandsql);
$brandrw =mysqli_fetch_all($brandqq,MYSQLI_ASSOC);
$brands = array($brandrw);

//get lit of all sizes for brand selected;
$sizsql = "select sid,size from sizes where prid=$_POST[productid] and grade=$_POST[gradeid] and brand=$_POST[brandid]";
$sizqq=mysqli_query($conn,$sizsql);
$sizrw = mysqli_fetch_all($sizqq,MYSQLI_ASSOC);
$sizes = array($sizrw);

echo json_encode(array('brand'=>$brandrw,'sizes'=>$sizrw));
}

if($_POST['doAction']=='calculatepcs'  && !empty($_POST['dispatchweight']) && !empty($_POST['stdlength']) && !empty($_POST['lengthtype']) && !empty($_POST['mtweight']) && !empty($_POST['ftweight']) && !empty($_POST['qtytype'])){
    $newr=wtpcconvert($_POST['stdlength'],$_POST['lengthtype'],$_POST['mtweight'],$_POST['ftweight'],$_POST['dispatchweight'],$_POST['qtytype'],$_POST['bundleweight']);
    echo json_encode($newr);
}

if($_POST['doAction']=='adddispatchplanitem' && !empty($_POST['saleorderid']) && !empty($_POST['saleordersizeid']) && !empty($_POST['productid']) && !empty($_POST['customerid']) && !empty($_POST['pccal'])){
    echo json_encode($_POST);
}
/**
 * calculate the pcs from size id and weight given for new dispatch slip on dispatch plan page
 */

//if($_POST['doAction']=='getpcsfromsizeid&weight' && !empty($_POST['disizeid']) && !empty($_POST['disweight'])){
    if($_POST['doAction']=='getpcsfromsizeidweight' && !empty($_POST['disizeid']) && !empty($_POST['disweight'])){
        $psc = "SELECT sizes.sid, sizes.mtweight, sizes.ftweight, sizes.weighttype, sizes.stdlength,sizes.bundleweight, sizes.lengthtype from sizes where sid=$_POST[disizeid] limit 1";
        $pscqq = mysqli_query($conn,$psc);
        $psqrw = mysqli_fetch_assoc($pscqq);
        $psmtwegiht=$psqrw['mtweight'];
        $psftweight=$psqrw['ftweight'];
        $psstdlength=$psqrw['stdlength'];
        $pslengthtype=$psqrw['lengthtype'];
        $psweight = $_POST['disweight'];
        $bundleweight = $psqrw['bundleweight'];
        $newr=wtpcconvert($psstdlength,$pslengthtype,$psmtwegiht,$psftweight,$psweight,'1',$bundleweight);
        echo json_encode($newr);
    }
    
    if($_POST['doAction']=='getweightfromsizeidpcs' && !empty($_POST['disizeid']) && !empty($_POST['dispc'])){
        $psc = "SELECT sizes.sid, sizes.mtweight, sizes.ftweight, sizes.weighttype,sizes.bundleweight, sizes.stdlength, sizes.lengthtype from sizes where sid=$_POST[disizeid] limit 1";
        $pscqq = mysqli_query($conn,$psc);
        $psqrw = mysqli_fetch_assoc($pscqq);
        $psmtwegiht=$psqrw['mtweight'];
        $psftweight=$psqrw['ftweight'];
        $psstdlength=$psqrw['stdlength'];
        $pslengthtype=$psqrw['lengthtype'];
        $pspcs = $_POST['dispc'];
        $bundleweight = $psqrw['bundleweight'];
        ///print_r($bundleweight);
        // var_Dump($psqrw);
        if(is_null($bundleweight) || $bundleweight == '0' || $bundleweight == 0){
            //var_dump('stage2');
            $newr=wtpcconvert($psstdlength,$pslengthtype,$psmtwegiht,$psftweight,$pspcs,'2',$bundleweight);
            echo json_encode($newr);
        }else{
            //var_dump('stage1');
            $newr=wtpcconvert($psstdlength,$pslengthtype,$psmtwegiht,$psftweight,$pspcs,'4',$bundleweight);
            echo json_encode($newr);
        }
        
        
    }



if($_POST['doAction']=='sizeaspercondition' && !empty($_POST['prdid']) && !empty($_POST['brid']) && !empty($_POST['grid'])){
    if(is_numeric($_POST['prdid']) && is_numeric($_POST['brid']) && is_numeric($_POST['grid'])){
        $consizesql = "select sid,size,bundleweight from sizes where prid='$_POST[prdid]' and brand='$_POST[brid]' and grade='$_POST[grid]'";
        $consizeqq = mysqli_query($conn,$consizesql);
        $connsizerw = mysqli_fetch_all($consizeqq,MYSQLI_ASSOC);
        echo json_encode($connsizerw);
    }
}

if($_POST['doAction']=='sizeqtyavailableandpending' && !empty($_POST['sizeid']) && is_numeric($_POST['sizeid'])){
    $sizeqtysql = "select currentstock,(select (sum(weightintons)-sum(dispatched)) from saleordersizes where sosizeid=sizes.sid) as pending,bundleweight from sizes where sid=$_POST[sizeid]";
    $sizeqtyqq=mysqli_query($conn,$sizeqtysql);
    $sizeqtyrw=mysqli_fetch_all($sizeqtyqq,MYSQLI_ASSOC);
    echo json_encode($sizeqtyrw);
}

if($_POST['doAction']=='getsizesforproductid' && !empty($_POST['pid'])){
    $searchsizesql = "select sid,size from sizes where prid='$_POST[pid]'";
    //echo json_encode($_POST);
    $searchsizeqq=mysqli_query($conn,$searchsizesql);
    $searchsizerw=mysqli_fetch_all($searchsizeqq,MYSQLI_ASSOC);
    echo json_encode($searchsizerw);
}
if($_POST['doAction']=='removedealitem' && !empty($_POST['dealitemid'])){
    $dealiid = mysqli_real_escape_string($conn,$_POST['dealitemid']); // dealitemid
    $deletedealitem = "delete from dealitems where ditemid='$dealiid'";
    mysqli_query($conn,$deletedealitem);
    $diidres = mysqli_affected_rows($conn);
    echo json_encode($diidres);
}