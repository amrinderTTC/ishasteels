<?php include_once "../conn.php"; 
if($_SESSION["sid"] == "Admin_loggedin"){
    header("location:main.php");
} else if($_POST['login']=='Log in'){
    $user=mysqli_real_escape_string($conn, $_POST['userName']);
    $pass=mysqli_real_escape_string($conn, $_POST['password']);

    $query="select * from admin where user='$user' and pass='".md5($pass)."' ";
    $q=mysqli_query($conn, $query) or die(mysqli_error($conn));
    if($rw=mysqli_fetch_array($q)){
        $_SESSION["sid"] = "Admin_loggedin";
		// cookie setup
		setcookie("user_id", $rw[admin_id]);
		setcookie("user_typ", $rw[admin_id]);
		$_SESSION["user_typ"] = $rw["typ"];
		$_SESSION["name"] = $rw["name"];
		$_SESSION["admin_id"]=$_SESSION["user_id"]=$_SESSION["uid"] = $rw[admin_id];
		$_SESSION["logged_user"] = $rw["user"];

		$qq="update admin set logindt=NOW() where admin_id='$rw[admin_id]' ";
		$qq=mysqli_query($conn, $qq) or die(mysqli_error($conn));

        if($_SESSION[url]!=""){
            $u=$_SESSION[url];
            $_SESSION[url]="";
            header("Location:$u");
            echo "<script>window.location.href='$u'</script>";
        }else{
            header("location:main.php");
            echo "<script>window.location.href='main.php'</script>";
        }

    }else{
        $errmsg="Invalid User / Password.";
    }
}

if($_GET[msg]){
    $msg=$_GET[msg];
}
if($_GET[errmsg]){
    $errmsg=$_GET[errmsg];
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Welcome Back! Sign in to Wire Rod Rolling Mill</title>
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
                                    <h5 class="text-center">Welcome Back!</h5>
                                    <p class="text-center">Sign in to continue with Vital Steel Bar LLP</p>
                                </div>
                                <?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
			                    <?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg; ?></div><?php }?>
                                <form action="" method="post" autocomplete="off">
                                    <div class="form-group form-elem-wrapper">
                                        <label for="username">Username</label>
                                        <span class="form-elem-icon"><i class="bi bi-person"></i></span>
                                        <input class="form-control form-elem-input" type="text" name="userName" id="userName" placeholder="Enter Username">
                                    </div>
                                    <div class="form-group form-elem-wrapper">
                                        <label for="password">Password</label>
                                        <span class="form-elem-icon"><i class="bi bi-lock"></i></span>
                                        <input class="form-control form-elem-input" type="password" name="password" id="password" placeholder="Enter Password">
                                    </div>
                                    <!-- <div class="form-group">
                                        <div class="form-elem-checkbox">
                                            <input class="form-elem-checkbox-input" type="checkbox" value="1" name="rememberme" id="rememberme">
                                            <span class="form-elem-checkbox-btn"></span> Remember me
                                        </div>
                                    </div> -->
                                    <div class="text-center mt-4">
                                        <input class="btn btn-basic btn-lg" type="submit" name="login" value="Log in">
                                    </div>
                                    <p class="text-center mt-3 text-link"><a href="forgot-password.php"><i class="bi bi-key"></i>&nbsp;&nbsp;Forgot your password?</a></p>
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