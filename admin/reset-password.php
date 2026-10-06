<?php
 include_once "../conn.php";
    if(isset($_GET['rkey']) && !empty($_GET['rkey'])){
        $rkey=mysqli_real_escape_string($conn, $_GET['rkey']);
        if($_POST['doAction']=='Resetpass'){
            echo 'stage1';
            $password=mysqli_real_escape_string($conn, $_POST['password']);
            $cpassword=mysqli_real_escape_string($conn, $_POST['cpassword']);
            if(strcmp($password,$cpassword)===0){
                echo 'stage2';
                $rsql = "select admin_id from admin where resetKey='".$rkey."' limit 1";
                $rqq=mysqli_query($conn, $rsql) or die(mysqli_error($conn));

                if(mysqli_num_rows($rqq)>0){
                    echo 'stage3';
                    $rw=mysqli_fetch_assoc($rqq);
                    $nepass = "update admin set pass=md5('$password'),resetkey='' where admin_id=$rw[admin_id]";
                    //echo $nepass;
                    $qq2=mysqli_query($conn, $nepass) or die(mysqli_error($conn));
                    if(mysqli_affected_rows($conn)>0){
                        
                        echo '<script>window.location.href="index.php?msg=Password Reset Successfully.You can Login Now."</script>';
                    }
                }else{
                    echo '<script>window.location.href="forgot-password.php&errmsg=Invalid Reset Key. Please resend Link Again."</script>';    
                }
            }else{
                echo '<script>window.location.href="reset-password.php?rkey='.$rkey.'&errmsg=Input Passwords Dont Match."</script>';
            }
            
        }
    }else{
        echo '<script>window.location.href="index.php"</script>';
    }

if($_GET['msg']){
    $msg=$_GET['msg'];
}
if($_GET[errmsg]){
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
                                    <h5 class="text-center">Reset Your Password</h5>
                                </div>
                                <?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
			                    <?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
                                <form action="" method="post">
                                    <input type="hidden" name="doAction" value="Resetpass">
                                    <div class="form-group form-elem-wrapper">
                                        <label for="password">New Password</label>
                                        <span class="form-elem-icon"><i class="bi bi-lock"></i></span>
                                        <input class="form-control form-elem-input" type="password" name="password" id="password" placeholder="Enter New Password">
                                    </div>
                                    <div class="form-group form-elem-wrapper">
                                        <label for="cpassword">Confirm Password</label>
                                        <span class="form-elem-icon"><i class="bi bi-lock"></i></span>
                                        <input class="form-control form-elem-input" type="password" name="cpassword" id="cpassword" placeholder="Confirm Password">
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