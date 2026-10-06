<?php
include_once "../conn.php";

$intervaldays=10;
//gate
//weight
//dispatch
//dispatchitems
$gatesql = "SELECT * from gate where chkouton is not null and date(chkouton) < (CURDATE() - interval $intervaldays day)";
//echo $gatesql.'<br>';
$gateqq = mysqli_query($conn,$gatesql);
while($grw = mysqli_fetch_assoc($gateqq)){
    $delgate="DELETE from gate where gid=$grw[gid]";
    mysqli_query($conn,$delgate) or die(__LINE__." ".mysqli_error($conn));
    //echo $delgate.'<br>';

    $delweight = "SELECT * from weight where vhid=$grw[gid]";
    mysqli_query($conn,$delweight) or die(__LINE__." ".mysqli_error($conn));
    //echo $delweight.'<br>';

    $deldispatch = "DELETE from dispatch where vehicleid=$grw[gid]";
    mysqli_query($conn,$deldispatch) or die(__LINE__." ".mysqli_error($conn));
    //echo $deldispatch.'<br>';

    $deldispatchitem = "DELETE from dispatch_item where vid=$grw[gid]";
    mysqli_query($conn,$deldispatchitem) or die(__LINE__." ".mysqli_error($conn));
    //echo $deldispatchitem.'<br>';
}

//deals
//dealitems
//fetch all the dealorders output and run following 2 sql queries in loop
$dealquery = "SELECT * from dealorder where date(markedcompleted) < (CURDATE() - INTERVAL $intervaldays day) and status=1";
//echo $dealquery.'<br>';
$dealqq = mysqli_query($conn,$dealquery) or die(__LINE__." ".mysqli_error($conn));
while($dealrw = mysqli_fetch_assoc($dealqq)){
    $deldeal = "DELETE from dealorders where slid=$dealrw[slid]";
    mysqli_query($conn,$deldeal) or die(__LINE__." ".mysqli_error($conn));
    //echo $deldeal.'<br>';

    $deldealitems = "DELETE from dealitems where dealid=$dealrw[slid]";
    mysqli_query($conn,$deldealitems) or die(__LINE__." ".mysqli_error($conn));
    //echo $deldealitems.'<br>';
}

//saleorder
//saleordersizes
$orderquery = "SELECT * from saleorder where date(smarkedcompleted) < date(CURDATE() - interval $intervaldays day) and status=1";
//echo $orderquery.'<br>';
$orderqq = mysqli_query($conn,$orderquery) or die(__LINE__." ".mysqli_error($conn));
while($orderrw = mysqli_fetch_assoc($orderqq)){
    $delorder = "DELETE from saleorder where slid=$orderrw[slid]";
    mysqli_query($conn,$delorder) or die(__LINE__." ".mysqli_error($conn));
    //echo $delorder.'<br>';

    $delorderitem = "DELETE from saleordersizes where sosaleid=$orderrw[slid]";
    mysqli_query($conn,$delorderitem) or die(__LINE__." ".mysqli_error($conn));
    //echo $delorderitem.'<br>';
}

//stock history
$stockhistory="DELETE from stock where date(createdon) <(CURDATE() - interval $intervaldays day)";
mysqli_query($conn,$stockhistory) or die(__LINE__." ".mysqli_error($conn));
//echo $stockhistory;
?>