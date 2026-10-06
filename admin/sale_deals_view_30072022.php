<?php
$perm=check_permission("A");
if(isset($_GET['delslid']) && is_numeric($_GET['delslid'])){
    //first delete items from saleitems
    echo $did = $_GET['delslid'];
    $desql1 = "delete from dealitems where dealid=$did";
    mysqli_query($conn,$desql1);
    
    $de2sql = "delete from dealorder where slid=$did";
    mysqli_query($conn,$de2sql);
    echo '<script>window.location.href="main.php?paction=sale_deals_view&msg=Deal Deleted Successfully."</script>';
}

if($_GET[msg]){
	$msg=$_GET[msg];
}

if($_GET[errmsg]){
	$errmsg=$_GET[errmsg];
}
?>

<div class="main-content-inner">
<?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
    <?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
    <?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
    <?php if($perm){ ?>

    <div class="page-header">
        <h1 class="page-heading h6 ebold">Sale Deals</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Manage Deals</li>
            <li class="breadcrumb-item">Sale Deals</li>
        </ul>
    </div>

    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">Sale Deals</h5>
            <a href="main.php?paction=add_deal_order" class="btn btn-basic btn-sm">New Deal</a>
        </div>
        
        <div class="article-content container-max">
            <div class="filter">
                <form action="" name="filter" method="get">
                    <input type="hidden" name="paction" value="sale_deals_view">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="dealno">Deal No.</label>
                                <input type="text" name="dealno" id="dealno" class="form-control" value="<?php echo (!empty($_GET['dealno'])?$_GET['dealno']:''); ?>">
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
                                            echo '<option value="'.$crw['cust_id'].'"'.($crw['cust_id']==$_GET['partyname']?' selected':'').'>'.$crw['name'].'</option>';
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
                            <th>Deal Date</th>
                            <th>Deal No.</th>
                            <th>Party Name</th>
                            <th>Contact Person</th>
                            <th>Contact No.</th>
                            <th>Deal Qty.</th>
                            <th>Dispatched Qty.</th>
                            <th>Status</th>
                            <th>Added By</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if($_GET[per_page]){
                            $noprd=$_GET[per_page];
                        }else{
                            $noprd=15;
                        }
                        
                        $orderby = "order by dealorder.slid desc";
                        
                        $cpage=$_REQUEST['page'];
                        if ($cpage == 0){
                            $cpage=1;
                            $cnt=1;
                        }else{
                            $cnt=($cpage * $noprd) - $noprd + 1;
                        }
                        
                        $frm=($cpage * $noprd) - $noprd;
                        $whr=1;

                        
                        if($_GET['dealno']){
                            $orderno = mysqli_real_escape_string($conn, $_GET['dealno']);
                            $whr .= " and dealorder.`slid` = '$orderno' ";
                        }

                        if(isset($_GET['partyname']) && !empty($_GET['partyname'])){
                            $partyname = mysqli_real_escape_string($conn, $_GET['partyname']);
                            $whr .= " and  customers.cust_id = '$partyname'";
                        }
                        
                        
                        

                        // $query = "SELECT
                        // dealorder.slid,
                        // dealorder.cid,
                        // dealorder.cperson,
                        // dealorder.`status`,
                        // dealorder.orderqty  AS maxorderqty,
                        // (select sum(qtydispatched) from dealitems  where dealid=dealorder.slid group by dealid) AS ordereddispatched,
                        // dealorder.createdon,
                        // dealorder.createdby,
                        // contacts.cperson,
                        // contacts.contact as mobile,
                        // customers.`name` as partyname,
                        // admin.`user` as createdbyname
                        // FROM dealorder
                        // INNER JOIN customers ON customers.cust_id = dealorder.cid
                        // INNER JOIN admin ON admin.admin_id = dealorder.createdby
                        // INNER JOIN contacts ON contacts.cust_id = customers.cust_id where $whr  and ((DATE_ADD(DATE_ADD(DATE(dealorder.`markedcompleted`), INTERVAL 12 HOUR), INTERVAL 1 DAY) >= NOW()  and dealorder.`status`=1) or dealorder.`status`=0)";
                        $query = "SELECT
                        dealorder.slid,
                        dealorder.cid,
                        dealorder.cperson,
                        dealorder.`status`,
                        dealorder.orderqty  AS maxorderqty,
                        dealorder.dispatchedqty,
                        (select sum(dispatchqty) as qtydispatched from dealdispatch where dealid=dealorder.slid)  AS ordereddispatched,
                        dealorder.createdon,
                        dealorder.createdby,
                        contacts.cperson,
                        contacts.contact as mobile,
                        customers.`name` as partyname,
                        admin.`user` as createdbyname,
                        dealorder.markedcompleted
                        FROM dealorder
                        INNER JOIN customers ON customers.cust_id = dealorder.cid
                        INNER JOIN admin ON admin.admin_id = dealorder.createdby
                        INNER JOIN contacts ON contacts.cust_id = customers.cust_id where $whr  and ((DATE_ADD(DATE_ADD(DATE(dealorder.`markedcompleted`), INTERVAL 12 HOUR), INTERVAL 1 DAY) >= NOW()  and dealorder.`status`=1) or dealorder.`status`=0)";
                        $sql=$query;
                        $query.=" $orderby LIMIT $frm, $noprd";
                        
                        $qq = mysqli_query($conn,$query);
                        if(mysqli_num_rows($qq)>0){
                            while($rw=mysqli_fetch_assoc($qq)){
                            ?>
                            <tr>
                                <td><?php echo date('d-m-Y',strtotime($rw['createdon'])); ?></td>
                                <td><?php echo $rw['slid']; ?></td>
                                <td><?php echo strtoupper($rw['partyname']); ?></td>
                                <td><?php echo strtoupper($rw['cperson']);; ?></td>
                                <td><?php echo $rw['mobile'] ?></td>
                                <td><?php echo $rw['maxorderqty'];?> Tons</td>
                                <td><?php  echo (empty($rw['ordereddispatched'])?'0':$rw['ordereddispatched']);?> Tons</td>
                                <td><?php echo (!empty($rw['markedcompleted'])?'Completed':'Pending'); ?></td>
                                <td><?php echo strtoupper($rw['createdbyname']); ?></td>
                                <td>
                                    <a data-toggle="tooltip" title="View Details" href="main.php?paction=sale_deal_details&slid=<?php echo $rw['slid']; ?>" class="btn action-btn btn-success"><i class="bi bi-eye"></i></a>
                                    <?php if($rw['status']=='0'){ ?>
                                    <a data-toggle="tooltip" title="Edit" href="main.php?paction=edit_deal_order&slid=<?php echo $rw['slid']; ?>" class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>
                                    <?php if(empty($rw['slidused'])){ ?>
                                    <a data-toggle="tooltip" title="Delete" href="main.php?paction=sale_deals_view&delslid=<?php echo $rw['slid']; ?>" class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a>
                                    <?php } //$rw['slidused'] is empty
                                    }// if status is 0 ?>
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
