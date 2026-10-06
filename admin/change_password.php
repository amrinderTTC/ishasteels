<?php #var_dump($_POST);
//var_dump($_SESSION);
$perm=check_permission("A","GT");
$uid = $_SESSION['user_id'];
$oldpw = mysqli_real_escape_string($conn,$_POST['currentPassword']);
if($_POST['submit']){
    $sql = "select count(*) from admin where admin_id='$uid' and pass='".md5($pass)."' ";
    $q=mysqli_query($conn, $sql) or die(mysqli_error($conn));
    $nr = mysqli_num_rows($q);
    if($nr>0){
        $newpw = mysqli_real_escape_string($conn,$_POST['password']);
        $newpw2 = mysqli_real_escape_string($conn,$_POST['cPassword']);
        $status = strcmp($newpw,$newpw2); // if status is 0 then 2 strings match;
        $usql = "update admin set pass='".md5($newpw)."' where admin_id='$uid'";
        $uq=mysqli_query($conn, $usql) or die(mysqli_error($conn));
        $stat = mysqli_affected_rows($conn);
        if($stat>0){
            $msg = "Password Updated Successfully";
            echo "<script>window.location.href='main.php?paction=change_password&msg=$msg'</script>";
        }else{
            $errmsg = "Something went wrong. Please try again.";
            echo "<script>window.location.href='main.php?paction=change_password&errmsg=$errmsg'</script>";
        }
    }
}

if($_GET['msg']){
	$msg=$_GET['msg'];
}
if($_GET['errmsg']){
	$errmsg=$_GET['errmsg'];
}
?>

<div class="main-content-inner">
    <div class="page-header">
        <h1 class="page-heading heading6 ebold">Change Password</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">User Profile</li>
            <li class="breadcrumb-item">Change Password</li>
        </ul>
    </div>
    <?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
    <?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
    <?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
    <?php
        if($perm){
    ?>
    <div class="page-content container-max">
        <div class="login-container mx-auto">
            <div class="article login-content pb-4">
                <form action="#" method="post" class="pt-3" id="changePassword">
                    <h5 class="text-center bold mb-4">Change Password</h5>
                    <div class="form-group">
                        <label for="currentPassword">Current Password</label>
                        <input type="text" name="currentPassword" id="currentPassword" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>
                    <div class="form-group mb-2">
                        <label for="cPassword">Confirm Password</label>
                        <input type="password" name="cPassword" id="cPassword" class="form-control" required>
                    </div>
                    <p class="text-right text-link"><a href="forgot-password.php">Forgot Pasword?</a></p>
                
                    <input type="submit" name="submit" value="Change Password" class="btn btn-basic">
                </form>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
						
<script>
	$(document).on('click','.statusToggle',function(){
        $(this).toggleClass('active inactive');
        $(this).children('.fa').toggleClass('fa-toggle-on fa-toggle-off');
    });
</script>