<?php
$perm=check_permission("A");
 
$query = "SELECT vehicleRemoved.gid,vehicleRemoved.tokenid, vehicleRemoved.cid, vehicleRemoved.vehicleno, vehicleRemoved.vehicletype, vehicleRemoved.transport, vehicleRemoved.drivername,
vehicleRemoved.drivermobile, vehicleRemoved.efrom, vehicleRemoved.gatestatus, vehicleRemoved.chkinon, vehicleRemoved.chkinby, vehicleRemoved.chkouton,admin.name as updateduser,
vehicleRemoved.modifiedon as Modate, vehicleRemoved.createdby, vehicleRemoved.chkoutby, customers.`name` as partyname FROM vehicleRemoved 
LEFT JOIN customers ON customers.cust_id = vehicleRemoved.cid LEFT JOIN admin ON admin.admin_id = vehicleRemoved.modifiedby";
$sql=$query;
// $query.=" $orderby LIMIT $frm, $noprd";
//echo $query;
$qq = mysqli_query($conn,$query);
 
$currentDate = date('Y-m-d');  
$twoDaysAgo = date('Y-m-d', strtotime('-10 days')); 
 
$deleteQuery = "DELETE FROM vehicleRemoved WHERE DATE(createdon) < '$twoDaysAgo'";
mysqli_query($conn, $deleteQuery);
?>

<div class="main-content-inner">
    <div class="page-header"> 
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li> 
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">All List</h5>
            <a href="main.php" class="btn btn-basic btn-sm">Dashboard</a>
        </div>
        <?php $whr=1;
            if(isset($_GET['date']) && !empty($_GET['date'])){
                $dt= mysqli_real_escape_string($conn,$_GET['date']);
                $whr .=" and vehicleRemoved.createdon='$dt' ";
            }

            if(isset($_GET['token']) && !empty($_GET['token'])&& is_numeric($_GET['token'])){
                $tok= mysqli_real_escape_string($conn,$_GET['token']);
                $whr .=" and vehicleRemoved.tokenid='$tok' ";
            }

            if(isset($_GET['vehicle']) && !empty($_GET['vehicle']) && is_numeric($_GET['vehicle'])){
                $veh= mysqli_real_escape_string($conn,$_GET['vehicle']);
                $whr .=  " and vehicleRemoved.vehicleno ='$veh' "; 
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
                            <th>Removed On:</th>
                            <th>Removed By: </th> 
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $cnt=1;
                        if(mysqli_num_rows($qq)>0){
                            while($qrw = mysqli_fetch_assoc($qq)){
                                ?>
                            <tr>
                                <td><?php echo $cnt;?></td>
                                <td><?php echo $qrw['tokenid']; ?></td>
                                <td><?php echo $qrw['vehicleno']; ?></td>
                                <td><?php echo $qrw['partyname']; ?></td>
                                <td><?php
                                    if($qrw['efrom']=='1'){ 
                                        echo 'Material In';
                                    }elseif($qrw['efrom']=='2'){ 
                                        echo 'Material Out';
                                    }?></td>
                                <td><?php echo date('d-m-Y H:i:s', strtotime($qrw['Modate'])); ?></td>
                                <td><?php echo $qrw['updateduser']; ?></td>  
                            </tr>
                            <?php $cnt++; }
                            }else{
                                echo '<tr><td colspan="8" class="text-center">No Record Found.</td></tr>';
                            } ?>
                    </tbody>
                </table>
            </div> 
        </div>
    </div>
</div>
 
