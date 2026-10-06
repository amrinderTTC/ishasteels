<?php

$perm=check_permission("A","SK");
 $ip =  $_SERVER['REMOTE_ADDR'];
    $queryIp="SELECT * from allow_ip where ip='$ip'";
    $qIp=mysqli_query($conn, $queryIp) or die(mysqli_error($conn));
    $qlist=mysqli_fetch_array($qIp);
    // var_dump($qlist['ip']); die();
    if($qlist['ip'] != $ip & $_SESSION["user_typ"] != 'A'){ 
        echo '<script>window.location.href="main.php?paction=unauthorize&errmsg=Ip address blocked"</script>';
    }else{
if($_POST['doAction']=='updatestock'){
   // print_r($_POST); die('dddd');
    $prdid = $_POST['prid'];
    $brid = $_POST['brid'];
    $sid = $_POST['sid'];
    $grid = $_POST['grid'];
    $weight = $_POST['newWeight'];
    $remarks = $_POST['remarks'];
    for($y=0;$y<count($prdid);$y++){
        if(!empty($weight[$y])){
            $cstsql = "select currentstock from sizes where sid='".$sid[$y]."'";
            $cstqq = mysqli_query($conn,$cstsql);
            $cstrw = mysqli_fetch_assoc($cstqq);
            $currentweight = $cstrw['currentstock'];
            $newwght = ($currentweight+$weight[$y]);
            $newwght = $weight[$y];
            $ucstsql = "update sizes set currentstock='".$newwght."',remarks='".$remarks[$y]."' where prid='".$prdid[$y]."' and sid='".$sid[$y]."'";
            mysqli_query($conn,$ucstsql);
            echo '<script>window.location.href="main.php?paction=stock_view&msg=Stock updated Successfully"</script>';
        }
    } 

}
//print_r($_POST['doAction']); die('dddd1');


/**Amit Verma */
if($_GET['doAction']=='showlist'){
    if($_GET['per_page']){
        $noprd=$_GET['per_page'];
        }else{
            $noprd=15;
        }
    /**
     * sort by values
     * 1 grade asc
     * 2 grade desc
     * 3 size asc
     * 4 size desc
     */
     
     if($_GET['sortby']){
        if(!empty($_GET['sortby']) && is_numeric($_GET['sortby'])){
            $sortby = $_GET['sortby'];
            if($sortby=='1'){ // order by grade asc
                $orderby = "order by grade.gid asc";
            }else if($sortby =='2'){ // order by grade desc
                $orderby = "order by grade.gid desc";
            }else if($sortby =='3'){ // order by size asc
                $orderby = "order by sizes.sid asc";
            }else if($sortby =='4'){ // order by size desc
                $orderby = "order by sizes.sid asc";
            }
        }
    }
}else{
        $orderby = " order by products.productname asc";
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
    <?php if($perm){ ?>
    <div class="page-header">
        <h1 class="page-heading h6 ebold">Stock View</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Stock View</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">Stock View</h5>
            <div class="sort">
                <select name="sort" class="form-control" id="sort">
                    <option value="">Sort By</option>
                    <option value="1" <?php echo ($_GET['sortby']=='1'?'selected':''); ?>>Grade Asc</option>
                    <option value="2" <?php echo ($_GET['sortby']=='2'?'selected':''); ?>>Grade DESC</option>
                    <option value="3" <?php echo ($_GET['sortby']=='3'?'selected':''); ?>>Size ASC</option>
                    <option value="4" <?php echo ($_GET['sortby']=='4'?'selected':''); ?>>Size DESC</option>
                </select>
            </div>
        </div>
        <div class="filter mb-4">
            <form action="" id="filterForm" method="get">
                <input type="hidden" name="paction" value="stock_view">
                <input type="hidden" name="doAction" value="showlist">
                <div class="row">
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <input type="hidden" name="paction" class="finished_stock" value="<?php echo $_GET['paction']; ?>">
                            <label for="product">Filter By Product</label>
                            <select name="product" id="product" class="form-control select2me filteritem ">
                                <option value="">All Products</option>
                                <?php 
                                $psql = "select prid,productname from products order by productname asc";
                                $pqq = mysqli_query($conn,$psql);
                                while($prw =mysqli_fetch_array($pqq)){
                                    echo '<option value="'.$prw['prid'].'"'.($prw['prid']==$_GET['product']?' selected':'').'>'.ucwords($prw['productname']).'</option>';
                                }
                                ?>
                            </select>
                            <input type="hidden" name="sortby" class="sortby">
                        </div>
                    </div>
                    
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="brand">Filter By Brand</label>
                            <select name="brand" id="brand" class="form-control select2me filteritem">
                                <option value="">All Brands</option>
                                <?php $bsql = "select brid,brandname from brands order by brandname asc";
                                    $bqq = mysqli_query($conn,$bsql);
                                    while($brw = mysqli_fetch_assoc($bqq)){
                                        echo '<option value="'.$brw['brid'].'"'.($brw['brid']==$_GET['brand']?' selected':'').' >'.$brw['brandname'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="grade">Filter By Grade</label>
                            <select name="grade" id="grade" class="form-control select2me filteritem ">
                                <option value="">All Grades</option>
                                <?php $gsql = "select gid,grade from grade order by grade desc";
                                      $gqq = mysqli_query($conn,$gsql); 
                                        while($grw = mysqli_fetch_assoc($gqq)){
                                            echo '<option value="'.$grw['gid'].'" '.($grw['gid']==$_GET['grade']?'selected':'').'>'.$grw['grade'].'</option>';
                                        }
                                    ?>
                            </select>
                            <input type="hidden" name="sortby" class="sortby">
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="size">Filter By Size</label>
                            <select name="size" id="size" class="form-control select2me">
                                <option value="">Select From List</option>
                                <?php
                                $csql="select sid,size from sizes";
                                $cqq = mysqli_query($conn,$csql);
                                while($crw=mysqli_fetch_assoc($cqq)){
                                    echo '<option value="'.$crw['sid'].'"'.($crw['sid']==$_GET['size']?'selected':'').'>'.strtoupper($crw['size']).'</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="form-group">
                            <label for="shed">Filter By Shed</label>
                            <select name="shed" id="shed" class="form-control select2me">
                                <option value="">All Sheds</option>
                                <?php
                                    $shedsql="select lid,locname from location order by lid asc";
                                    $shedqq = mysqli_query($conn,$shedsql);
                                    while($shrw = mysqli_fetch_assoc($shedqq)){
                                        echo '<option value="'.$shrw['lid'].'"'.($shrw['lid']==$_GET['shed']?' selected ': '').'>'.$shrw['locname'].'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-xl-2">
                        <div class="h-100 d-flex align-items-center justify-content-end">
                            <input type="submit" name="filterFinishedStock" class="btn btn-basic" value="Apply Filter">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="article-content container-max">
            <form action="" method="post">
                <input type="hidden" name="doAction" value="updatestock">
                <div class="table-responsive">
                    <table class="table table-bordered table-big">
                        <thead>
                            <tr>
                                <th rowspan="2">#</th>
                                <th rowspan="2">Product</th>
                                <th rowspan="2">Brand</th>
                                <th rowspan="2">Size/Dia(mm)</th>
                                <th rowspan="2">Length(Meter)</th>
                                <th rowspan="2">Grade</th>
                                <th colspan="2" class="text-center">Available Qty</th>
                                <!-- <th colspan="2" class="text-center">Add Qty</th> -->
                                <th  rowspan="2" class="text-center">Remarks</th>
                                <th rowspan="2" class="text-center">Location</th>
                                
                                <!-- <th style="width: 5rem" rowspan="2"></th> -->
                            </tr>
                            <tr>
                                <th class="text-center">Weight(Tons)</th>
                                <th class="text-center">Pcs / Bundles</th>
                                <!-- <th style="width: 8rem" class="text-center">Weight(Tons)</th> -->
                                
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                $cpage=$_REQUEST['page'];
                                if ($cpage == 0){
                                    $cpage=1;
                                    $cnt=1;
                                }else{
                                    $cnt=($cpage * $noprd) - $noprd + 1;
                                }
                                
                               // $frm=($cpage * $noprd) - $noprd;
                                $whr=1;
                               if(isset($_GET['product']) && !empty($_GET['product']) && is_numeric($_GET['product'])){
                                    $prid = mysqli_real_escape_string($conn,$_GET['product']);
                                    $whr .=" and sizes.prid='$prid' ";
                                }
                                
                                if(isset($_GET['brand']) && !empty($_GET['brand']) && is_numeric($_GET['brand'])){
                                    $brid = mysqli_real_escape_string($conn,$_GET['brand']);
                                    $whr .=" and sizes.brand ='$brid' ";
                                }
                        
                                if(isset($_GET['grade']) && !empty($_GET['grade']) && is_numeric($_GET['grade'])){
                                        $whr .=" and sizes.grade='".$_GET['grade']."' ";
                                    
                                }
                            
                                if(isset($_GET['size']) && !empty($_GET['size']) && is_numeric($_GET['size'])){
                                        $whr .=" and sizes.sid='".$_GET['size']."' ";
                                }

                                if(isset($_GET['shed']) && !empty($_GET['shed']) && is_numeric($_GET['shed'])){
                                        $whr .=" and sizes.location='".$_GET['shed']."' ";
                                }
                                
                                $query = "SELECT sizes.sid,
                                sizes.prid,
                                sizes.size,
                                sizes.mtweight,
                                sizes.ftweight,
                                sizes.weighttype,
                                sizes.stdlength,
                                sizes.lengthtype,
                                sizes.grade as gradeid,
                                grade.grade,
                                sizes.brand,
                                sizes.bundleweight,
                                (select brandname from brands where brid=sizes.brand) as brandname, 
                                sizes.location, 
                                sizes.currentstock as weigth, 
                                sizes.remarks, 
                                products.productname, 
                                location.locname FROM sizes 
                                INNER JOIN products ON products.prid = sizes.prid 
                                INNER JOIN location ON location.lid = sizes.location 
                                INNER JOIN grade ON grade.gid = sizes.grade
                                where $whr ";
                                
                               $sql = $query;
                               $query.=" $orderby";
                               //echo $query;
                               $qq = mysqli_query($conn,$query);
                                $x = 1;
                                $twght=0;
                                $tpc=0;
                                while($rw= mysqli_fetch_assoc($qq)){
                                    //var_dump($rw);
                                    if($rw['lengthtype']=='1'){ // pc in meters
                                        $wghttn = ($rw['mtweight']*$rw['stdlength'])/1000;
                                    }else if($rw['lengthtype']=='2'){ // pc in foot
                                        $wghttn = ($rw['ftweight']*$rw['stdlength'])/1000;
                                    }  
                                    if($wghttn){
                                        $wght2pcs=($rw['weigth']/$wghttn);
                                    }
                                    ?>
                                <tr>
                                    <td><?php echo $x; ?>
                                    <input type="hidden" name="prid[]" value="<?php echo $rw['prid']; ?>">
                                    <input type="hidden" name="brid[]" value="<?php echo $rw['brand']; ?>">
                                    <input type="hidden" name="sid[]" value="<?php echo $rw['sid']; ?>">
                                    <input type="hidden" name="grid[]" value="<?php echo $rw['gradeid']; ?>">
                                    </td>
                                    <td><?php echo $rw['productname']; ?></td>
                                    <td><?php echo (empty($rw['brandname'])?'-':$rw['brandname']); ?></td>
                                    <td><?php echo $rw['size']; ?></td>
                                    <td><?php  
                                        echo ($rw['stdlength']);
                                        echo ($rw['lengthtype']=='2'?' Mt.':' Ft.');
                                        //echo ($rw['lengthtype']=='1'?' Mt.':' Ft.');
                                    ?></td>
                                    <td><?php echo $rw['grade'] ;?></td>
                                    <td class="text-right">
                                    <input type="number" name="newWeight[]" class="form-control newWeight" step="0.001" value="<?php echo $rw['weigth']; ?>">
                                        <input type="hidden" name="sizeid" class="sizeid" value="<?php echo $rw['sid']; ?>">    
                                    <?php #echo $rw['weigth'];
                                    $twght +=$rw['weigth'];
                                    ?> </td>
                                    <td class="text-right newpcs"><?php
                                    $newr=wtpcconvert($rw['stdlength'],$rw['lengthtype'],$rw['mtweight'],$rw['ftweight'],$rw['weigth'],'1',$rw['bundleweight']);
                                         $pc = round($newr[0],2);
                                         echo $pc;
                                         if(!empty($rw['bundleweight'])){
                                            echo ' Bundles';
                                         }else{
                                            echo ' Pcs.';
                                         }
                                        $tpc +=$pc;    
                                        ?></td>
                                        <td class="text-right"><input type="text" name="remarks[]" class="form-control remarks" value="<?php echo $rw['remarks']; ?>" ></td>
                                    <!-- <td class="text-right">
                                        <input type="number" name="newWeight[]" class="form-control newWeight"> amit
                                        <input type="hidden" name="sizeid" class="sizeid" value="<?php #echo $rw['sid']; ?>"> 
                                    </td>
                                    <td class="text-right"></td>
                                    <td class="text-right newpcs"></td>-->
                                    <td class="text-center"><?php echo strtoupper($rw['locname']);?></td>
                                    
                                </tr>
                            <?php $x++; } ?> 
                            <tr>
                                <th class="text-right" colspan="6">Total</th>
                                <td class="text-right"><?php echo $twght; ?> Tons</td>
                                <td class="text-right"><?php #echo $tpc; ?></td>
                                <td class="text-right" colspan="4"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="text-right">
                    <input type="submit" class="btn btn-basic" name="updateAll" value="Update All">
                </div>
            </form>
        </div>
    </div>
    <?php } } ?>
</div>

<script>

	$('#sort').change(function(){
        let sort = $(this).val();
        $('.sortby').val(sort);
        $('#filterForm').submit();
    });
    $('document').ready(function(){
        $('#grade').trigger('change');
        gid = '<?php echo $_GET['grade']; ?>';
        console.log('gid:'+gid);
    });
    
    $('.filteritem').each(function(){
        $(this).change(function(){
            let gid = $('#grade').val(); // grade id
            let brand = $('#brand').val(); // brand id
            let product = $('#product').val(); // product id
            //console.log(gid);
            if(gid!='' && brand != '' && product != ''){
                $.ajax({
                    url: 'Ajax.php',
                    type: 'POST',
                    data: {doAction:'stockviewsizelist',gradid:gid, brandid: brand, productid:product},
                    success: function(data) {
                        //console.log(data);
                        let di = JSON.parse(data);
                        html='<option value="">Select From List</option>';
                        let csizeid = '<?php echo $_GET['size']; ?>';
                        for(let x=0; x<di.length; x++){
                            html +='<option value="'+di[x].sid+'"';
                            if(csizeid !='' && di[x].sid==csizeid){
                                html += ' selected';
                            }
                            html +='>'+di[x].size+'</option>';
                        }
                        //console.log(html);
                        $('#size').html(html);
                    }
                });
            }
        })
    });



    //To work on
    $(document).on('change','.newWeight',function(e){
        e.preventDefault();
        let awght = $(this).val();
        let sizid = $(this).siblings('.sizeid').val();
        let that = $(this);
        $.ajax({
            type:"post",
            url:"Ajax.php",
            data:{doAction:'getpcsfromsizeidweight',disizeid:sizid,disweight:awght},
            success:function(data){
                //console.log(data);
                let dd = JSON.parse(data);
                //console.log(dd[0]);
                let tr = that.closest('tr');
                //console.log(tr.find('.newpcs'));
                tr.find('.newpcs').html(dd[0].toFixed(2));   
            }
        });
    });
</script>
