<?php
include_once "conn.php";

if(isset($_POST['vehicleNo'])){
    // var_Dump($_POST);

    // echo '<br/>dealid: '.
    $dealid=mysqli_real_escape_string($conn, $_POST['dealid']);
    // echo '<br/>pendingqty: '.
    $pendingqty = mysqli_real_escape_string($conn, $_POST['pendingqty']);
    // echo '<br/>dealdispatchid: '.
    $dealdispatchid=mysqli_real_escape_string($conn, $_POST['dealdispatchid']);
    // echo '<br/>dispatchqty: '.
    $dispatchqty=mysqli_real_escape_string($conn, $_POST['dispatch']);
    // echo '<br/>olddispatchvalue: '.
    $olddispatchvalue=mysqli_real_escape_string($conn, $_POST['olddispatchvalue']);
    // echo '<br/>vehicleNo: '.
    $vehicleNo=mysqli_real_escape_string($conn, $_POST['vehicleNo']);
    $dtotal=0;
    if($olddispatchvalue>$dispatchqty){
        /**
         * 3.12 > 3.10 and current pending dispatch is 4
         * .02 will be added to 4 and total will be 4.02
         */
        $dtotal=number_format($olddispatchvalue-$dispatchqty,3);
        $dtotal=number_format($pendingqty+$dtotal,3);
        //echo $olddispatchvalue." > ".$dispatchqty.'<br>';
    }elseif($olddispatchvalue < $dispatchqty){
        /**
         * 3.12 < 3.14 and current pending dispatch is 4
         * .02 will be subtracted from the 4 and total will be 3.98
         */
        //echo $olddispatchvalue." < ".$dispatchqty.'<br>';
        
        $dtotal=number_format($dispatchqty,3)-number_format($olddispatchvalue,3);
        $dtotal=number_format($pendingqty,3)-number_format($dtotal,3);
    }
    
    $update1 = "update dealdispatch set 
        vehicleno='$vehicleNo',
        dispatchqty='$dispatchqty' 
        where dealid='$dealid' and dealdispatchid='$dealdispatchid'";
    mysqli_query($conn,$update1);
     
    if($dtotal!=0){
        $update2="update dealorder set dispatchedqty='$dtotal' where slid='$dealid'";
        mysqli_query($conn,$update2);
    }

    $ar['success']=1;
    $ar['vehicleNo']=$vehicleNo;
    $ar['dispatchqty']=$dispatchqty;
    //  return $update1; 
    //$_POST=array();
    ///echo '<script>window.location.href="?paction=client_report&msg=Deal Dispatched Updated Successfully."</script>';
    // echo "1";
}else{
    $ar['success']="0";
    // echo "0";
}

echo json_encode($ar);
?>