<?php

$perm=check_permission("A", "OF");

if(isset($_GET['contactd_id']) && is_numeric($_GET['contactd_id'])){
    
    if(isset($_GET['did']) and is_numeric($_GET['did'])){
        $delsql = "delete from customers where cust_id='$_GET[contactd_id]' and typ='cu'";
        
        $delq=mysqli_query($conn,$delsql) or die(mysqli_error($conn));
        if(mysqli_affected_rows($conn)>0){
            echo '<script>window.location.href="main.php?paction=contact_view&typ=customer&msg='.$_GET['typ'].' Deleted Successfully."</script>';
        }
    }
}
if($_GET['per_page']){
    $noprd=$_GET['per_page'];
}else{
    $noprd=15;
}

$orderby = "order by dt desc";
$cpage=$_REQUEST['page'];
if ($cpage == 0){
    $cpage=1;
    $cnt=1;
}else{
    $cnt=($cpage * $noprd) - $noprd + 1;
}

$frm=($cpage * $noprd) - $noprd;
$whr=1;

if(isset($_GET['scontactName'])){
    if(!empty($_GET['scontactName']) && is_numeric($_GET['scontactName'])){
        $whr .=" and cust_id='".$_GET['scontactName']."' ";
    }
}

if($_GET['typ']=='customer'){
    $whr .=" and typ='cu'";
}else if($_GET['typ']=='vendor'){
    $whr .=" and typ='ve'";
}

// $query = "SELECT customers.cust_id, customers.`name`, customers.email, customers.`status`, 
// customers.typ, countries.`name` as country, states.`name` as state
// FROM customers INNER JOIN countries ON countries.id = customers.country
// INNER JOIN states ON states.id = customers.state
// where $whr";
$query ="SELECT
customers.`name`,email,
customers.cust_id,state,
(select name from countries where id=customers.country) as country,
(select name from states where id = customers.state) as state,
(select cid from saleorder where cid=customers.cust_id limit 1) as custused
FROM
customers where $whr";
$sql=$query;
$query.=" $orderby LIMIT $frm, $noprd";
//echo $query;
$q=mysqli_query($conn, $query) or die(mysqli_error($conn));
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
        <h1 class="page-heading h6 ebold">All <?php echo(($_GET['typ']=='vendor')?'Vendors':'Customers')?></h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Manage Contacts</li>
            <li class="breadcrumb-item">All <?php echo(($_GET['typ']=='vendor')?'Vendors':'Customers')?></li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">All <?php echo(($_GET['typ']=='vendor')?'Vendors':'Customers')?></h5>
            <a href="main.php?paction=contact_add&typ=<?php echo ($_GET['typ']); ?>" class="btn btn-basic btn-sm">Add <?php echo(($_GET['typ']=='vendor')?'Vendor':'Customer')?></a>
        </div>

        <div class="article-content container-max">
            <div class="filter mb-4">
                <form action="" method="get">
                    <div class="row">
                        <div class="col-lg-10 col-sm-6">
                            <div class="form-group mb-4 mb-sm-0">
                            <?php
                                    $tt = ($_GET['typ']=='vendor'?'ve':'cu');
                                    $custlist = "select cust_id,name from customers where typ='$tt'";
                                    //echo $custlist;
                                ?>
                                <select class="form-control select2me" name="scontactName" id="scontactName">
                                    <option value="">Select <?php echo(($_GET['typ']=='vendor')?'Vendors':'Customer'); ?></option>
                                    <?php $custlistq=mysqli_query($conn, $custlist) or die(mysqli_error($conn));
                                        while($custrw=mysqli_fetch_assoc($custlistq)){
                                            echo '<option value="'.$custrw['cust_id'].'" ';
                                            if(isset($_GET['scontactName'])){
                                                if(!empty($_GET['scontactName']) && is_numeric($_GET['scontactName'])){
                                                    echo ($custrw['cust_id']==$_GET['scontactName']?'selected':'');
                                                }
                                            }
                                            echo ' >'.ucwords($custrw['name']).'</option>';
                                        } ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-sm-5">
                            <input type="hidden" name="paction" value="contact_view">
                            <input type="hidden" name="typ" value="<?php echo $_GET['typ']; ?>">
                            <button type="submit" class="btn btn-basic btn-sm">Apply Filter</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-large">
                    <thead>
                        <tr>
                            <th><?php echo(($_GET['typ']=='vendor')?'Vendors':'Customers')?> Name</th>
                            <th>E-mail</th>
                            <th>State/Country</th>
                            <!-- <th>Status</th> -->
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while($rw=mysqli_fetch_assoc($q)){
                        echo '<tr>
                            <td>'.ucwords($rw['name']).'</td>
                            
                            <td>'.$rw['email'].'</td>
                        
                            <td>'.ucwords($rw['state']).'/'.ucwords($rw['country']).'</td>';
                            // echo '<td class="text-center">';
                            // if($rw['admin_id']!='1'){
                            //     echo '<button data-toggle="tooltip" title="'.($rw['status']=='1'?'Disable':'Enable').'" data-id ="'.$rw['admin_id'].'" class="btn btn-default statusToggle '.($rw['status']=='1'?'active':'inactive').'"><i class="fa fa-toggle-'.($rw['status']=='1'?'on':'off').'"></i></button>';
                            // }
                            // echo '</td>';
                            echo '<td class="ws-nowrap">';
                            //if($rw['cust_id']!='1'){
                                if(check_permission("A")){ 
                                    echo '<a data-toggle="tooltip" title="Edit" href="main.php?paction=contact_add&typ='.$_GET['typ'].'&contact_id='.$rw['cust_id'].'"  class="btn action-btn btn-primary mr-1"><i class="bi bi-pencil-square"></i></a>';
                                }
                                    echo '<a data-toggle="tooltip" title="View Details" href="main.php?paction=contact_details&typ='.$_GET['typ'].'&contact_id='.$rw['cust_id'].'" class="btn action-btn btn-success"><i class="bi bi-eye"></i></a>';
                                if(check_permission("A")){ 
                                    if(empty($rw['custused'])){
                                        echo '<a data-toggle="tooltip" title="Delete" class="btn action-btn btn-danger" href="main.php?paction=contact_view&typ='.$_GET['typ'].'&contactd_id='.$rw['cust_id'].'&did=1"><i class="bi bi-trash"></i></a>';
                                    }
                                }
                            //}
                            echo '</td>
                        </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagging -->
                <div class="pagging">
                    <div class="right">
                        <?php
                        include('ps_pagination.php');
                        $pager = new PS_Pagination($conn, $sql, $noprd, 6, "paction=$_GET[paction]");
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
                console.log(data);
                location.reload(true); 
            }
        });
    });
</script>
