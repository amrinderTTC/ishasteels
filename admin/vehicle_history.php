<?php
$perm=check_permission("A");

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

// if($_GET['typ']=='customer'){
//     $whr .=" and typ='cu'";
// }else if($_GET['typ']=='vendor'){
//     $whr .=" and typ='ve'";
// }

if(isset($_GET['date'])){
    if(!empty($_GET['date'])){
        $whr .=" and date(gate.createdon)='".$_GET['date']."'";
    }
}

if(isset($_GET['token'])){
    if(!empty($_GET['token'])){
        $whr .=" and gate.tokenid='".$_GET['token']."'";
    }
}
if(isset($_GET['vehicle'])){
    if(!empty($_GET['vehicle'])){
        $whr .=" and gate.vehicleno='".$_GET['vehicle']."'";
    }
}

$query = "SELECT gate.gid,gate.tokenid, gate.cid, gate.vehicleno, gate.vehicletype, gate.transport, gate.drivername,
gate.drivermobile, gate.efrom, gate.gatestatus, gate.chkinon, gate.chkinby, gate.chkouton,
gate.createdon, gate.createdby, gate.chkoutby, customers.`name` as partyname FROM gate 
INNER JOIN customers ON customers.cust_id = gate.cid where $whr";
$sql=$query;
$query.=" $orderby LIMIT $frm, $noprd";
//echo $query;
$qq = mysqli_query($conn,$query);
?>

<div class="main-content-inner">
    <div class="page-header">
        <h1 class="page-heading h6 ebold">All Vehicles</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">All Vehicles</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">All Vehicles</h5>
            <a href="main.php" class="btn btn-basic btn-sm">Dashboard</a>
        </div>
        <?php $whr=1;
            if(isset($_GET['date']) && !empty($_GET['date'])){
                $dt= mysqli_real_escape_string($conn,$_GET['date']);
                $whr .=" and gate.createdon='$dt' ";
            }

            if(isset($_GET['token']) && !empty($_GET['token'])&& is_numeric($_GET['token'])){
                $tok= mysqli_real_escape_string($conn,$_GET['token']);
                $whr .=" and gate.tokenid='$tok' ";
            }

            if(isset($_GET['vehicle']) && !empty($_GET['vehicle']) && is_numeric($_GET['vehicle'])){
                $veh= mysqli_real_escape_string($conn,$_GET['vehicle']);
                $whr .=  " and gate.vehicleno ='$veh' "; 
            }
        ?>
        <div class="article-content container-max">
            <div class="filter">
                <form action="" name="filter" method="get">
                    <input type="hidden" name="paction" value="vehicle_history">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="date">Date</label>

                                <select name="date" class="form-control">
                                    <option value="">Select Date</option>
                                    <option value="<?php echo date('Y-m-d',strtotime("-1 days")) ;?>" <?php echo (date('Y-m-d',strtotime("-1 days"))==$_GET['date']?'selected':'');?>><?php echo date('d-m-Y',strtotime("-1 days")) ;?></option>
                                    <option value="<?php echo date('Y-m-d') ?>" <?php echo (date('Y-m-d')==$_GET['date']?'selected':''); ?>><?php echo date("d-m-Y");?> </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="token">Token No.</label>
                                <input type="text" name="token" class="form-control"  value="<?php echo $_GET['token']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <div class="form-group">
                                <label for="vehicle">Vehicle No.</label>
                                <input type="text" name="vehicle" class="form-control"  value="<?php echo $_GET['vehicle']; ?>">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-12 col-sm-6">
                            <div class="form-group mt-4 pt-1">
                                <input type="submit" name="filter" class="btn btn-basic" value="Apply Filter">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered table-large">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Token No.</th>
                            <th>Vehicle No.</th>
                            <th>Party</th>
                            <th>Purpose</th>
                            <th>Checkin</th>
                            <th>Checkout</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if(mysqli_num_rows($qq)>0){
                            while($qrw = mysqli_fetch_assoc($qq)){
                                ?>
                            <tr>
                                <td><?php echo date('d-m-Y', strtotime($qrw['createdon']));?></td>
                                <td><?php echo $qrw['tokenid']; ?></td>
                                <td><?php echo $qrw['vehicleno']; ?></td>
                                <td><?php echo $qrw['partyname']; ?></td>
                                <td><?php
                                    if($qrw['efrom']=='1'){ 
                                        echo 'Material In';
                                    }elseif($qrw['efrom']=='2'){ 
                                        echo 'Material Out';
                                    }?></td>
                                <td><?php echo date('d-m-Y H:i:s', strtotime($qrw['createdon'])); ?></td>
                                <td><?php echo (!empty($qrw['chkouton'])?date('d-m-Y H:i:s', strtotime($qrw['chkouton'])):''); ?></td>
                                <td><a href="main.php?paction=vehicle_view&vid=<?php echo $qrw['gid']; ?>" class="btn action-btn btn-success"><i class="bi bi-eye"></i></a></td>
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
                        $pager = new PS_Pagination($conn, $sql, $noprd, 6, "paction=$_GET[paction]&date='$_GET[date]&token=$_GET[token]&vehicle=$_GET[vehicle]'");
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
    $(document).ready(function(){
        let date = new Date();
        let dd = date.getDate();
        if(dd < 10){
            dd = '0' + dd
        }
        let mm = date.getMonth() + 1;
        if(mm < 10){
            mm = '0' + mm
        }
        let yy = date.getFullYear();
        today = yy + '-' + mm + '-' + dd;
        yesterday = yy + '-' + mm + '-' + (+dd-1);
        $('#date').attr('min', yesterday);
        $('#date').attr('max', today);
        $('#date').attr('value', '');
    })
</script>
