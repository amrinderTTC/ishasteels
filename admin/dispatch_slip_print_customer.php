<style>
    @media print{
        @page{
            size: 13cm 21.5cm;
            margin: .75cm .75cm;
        }

        *{
            -webkit-print-color-adjust: exact !important;   /* Chrome, Safari, Edge */
            color-adjust: exact !important;
        }
        body{
            /* max-width:400px;
            width: 400px !important;
            min-width: 400px !important; */
            /* margin:0px auto; */
            /* width: 11cm;
            height: 21.5cm; */
            /* background: #e0e0e0 !important */
        }
        .page-wrapper .main-content{
            padding: 0px;
            padding-top: 0px !important;
        }
        .table{
            margin-bottom: 0px;
        }
        table tr.heading{
            background: rgba(230,230,230)
        }
        .table.table-bordered th, .table.table-bordered td{
            border-color: #000 !important
        }
        .table th, .table td{
            font-family: 'Nunito', sans-serif;
            padding: 2px 5px;
            font-size: .65rem;
            font-weight: 700;
        }
        .table tr.productrow td{
            padding: 1px 5px;
        }
        table tr.heading th{
            padding:3px 5px;
            background: rgba(220,220,220) !important;
            white-space: nowrap
        }
        span.heading5, h6.bold{
            font-size: .8rem
        }
        th h5.bold{
            font-size: 1rem
        }
        .table-bordered th.border-bottom-0, .table-bordered td.border-bottom-0{
            border-bottom: 0px !important;
        }
        .table-bordered th.border-top-0, .table-bordered td.border-top-0{
            border-top: 0px !important;
        }

    }
</style>

<?php

$perm=check_permission("A");

    if(isset($_GET['vid']) && !empty($_GET['vid']) && is_numeric($_GET['vid'])){
        if(isset($_GET['vid']) && !empty($_GET['vid']) && is_numeric($_GET['vid'])){
            $vid = mysqli_real_escape_string($conn, $_GET['vid']);
            $dispid = mysqli_real_escape_string($conn, $_GET['dispid']);
        
        $vsql ="SELECT gate.gid, gate.cid, gate.vehicleno, gate.vehicletype, gate.transport, gate.drivername, gate.drivermobile, gate.efrom, gate.gatestatus, gate.chkinon, gate.chkinby, gate.dslip, gate.chkouton, gate.chkoutby, gate.createdon, gate.createdby, gate.modifiedon, gate.modifiedby, vehicletype.vtname,
        (SELECT customers.`name` FROM customers INNER JOIN dispatch ON customers.cust_id = dispatch.custid where dispatch.dispatchid=$dispid ) as custname, (select createdon from dispatch where dispatchid=$dispid) as dispatchcreatedon FROM gate INNER JOIN vehicletype ON vehicletype.vtid = gate.vehicletype where gid=$vid";
        
        $vsqlq=mysqli_query($conn,$vsql) or die(mysqli_error($conn));
        $vew = mysqli_fetch_assoc($vsqlq);

        $query1="SELECT
		dispatch_item.dispitemid,
		dispatch_item.dispid,
		dispatch_item.vid,
		dispatch_item.customer,
		dispatch_item.slid,
		dispatch_item.sopid,
		dispatch_item.sosize,
		dispatch_item.prodid,
		dispatch_item.gradeid,
		dispatch_item.sizeid,
		dispatch_item.brandid,
		dispatch_item.weighttodispatch,
		dispatch_item.pctodispatch,
		dispatch_item.actualweight,
		dispatch_item.actualpcs,
		dispatch_item.finalweighttodispatch,
		dispatch_item.finalweight,
		dispatch_item.finalbundles,
		dispatch_item.finalpcs,
		dispatch_item.finalpctodispatch,
		dispatch_item.completed,
		dispatch_item.createdon,
		dispatch_item.createdby,
		grade.grade,
		brands.brandname,
		products.productname,
		sizes.size,
		sizes.mtweight,
		sizes.ftweight,
		saleorder.remarks,
		sizes.weighttype,
		sizes.stdlength,
		sizes.lengthtype,
		sizes.bundleweight,
		sizes.currentstock,
		location.locname,
		sizes.location,
        saleordersizes.soprice
		FROM
		dispatch_item
		INNER JOIN grade ON grade.gid = dispatch_item.gradeid
		INNER JOIN brands ON brands.brid = dispatch_item.brandid
		INNER JOIN products ON products.prid = dispatch_item.sopid
		INNER JOIN saleorder ON saleorder.slid = dispatch_item.slid
		INNER JOIN sizes ON sizes.sid = dispatch_item.sizeid
		INNER JOIN location ON location.lid = sizes.location
        INNER JOIN saleordersizes ON saleordersizes.sosize = dispatch_item.sosize
		where dispid = $_GET[dispid] and vid=$_GET[vid] and customer=$_GET[custid] order by saleordersizes.sosize,products.productname asc";
		//$disqq = mysqli_query($conn,$dissql);
		// $totaltod =0;
		// $qq = mysqli_query($conn,$query1);
		// $re = mysqli_num_rows($qq);
        //$disqq = mysqli_query($conn,$dissql);
        $totaltod =0;
        $qq2 = mysqli_query($conn,$query1);
        $qq = mysqli_query($conn,$query1);
        $re = mysqli_num_rows($qq);
        }
    }
?>
<link href="assets/css/print.css" type="text/css" rel="stylesheet"></link>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th colspan="5" class="py-2"><h5 class="bold mb-0 text-center"><?php echo $vew['custname']; ?></div></th>
                </tr>
                <tr>
                    <th colspan="2" class="py-1">Slip No.: <h6 class="bold mb-0 d-inline"><?php echo $_GET['dispid'];?></h6></th>
                    <th colspan="3" class="py-1 text-right"><span class="heading6 mb-0">Date: <span class="heading5 ml-1"><?php echo date('d-m-Y', strtotime($vew['dispatchcreatedon'])); ?></span></span></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th colspan="2" class="border-bottom-0">Token No.: <span class="heading5 ml-1"><?php echo $vew['gid']; ?></span></th>
                    <th colspan="3" class="text-right border-bottom-0"><span class="heading5 ml-1"><?php echo $vew['vehicleno']; ?></span></th>
                </tr>
                <?php $rew1 = mysqli_fetch_array($qq2); 
                if($rew1['remarks'] !=NULL){ ?>
                <tr>
                    <th colspan="5" class="border-bottom-0">Order Remarks.: <span class="heading5 ml-1">
                      <?php echo $rew1['remarks']; ?></span></th>
                </tr>
                <?php } ?>
                <tr class="heading">
                    <th rowspan="2" style="width: 2rem; ">S.N.</th>
                    <th rowspan="2" style="width: 45%">Product</th>
                    <!-- <th rowspan="2">Grade</th> -->
                    <th rowspan="2" class="text-right" style="width: 2.5rem">Qty.</th>
                    <th rowspan="2" class="text-right">Price</th>
                    <th rowspan="2" class="text-right">Total</th>
                    <!-- <th rowspan="2">Brand</th> -->
                    <!-- <th class="text-center" >Qty Dispatched</th> -->
                </tr>
                <tr>
                    <!-- <th class="text-center">Weight</th> -->
                    <!-- <th class="text-center">Pcs/Bundles</th> -->
                </tr>
                <?php
                    if($re>0){
                        $pwsum = 0; // panned weight sum
                        $pcsum = 0; // planned pc sum
                        $bundlesum = 0; // qty planned bundle sum
                        $bundelpcssum = 0; // qty planned pcs in bundle sum
                        $disbundlesum = 0; // qty dispatched bundle sum
                        $disbundelpcssum = 0; // qty dispatched pcs in bundle sum
                        $chklocation = '';
                        $i = 1;
                        $total = 0;
                        $subtotal = 0;

                       while($rew = mysqli_fetch_assoc($qq)){
                        $tsum += $rew['weighttodispatch'];
                    ?>
                <tr  class="<?php echo ($i==$re?'last':''); ?> productrow">
                    <td class="border-top-0 border-bottom-0"><?php echo $i; ?></td>
                    <td class="border-top-0 border-bottom-0"><?php #echo ucwords($rew['productname']); ?><?php echo $rew['size']; ?></td>
                    <!-- <td><?php #echo ucwords($rew['grade']); ?></td> -->
                    <!-- <td><?php #echo (!empty($rew['brandname'])?ucwords($rew['brandname']):'-'); ?></td> -->
                    <td class="border-top-0 border-bottom-0 text-right"><?php echo $rew['finalweight']; $pwsum+=$rew['finalweight']; ?> </td>
                    <td class="border-top-0 border-bottom-0 text-right"><?php echo $rew['soprice']; ?></td>
                    <td class="border-top-0 border-bottom-0 text-right"><?php 
                    if(!empty($rew['finalweight'])){
                        echo $total=($rew['finalweight']*$rew['soprice']);  
                        $subtotal +=$total;
                    } ?></td>
                    <!-- <td><?php #echo $rew['pctodispatch']; echo (empty($rew['bundleweight'])?' Pcs.':' Bundles') ?></td> -->
                </tr>
                <?php  
                   $i++; 
                 }
                    } 
                    
                    $disinfo =  "select weight,cashdiscount,laborchr,otherchr,tcs from dispatch where dispatchid=$dispid";
                    $disinfoqq = mysqli_query($conn,$disinfo);
                    $dirw = mysqli_fetch_assoc($disinfoqq);
                    //var_dump($dirw);
                    ?>
                <tr>
                    <th style="vertical-align:bottom" colspan="2" class="text-right"><strong>Total</strong></th>
                    <th style="vertical-align:bottom" class="text-right"><strong><?php echo $pwsum;#$dirw['weight']; ?></strong></th>
                    <th style="vertical-align:bottom" class="text-right"></th>
                    <th  class="text-right"><span class="heading5"><?php echo number_format($subtotal, 2, ',', ' ');?></span></th>
                </tr>
                <tr>
                    <th style="vertical-align:bottom" colspan="4" class="text-right">Cash Discount <?php echo $dirw['cashdiscount']; ?>% </th>
                    <td  class="text-right"><?php echo $cds = (($subtotal*$dirw['cashdiscount'])/100);
                    echo number_format($cds, 2, ',', ' ');
                    
                    ?></td>
                </tr>
                
                <tr>
                    <th style="vertical-align:bottom" colspan="4" class="text-right">Labor(Rs)</th>
                    <td  class="text-right"><?php $laborcharges=($dirw['weight']*$dirw['laborchr']);
                    echo number_format($laborcharges, 2, ',', ' ');?></td>
                </tr>
                <tr>
                    <th style="vertical-align:bottom" colspan="4" class="text-right">Other Charges(Rs)</th>
                    <td  class="text-right"><?php echo number_format($dirw['otherchr'], 2, ',', ' ');; ?></td>
                </tr>
                <tr>
                    <th style="vertical-align:bottom" colspan="4" class="text-right"><strong>Taxable Amt.</strong></th>
                    <th  class="text-right"><span class="heading5"><?php $newsubtotal = (($subtotal-$cds)+$laborcharges+$dirw['otherchr']);
                    echo number_format($newsubtotal, 2, ',', ' ');
                    ?></span></th>
                </tr>
                <tr>
                    <th style="vertical-align:bottom" colspan="4" class="text-right">GST 18%</th>
                    <td class="text-right"><?php echo $gstamt = round(($newsubtotal*18)/100);?></td>
                </tr>
                <tr>
                    <th style="vertical-align:bottom" colspan="4" class="text-right">TCS</th>
                    <td  class="text-right"><?php echo $dirw['tcs']; ?></td>
                </tr>
                <tr>
                    <th style="vertical-align:bottom" colspan="4" class="text-right"><strong>Grand Total</strong></th>
                    <th class="text-right"><span class="heading5"><?php echo round($newsubtotal+$tcs+$gstamt);?></span></th>
                </tr>
                <tr style="height:3rem">
                    <th style="vertical-align:bottom" colspan="2" class="text-left">TDS:</th>
                    <th style="vertical-align:bottom" colspan="3" class="text-right">Authroised Signature</th>
                </tr>
            </tbody>
        </table>
<script>
    $(document).ready(function(){
        let abc = $('.table').height();
        let lastHeight = $('.last').height();
        let mainheight = 890;
        let productCount = $('.productrow').length;
        if(productCount < 4){
            mainheight -= (60/productCount);
        }else if(productCount > 4 && productCount <= 7){
            mainheight += (productCount*5.5)
        }else if(productCount > 7 &&  productCount <= 11){
            mainheight += (productCount*8)
        }else if(productCount > 11 && productCount <= 15){
            mainheight += (productCount*11)
        }else if(productCount > 15 && productCount <= 20){
            mainheight += (productCount*14)
        }

        let lasttrHeight = lastHeight + (mainheight - abc);
        console.log(abc, lastHeight, lasttrHeight);
        $('.last').height(lasttrHeight);
		console.log($('.table').height());
        window.print();
    });
    
	window.addEventListener('afterprint', (event) => {
		window.location.href="main.php?paction=dispatch_plan&vid=<?php echo $vid ?>";
    });
    
	window.addEventListener('beforeprint', (event) => {
    });
</script>