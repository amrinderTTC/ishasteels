<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Screen Locked - Enter Password to unlock</title>
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
                                    <h5 class="text-center">Screen Locked</h5>
                                    <p class="text-center">Enter your password to unlock the screen</p>
                                </div>
                                <div class="user-profile">
                                    <div class="profile-img">
                                        <img class="img-fluid" src="assets/images/profiles/profile-pic.png">
                                    </div>
                                    <h6>User Name</h6>
                                </div>
                                <form action="#" method="post">
                                    <input type="hidden" name="username">
                                    <div class="form-group form-elem-wrapper">
                                        <label for="password">Password</label>
                                        <span class="form-elem-icon"><i class="bi bi-lock"></i></span>
                                        <input class="form-control form-elem-input" type="password" name="password" id="password" placeholder="Enter Password">
                                    </div>
                                    <div class="text-center mt-4">
                                        <input class="btn btn-basic btn-lg" type="submit" name="unlock" value="Unlock">
                                    </div>
                                    <p class="text-center mt-3">Already have an account? <a href="#" class="text-link"><i class="bi bi-box-arrow-in-right"></i>&nbsp;&nbsp;Login Now</a></p>
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