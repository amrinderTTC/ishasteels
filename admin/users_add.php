<?php
$perm=check_permission("A");

if($_POST['doAction']){ 
    $password= mysqli_real_escape_string($conn,$_POST['password']);
    $name= mysqli_real_escape_string($conn,$_POST['name']);
    $user= mysqli_real_escape_string($conn,$_POST['userName']);
    $mobile= mysqli_real_escape_string($conn,$_POST['contact']);
    $email= mysqli_real_escape_string($conn,$_POST['email']);
    $dept= mysqli_real_escape_string($conn,$_POST['dept']);
    
    if($_POST['password']){
		$pass=", pass='".md5($password)."' ";
	}
        $fields= " admin set
		name='$name',
		mobile='$mobile',
		email='$email',
		user='$user',
		typ='$dept'
		$pass
	";
    
	if($_POST['doAction']=="add"){
	    
		$add= "insert into $fields ";
		print_r($add);
        $res1 = mysqli_query($GLOBALS["conn"], $add);
		$admin_id=mysqli_insert_id($GLOBALS["conn"]);
        if(mysqli_error($GLOBALS["conn"])){ // if there some error in mysql
            if(mysqli_errno($GLOBALS["conn"])=='1062'){
                $errmsg="Please Try Entering Another UserName";        
            }else{
                echo '<script>window.location.href="main.php?paction=users_add&errmsg=Something went wrong!"</script>';
            }
        }else{
            echo '<script>window.location.href="main.php?paction=users_add&msg=Record Added Successfully"</script>';	
        }
	}elseif($_POST['doAction']=="edit"){
	    $admnId = $_GET['admin_id'];
		$add= "update $fields where admin_id='$admnId'";
		$res1 = mysqli_query($GLOBALS["conn"], $add) or die (mysqli_error($GLOBALS["conn"]));
		$admin_id=$_GET['admin_id'];
        if(mysqli_error($GLOBALS["conn"])){ // if there some error in mysql
            if(mysqli_errno($GLOBALS["conn"])=='1062'){ 
                echo '<script>window.location.href="main.php?paction=users_add&errmsg=Please Try Entering Another UserName"</script>';
            }else{
                echo '<script>window.location.href="main.php?paction=users_add&errmsg=Something went wrong!"</script>';
            }
        }else{
            $msg="Record Updated Successfully";
            echo '<script>window.location.href="main.php?paction=users_add&msg=Record Added Successfully"</script>';
        }
	}
}
$admnId = $_GET['admin_id'];
$q="select * from admin where admin_id='$admnId'";
$q=mysqli_query($GLOBALS["conn"], $q) or die (mysqli_error($GLOBALS["conn"]));
if($editrow=mysqli_fetch_array($q)){}
?>

<div class="main-content-inner">
    <div class="page-header">
        <h1 class="page-heading ebold heading5"><?php echo($_GET['admin_id']?"Edit":"Add")?> User</h1>
        <ul class="list-inline breadcrumb breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Contacts</li>
            <li class="breadcrumb-item"><?php echo($_GET['admin_id']?"Edit":"Add")?> User</li>
        </ul>
    </div>
    <?php if(!$perm){ $errmsg="You are not authorized to view this section.";}?>
    <?php if($errmsg){ ?><div class="alert alert-danger"><strong>Oh snap!</strong> <?php echo $errmsg;?></div><?php } ?>
    <?php if($msg){?><div class="alert alert-success display-show"><button class="close" data-close="alert"></button><?php echo $msg?></div><?php }?>
    <?php
    if($perm){
    ?>
    <div class="page-content container-max">
        <div class="article">
            <div class="article-heading flex-heading">
                <h5 class="text-center"><?php echo($_GET['admin_id']?"Edit":"Add")?> User</h5>
                <a href="main.php?paction=users_view" class="btn btn-basic btn-sm">All User</a>
            </div>
            <div class="article-content">
                <form action="#" method="post">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">Employee Name</label>
                                <input type="text" name="name" class="form-control" id="name" value="<?php echo $editrow['name'];?>" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="contact">Contact Number</label>
                                <input type="number" name="contact" class="form-control" id="contact" min="0" value="<?php echo $editrow['mobile'];?>" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" class="form-control" id="email" value="<?php echo $editrow['email'];?>" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="userName">User Name</label>
                                <input type="text" name="userName" class="form-control" id="userName"  value="<?php echo $editrow['user'];?>" <?php echo ($_GET['admin_id']!=""?"readonly":"")?> required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control" id="password">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="dept">Department</label>
                                <select name="dept" class="form-control" id="dept" required>
                                    <option value="">Select User Type</option>
                                    <option value="A" <?php echo($editrow['typ']=="A"?"selected":"");?>>Admin</option>
                                    <option value="GT" <?php echo($editrow['typ']=="GT"?"selected":"");?>>Gate User</option>
                                    <option value="WT" <?php echo($editrow['typ']=="WT"?"selected":"");?>>Weight User</option>
                                    <option value="OF" <?php echo($editrow['typ']=="OF"?"selected":"");?>>Order Feeding</option>
                                    <option value="DP" <?php echo($editrow['typ']=="DP"?"selected":"");?>>Dispatch</option>
                                    <option value="SK" <?php echo($editrow['typ']=="SK"?"selected":"");?>>Store Keeping</option>
                                    <option value="AC" <?php echo($editrow['typ']=="AC"?"selected":"");?>>Account</option>
                                    <option value="MN" <?php echo($editrow['typ']=="MN"?"selected":"");?>>Manager</option>
                                    <option value="OD" <?php echo($editrow['typ']=="OD"?"selected":"");?>>Order Management</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="btns text-right mt-4">
                        <input type="hidden" name="paction" value="users_add">
                        <input type="hidden" name="doAction" value="<?php echo ($_GET['admin_id']!=""?"edit":"add")?>" />
                        <button type="submit" name="submt_btn" value="1" class="btn btn-basic"><i class="fa fa-check"></i> 
                        <?php echo ($_GET['admin_id']!=""?"Update":"Add")?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php }?>
</div>
