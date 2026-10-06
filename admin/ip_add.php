<?php $perm=check_permission("A");  
//main.php?paction=unauthorize 
//     $ip =  $_SERVER['REMOTE_ADDR'];
//     $queryIp="SELECT * from allow_ip where ip='$ip'";
//     $qIp=mysqli_query($conn, $queryIp) or die(mysqli_error($conn));
//     $qlist=mysqli_fetch_array($qIp);
//   // var_dump($qlist['ip']); 
//     if($qlist['ip'] == $ip){
//         echo '<script>window.location.href="main.php?paction=unauthorize&errmsg=Ip address blocked"</script>';
//     }else{
        if(isset($_POST['add_ip'])){
            $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
            $cdate = $date->format('d-m-Y H:i:s');
            $userid = mysqli_real_escape_string($conn,$_POST['userid']);
            $ip=mysqli_real_escape_string($conn,$_POST['ip']);  
           $udetails="INSERT INTO `allow_ip`(`ip`, `created_at`, `updated_at`) VALUES ('$ip','$cdate','$cdate')"; 
            $inqq=mysqli_query($conn,$udetails);
            $errcd = mysqli_errno($conn);
            // var_dump($updatesql);
            // echo var_Dump($errcd);
            $msg = '';
            if($inqq){
                $msg ='Ip added Successfully';
                echo '<script>window.location.href="main.php?paction=ip-add&errmsg=Ip-added-Successfully"</script>';
            }else{
            //echo $udetails;
            $errmsg = 'Ip Not added failed';
            echo '<script>window.location.href="main.php?paction=ip-add&msg=Ip-Not-added-failed"</script>';    
            }
        } 
   // }
?>
<div class="main-content-inner">
    <?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
    <?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
    <?php if($msgv){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msgv?></div><?php }?>
    <?php
      if($perm){
    ?>
    <div class="page-header">
        <h1 class="page-heading ebold heading5">Add Ip</h1>
        <ul class="list-inline breadcrumb breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li> 
            <li class="breadcrumb-item">Add Ip</li>
        </ul>
    </div>
    <div class="page-content container-max">
        <div class="article">
            <div class="article-heading">  
            </div>
            <div class="article-content"> 
                <form class="form-light" action="" method="post"> 
                    <input type="hidden" name="doAction" value="add">
					<input type="hidden" name="uid" value="<?php echo $_GET['uid']; ?>" /> 
					<p>Your IP: <strong><?php echo $_SERVER['REMOTE_ADDR'];  ?></strong></p>
					<div class="row gy-4">
						<div class="col-xl-4 col-md-6 col-sm-6">
							<div class="form-group">
								<label for="ip">Allow IP <span class="text-danger">*</span></label>
								<input type="text" class="form-control text-uppercase" value="<?php echo $_SERVER['REMOTE_ADDR']; ?>" id="ip" name="ip" required />
							</div>
						</div>
						<div class="col-xl-2 col-md-6 col-sm-6">
							<div class="text-start mt-4">
								<input type="submit" name="add_ip" class="btn btn-basic" value="Submit">
							</div>
						</div>
					</div>
				</form>
                <div class="row gy-4 mt-4">
					<table class="table table-bordered table-striped table-hover">
                        <thead class="sticky-top bg-sec-500">
                            <tr>
                                <th>#</th>
                                <th>IP</th>
                                <th width="60"></th>
                            </tr>
						</thead>
						<?php 
						 if(isset($_GET['del_id'])){
						    //var_dump($_GET);die;
                            $date = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
                            $cdate = $date->format('d-m-Y H:i:s');
                            $del_id = $_GET['del_id']; 
					      	$ipsqldel="DELETE FROM `allow_ip` WHERE id = '$del_id'"; 
                            $inqqdel=mysqli_query($conn,$ipsqldel);
                            $msgv = '';
                            if($inqqdel){
                                $msgv = 'Ip removerd Successfully';
                                echo '<script>window.location.href="main.php?paction=ip-add&delmsg=Ip removed Successfully!"</script>';
                            }else{
                            //echo $udetails;
                            echo '<script>window.location.href="main.php?paction=ip-add&msg=Ip Not renoved failed"</script>';    
                            }
                        } 
						$ipsql="select * from `allow_ip`"; 
                        $ipqery=mysqli_query($conn,$ipsql);
						foreach ($ipqery as $k=>$v){ 
						//var_dump($v);
						?>
							<tr>
								<td><?php echo $k+1; ?></td>
								<td><?php echo $v['ip']; ?></td>
                                <td class="text-center"> 
                                   <!--<input type="hidden" name="del_id" value="">-->
                                   <a data-toggle="tooltip" title="Delete" href="main.php?paction=ip-add&del_id=<?php echo $v['id']; ?>"  class="btn action-btn btn-danger"><i class="bi bi-trash"></i></a> 
                                </td>
							</tr>
						<?php } ?>
					</table>
				</div>
            </div>
        </div>
    </div>
    <?php  } ?>
</div>