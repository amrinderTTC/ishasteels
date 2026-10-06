<?php

$perm=check_permission("A");
$slid =  mysqli_real_escape_string($conn, $_GET['slid']);

// sale order details
$cxsql = "SELECT saleorder.cid, contacts.cid AS contid, contacts.cperson, contacts.countrycode, contacts.contact, contacts.designation, saleorder.slid, contacts.email,
customers.email as custemail, saleorder.createdon, customers.`name` as cust, countries.`name` as countryname, states.`name` as statename, customers.pincode, customers.address,customers.city,
countries.phonecode as cphonecode,ccode.phonecode FROM saleorder INNER JOIN contacts ON contacts.cid = saleorder.cperson INNER JOIN customers ON customers.cust_id = saleorder.cid INNER JOIN countries ON countries.id = customers.country  
INNER JOIN states ON states.id = customers.state INNER JOIN countries as ccode ON ccode.id = customers.countrycode where saleorder.slid=$slid";
//echo $cxsql;
$cxqq = mysqli_query($conn,$cxsql);
$crx = mysqli_fetch_assoc($cxqq);
//var_Dump($crx);

//show all products of the sale order
// $spid = "SELECT saleitems.slitemid, saleitems.slid, saleitems.prodid,saleitems.size, saleitems.gradeid, saleitems.brandid,
// saleitems.length, saleitems.lenunit, saleitems.lentyp, saleitems.qty, saleitems.price, saleitems.gst FROM saleitems
// where saleitems.slid = $slid";
$spid = "SELECT products.productname, sizes.size, grade.grade, brands.brandname, saleitems.length, saleitems.lenunit, saleitems.lentyp,
saleitems.qty, saleitems.price, saleitems.gst, saleitems.pcs,
saleitems.weight,saleitems.qtyunit, saleitems.pcs, saleitems.qtyunit, saleitems.weight
FROM saleitems INNER JOIN products ON products.prid = saleitems.prodid INNER JOIN sizes ON sizes.sid = saleitems.size INNER JOIN grade ON grade.gid = saleitems.gradeid INNER JOIN brands ON brands.brid = saleitems.brandid
where saleitems.slid = $slid";
//echo $spid;
$spq = mysqli_query($conn,$spid);
// $mtotal='';
// $mtotal1 = '';
// $mgst='';
// $mgstamt='';
// $mgstamt1='';
// $mgtotal='';
// while($sprw=mysqli_fetch_assoc($spq)){
//     $mtotal+=($sprw['qty']*$sprw['price']);
//     $mtotal1 = ($sprw['qty']*$sprw['price']);
//     if(!empty($sprw['gst'])){
//         $mgstamt=($mtotal1*$sprw['gst'])/100;
//         $mgstamt1 +=$mgstamt;
//     }
// }

// $gtotal = ($mtotal+$mgstamt1);
?>
<link rel="stylesheet" href="assets/css/print.css">
<div class="main-content-inner">
    <div class="page-header no-print">
        <h1 class="page-heading h6 ebold">Sale Purchase Order</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Manage Orders</li>
            <li class="breadcrumb-item">Print Sale Order</li>
        </ul>
    </div>
    <div class="text-right no-print mb-3"><button class="btn btn-basic" onclick="window.print()"><i class="bi bi-printer"></i>&nbsp;Print Order</button></div>
    <div class="table-responsive print-area">
        <table class="table table-bordered bg-white">
            <thead>
                <tr>
                    <td class="px-0 print-header text-center" colspan="14">
                        <img class="img-fluid" src="assets/images/inv-header.jpg">
                    </td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th colspan="7">Order No. : <span><?php echo $crx['slid'];?></span></th>
                    <th colspan="7" class="text-right">Order Date : <span><?php echo date('d-m-Y',strtotime($crx['createdon']));?></span></th>
                </tr>
                <tr>
                    <th colspan="7">Party Details</th>
                    <th colspan="7">Party Contact</th>
                </tr>
                <tr>
                    <td colspan="7">
                        <p class="bold mb-1"><?php echo ucwords($crx['cust']);?></p>
                        <p class="med mb-0"><?php echo $crx['address'];?>, <?php echo $crx['city'];?></p>
                        <p class="med mb-1"><?php echo ucwords($crx['statename']);?>-<?php echo ucwords($crx['pincode']);?>,<?php echo ucwords($crx['countryname']);?></p>
                        <p class="med mb-0"><i class="fa fa-envelope"></i> <?php echo $crx['custemail'];?></p>
                    </td>
                    <td colspan="7">
                        <p class="bold mb-1"><?php echo ucwords($crx['cperson']);?> (<?php echo ucwords($crx['designation']);?>)</p>
                        <p class="med mb-1"><i class="fa fa-envelope"></i> <?php echo $crx['email'];?></p>
                        <p class="med mb-0"><i class="fa fa-phone-alt"></i> +<?php echo $crx['phonecode'].'-'.$crx['contact'] ?></p>
                    </td>
                </tr>
                <tr>
                    <th colspan="14">Ordered Items</th>  
                </tr>
                <tr>
                    <th rowspan="2">Item Name</th>
                    <th rowspan="2">Size/Dia (mm)</th>
                    <th rowspan="2">Length</th>
                    <th rowspan="2">Length Type</th>
                    <th rowspan="2">Grade</th>
                    <th rowspan="2">Brand</th>
                    <th colspan="2" class="text-center">Qty</th>
                    <th rowspan="2" class="text-center">Price/Ton</th>
                    <th rowspan="2" class="text-center">Total</th>
                    <th rowspan="2" class="text-center">GST%</th>
                    <th rowspan="2" class="text-center">GST Amt</th>
                    <th rowspan="2" class="text-center">Subtotal</th>
                </tr>
                <tr>
                    <th>Weight</th>
                    <th>Pcs.</th>
                </tr>
                <?php 
                                $spqq = mysqli_query($conn,$spid);
                                $stotal ='';
                                $xstotal = '';
                                $xtotal = '';
                                $xgstamt = '';
                                $rcnt = mysqli_num_rows($spqq);
                                $xx = 1;
                                while($sprw1=mysqli_fetch_assoc($spqq)){ 
                                    //var_Dump($sprw1);
                                    $ntotal = ($sprw1['weight']*$sprw1['price']);
                                    $ngstamt2 = '';
                                    if(!empty($sprw1['gst'])){
                                        $ngstamt = ($ntotal*$sprw1['gst'])/100;
                                        $ngstamt2 = $ngstamt;
                                    }else{
                                        $ngstamt = '-';
                                        $ngstamt2 = '0';
                                    }
                                    
                                   // echo ($ngstamt2.'_'.$ntotal);
                                    $stotal = ($ngstamt2+$ntotal);

                                    $xtotal += $ntotal;
                                    $xgstamt += $ngstamt2;
                                    $xstotal  += $stotal;

                                    ?>
                                <tr <?php if($xx == $rcnt){
                                    echo 'class="last"';
                                }?>>
                                    <td><?php echo ucwords($sprw1['productname']); ?></td>
                                    <td><?php echo ucwords($sprw1['size']); ?></td>
                                    <td><?php
                                    if($sprw1['length']!='0.00'){
                                        echo $sprw1['length']; 
                                        echo ($sprw1['length']=='1'?' Ft.':'Mt.');
                                    }else{
                                        echo '-';
                                    }
                                    
                                    ?></td>
                                    <td><?php echo (!empty($sprw1['lentyp'])?$sprw1['lentyp']:'-'); ?></td>
                                    <td><?php echo ucwords($sprw1['grade']); ?></td>
                                    <td><?php echo (!empty($sprw1['brandname'])?ucwords($sprw1['brandname']):'-'); ?></td>
                                    <td class="text-right"><?php echo $sprw1['weight']; ?></td>
                                    <td class="text-right"><?php echo $sprw1['pcs']; ?></td>
                                    <td class="text-right"><?php echo $sprw1['price']; ?></td>
                                    <td class="text-right"><?php echo $ntotal; ?></td>
                                    <td class="text-right"><?php echo ($sprw1['gst']!='0'?$sprw1['gst']:'-'); ?></td>
                                    <td class="text-right"><?php echo $ngstamt; ?></td>
                                    <td class="text-right"><?php echo $stotal; ?></td>
                                    <!-- <td>
                                        <a href="main.php?paction=Sale_order_details&poid=12&pid=1" class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a>
                                    </td> -->
                                </tr>
                                <?php $xx++; } ?>
                                <tr>
                                    <th class="text-right" colspan="9">Total</th>
                                    <td class="text-right"><?php echo $xtotal; ?></td>
                                    <td class="text-right" colspan="2"><?php echo $xgstamt; ?></td>
                                    <td class="text-right"><?php echo $xstotal; ?></td>
                                </tr>
                
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="14" class="text-center p-0 pt-5">
                        <img class="img-fluid" src="assets/images/quote-footer.jpg">
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    window.onbeforeprint = function(){
        let ppi = 0;
        if (window.matchMedia(' (min-width: 1600px) ').matches){
            ppi = 147
        }else if(window.matchMedia(' (max-width: 768px) ').matches){
            ppi = 132
        }else{
            ppi = 142
        }
        //console.log(ppi);
        let pageHeight = (11*ppi) - ((.2*ppi)+30);
        let printarea = $('.print-area').height();
        if(printarea < pageHeight){
            let difference = pageHeight - printarea;
            $('.print-area').find('.last').css('height', difference);
        }
    }
    window.onafterprint = function(){
        $('.print-area').find('.last').css('height', 'auto');
    }
</script>
