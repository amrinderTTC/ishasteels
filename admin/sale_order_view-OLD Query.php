<?php
$perm=check_permission("A");
if(isset($_GET['delslid']) && is_numeric($_GET['delslid'])){
    //first delete items from saleitems
    echo $did = $_GET['delslid'];
    $desq2l = "delete from saleordersizes where sosaleid='$did'";
    //echo $desq2l.'<br><br><br>';
    mysqli_query($conn,$desq2l);
    $de2sql1 = "delete from saleorder where slid='$did'";
    //echo $de2sql1.'<br><br><br>';
    mysqli_query($conn,$de2sql1);
    echo '<script>window.location.href="main.php?paction=sale_order_view&msg=Sale Order Deleted Successfully."</script>';
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
        <h1 class="page-heading h6 ebold">Sale Orders</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Manage Orders</li>
            <li class="breadcrumb-item">Sale Orders</li>
        </ul>
    </div>

    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">Sale Orders</h5>
            <a href="main.php?paction=add_sale_order" class="btn btn-basic btn-sm">New Order</a>
        </div>
        
        <div class="article-content container-max">
            <div class="filter">
                <form action="" name="filter" method="get">
                    <input type="hidden" name="paction" value="sale_order_view">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="orderno">Order No.</label>
                                <input type="text" name="orderno" id="orderno" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-8 col-sm-6">
                            <div class="form-group">
                                <label for="partyname">Party Name</label>
                                <select name="partyname" id="partyname" class="form-control select2me">
                                    <option value="">Search Party Name</option>
                                    <?php
                                        $csql = "select cust_id,name from customers";
                                        $cqq = mysqli_query($conn,$csql);
                                        while($crw = mysqli_fetch_assoc($cqq)){
                                            echo '<option value="'.$crw['cust_id'].'">'.$crw['name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12">
                            <div class="form-group mt-4 pt-1">
                                <input type="submit" name="filter" class="btn btn-basic" value="Apply Filter">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-xl">
                    <thead>
                        <tr>
                            <th>Order Date</th>
                            <th>Order No.</th>
                            <th>Party Name</th>
                            <th>Contact Person</th>
                            <th>Contact No.</th>
                            <th>Order Qty.</th>
                            <th>Dispatched Qty.</th>
                            <th>Pending Qty.</th>
                            <th>Status</th>
                            <!--<th>Order Pcs.</th>-->
                            <th>Added By</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($_GET['per_page']){
                            $noprd=$_GET['per_page'];
                        }else{
                            $noprd=15;
                        }
                        
                        $orderby = "order by saleorder.slid desc";
                        
                        $cpage=$_REQUEST['page'];
                        if ($cpage == 0){
                            $cpage=1;
                            $cnt=1;
                        }else{
                            $cnt=($cpage * $noprd) - $noprd + 1;
                        }
                        
                        $frm=($cpage * $noprd) - $noprd;
                        $whr=1;

                        
                        if($_GET['orderno']){
                            $orderno = mysqli_real_escape_string($conn, $_GET['orderno']);
                            $whr .= " and saleorder.`slid` = '$orderno' ";
                        }

                        if(isset($_GET['partyname']) && !empty($_GET['partyname'])){
                            $partyname = mysqli_real_escape_string($conn, $_GET['partyname']);
                            $whr .= " and  customers.cust_id = '$partyname'";
                        }
                        
                        // $query = "SELECT
                        // saleorder.slid,
                        // saleorder.cid,
                        // contacts.cperson,
                        // customers.mobile,
                        // saleorder.`status`,
                        // saleorder.orderqty AS maxorderqty,
                        // (select sum(weightintons) from saleordersizes where sosaleid = saleorder.slid) AS orderedqty,
                        // (select sum(pcs) from saleordersizes where sosaleid = saleorder.slid) AS orderedpcs,
                        // (select sum(dispatched) from saleordersizes where sosaleid = saleorder.slid) AS ordereddispatched, 
                        // customers.`name` as partyname,
                        // saleorder.createdby,
                        // admin.`user` as createdbyname,
                        // saleorder.createdon,(select slid from dispatch_item where slid = saleorder.slid limit 1) as slidused 
                        // FROM
                        // saleorder
                        // INNER JOIN customers ON customers.cust_id = saleorder.cid
                        // INNER JOIN admin ON admin.admin_id = saleorder.createdby
                        // INNER JOIN contacts ON contacts.cust_id = customers.cust_id where $whr and saleorder.status='0'";
                        $query = "SELECT
                        saleorder.slid,
                        saleorder.cid,
                        contacts.cperson,
                        contacts.contact AS mobile,
                        saleorder.`status`,
                        saleorder.orderqty AS maxorderqty,
                        (select sum(weightintons) from saleordersizes where sosaleid = saleorder.slid) AS orderedqty,
                        (select sum(pcs) from saleordersizes where sosaleid = saleorder.slid) AS orderedpcs,
                        (select sum(dispatched) from saleordersizes where sosaleid = saleorder.slid) AS ordereddispatched,
                        customers.`name` AS partyname,
                        saleorder.createdby,
                        admin.`user` AS createdbyname,
                        saleorder.createdon,
                        (select slid from dispatch_item where slid = saleorder.slid limit 1) AS slidused,
                        countries.phonecode
                        FROM
                        saleorder
                        INNER JOIN customers ON customers.cust_id = saleorder.cid
                        INNER JOIN admin ON admin.admin_id = saleorder.createdby
                        INNER JOIN contacts ON contacts.cust_id = customers.cust_id
                        INNER JOIN countries ON countries.id = contacts.countrycode
                        where $whr and customers.cust_id=saleorder.cid and saleorder.cperson=contacts.cid and ((DATE_ADD(DATE_ADD(DATE(saleorder.`smarkedcompleted`), INTERVAL 12 HOUR), INTERVAL 1 DAY) >= NOW() and saleorder.`status`=1) or saleorder.`status`=0)";
                        
                        $sql=$query;
                        $query.=" $orderby LIMIT $frm, $noprd";
                        //echo $query;
                        $qq = mysqli_query($conn,$query);
                        if(mysqli_num_rows($qq)>0){
                            while($rw=mysqli_fetch_assoc($qq)){
                            ?>
                            <tr 
                            <?php 
                            if($rw['status']=='1'){
                                echo 'class="bg-lightgreen"';
                            }
                            
                            
                            ?>>
                                <td><?php echo date('d-m-Y',strtotime($rw['createdon'])); ?></td>
                                <td><?php echo $rw['slid']; ?></td>
                                <td><?php echo strtoupper($rw['partyname']); ?></td>
                                <td><?php echo strtoupper($rw['cperson']);; ?></td>
                                <td><?php echo '+'.$rw['phonecode'].'-'.$rw['mobile']; ?></td>
                                <td><?php echo $rw['orderedqty']; ?> Tons</td>
                                
                                <td><?php  echo $rw['ordereddispatched'];?></td>
                                <td><?php echo ($rw['orderedqty']-$rw['ordereddispatched']); ?> Tons</td>
                                
                                <td><?php echo strtoupper($rw['createdbyname']); ?></td>
                                <td>
                                    <a data-toggle="tooltip" title="View Details" href="main.php?paction=sale_order_details&slid=<?php echo $rw['slid']; ?>" class="btn action-btn btn-success"><i class="bi bi-eye"></i></a>
                                    <?php if($rw['status']=='0'){ ?>
                                    <a data-toggle="tooltip" title="Edit" href="main.php?paction=edit_sale_order&slid=<?php echo $rw['slid']; ?>" class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                    <?php } ?>
                                    <?php if(empty($rw['slidused'])){ ?>
                                    <a data-toggle="tooltip" title="Delete" href="main.php?paction=sale_order_view&delslid=<?php echo $rw['slid']; ?>" class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a>
                                    <?php } ?>
                                </td>
                            </tr>
                        <?php }
                        }else{
                            echo '<tr><td colspan="8" class="text-center">No Record Found.</td></tr>';
                        } ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagging -->
            <div class="pagging">
                    <div class="right">
                        <?php
                        include('ps_pagination.php');
                        $pager = new PS_Pagination($conn, $sql, $noprd, 6, "paction=$_GET[paction]&orderno=$_GET[orderno]&$_GET[partyname]");
                        $pager->setDebug(false);
                        $pager->total_rows=$result;
                        $rs = $pager->paginate();
                        echo $pager->renderFirst();
                        echo $pager->renderPrev("Back");
                        echo $pager->renderNav('<span>', '</span>');
                        echo $pager->renderNext("Next");
                        echo $pager->renderLast();
                        ?>
                    </div>
                </div>
            <!-- End Pagging -->
        </div>
    </div>
    <?php } ?>
</div>

<script>
	$(document).on('click','.statusToggle',function(){
        $(this).toggleClass('active inactive');
        $(this).children('.fa').toggleClass('fa-toggle-on fa-toggle-off');
        let id = $(this).data('id');
        let action = "changestatus";
        $.ajax({  
            type:'post',
            url:"Ajax.php",
            data:{uid:id,doAction:action},
            success:function(data){
               // console.log(data);
                location.reload(true); 
            }
        });
    });
</script>
