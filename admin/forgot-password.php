<?php
 include_once "../conn.php";
 if($_POST['doAction']=="recover"){ 
    $emailid = mysqli_real_escape_string($conn, $_POST['email']);
    $qtest = "select admin_id from admin where email='$emailid'";
    echo '<pre>'.$qtest.'</pre>';
	$q2=mysqli_query($conn, $qtest) or die(mysqli_error($conn));
	if(mysqli_num_rows($q2)>0){
        $rw=mysqli_fetch_assoc($q2);
        echo '<pre>'.$rw.'</pre>';
        $key = generateRandomString(12);
        $insstr = "update admin set resetkey='$key' where admin_id=$rw[admin_id]";
        echo '<pre>'.$insstr.'</pre>';
		$qq2=mysqli_query($conn, $insstr) or die(mysqli_error($conn));
        $subject = "Your Reset link for dashmesh admin portal";
		$resetlink = $mainurl.'admin/reset-password.php?rkey='.$key;
        send_mail($emailid,'',$subject,$resetlink,0,'forgot-password.php',"Password Reset Link Sent To Your Email Address");
    }else{
        //?msg=Password Reset Link Sent To Your Email Address
        echo '<script>window.location.href="forgot-password.php?errmsg=Invalid Email Address"</script>';
    }
 }

 if($_GET['msg']){
	$msg=$_GET['msg'];
}
if($_GET['errmsg']){
	$errmsg=$_GET['errmsg'];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Forgot Password? Enter your email and we will send you link to reset your password</title>
        <link rel="shortcut icon" href="assets/images/favicon.png" type="image/x-icon">   
        <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="assets/css/all.css">
        <link rel="stylesheet" type="text/css" href="assets/css/auth.css">
        <script type="text/javascript" src="assets/js/jquery-3.4.1.min.js"></script>
        <script type="text/javascript" src="assets/js/popper.min.js"></script>
        <script type="text/javascript" src="assets/js/bootstrap.js"></script>
        <script type="text/javascript" src="assets/js/script.js"></script>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    </head>
    <body>
        <div class="auth-wrapper">
            <div class="container-max px-0">
                <div class="row no-gutters">
                    <div class="col-lg-4">
                        <div class="auth-inner">
                            <div class="auth-content">
                                <div class="heading">
                                    <div class="logo">
                                        <img class="img-fluid" src="assets/images/vital-steel-bars-llp-logo.png">
                                    </div>
                                    <h5 class="text-center">Forgot Password?</h5>
                                </div>
                                <?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
			                    <?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
                                <form action="" method="post" auto-complete="off">
                                    <input type="hidden" name="doAction" value="recover">
                                    <div class="form-group form-elem-wrapper py-3 px-3 bg-success-light">
                                        <p class="text-center">Enter your email and we will send you instructions to reset your password</p>
                                    </div>
                                    <div class="form-group form-elem-wrapper">
                                        <label for="email">E-mail</label>
                                        <span class="form-elem-icon"><i class="bi bi-envelope"></i></span>
                                        <input class="form-control form-elem-input" type="email" name="email" id="email" placeholder="Enter Your Email Address">
                                        
                                    </div>
                                    <div class="text-center mt-4">
                                        <input class="btn btn-basic btn-lg" type="submit" name="reset" value="Reset Password">
                                    </div>
                                    <p class="text-center mt-3">Already have an account? <a href="index.php" class="text-link"><i class="bi bi-box-arrow-in-right"></i>&nbsp;&nbsp;Login Now</a></p>
                                </form>
                            </div>
                            <div class="footer">
                                <p>&copy;<span class="currentyear"></span> VSB<sup>&reg;</sup>. Developed by <a href="//ttcrobotronics.com">TTCR Pvt. Ltd.</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="auth-inner bg-wirerod d-none d-lg-flex">
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>