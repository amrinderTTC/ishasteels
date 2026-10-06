<?php
$perm=check_permission("A","OF");
$typ = $_GET['typ'];
if(!isset($typ) || empty($typ)){
    echo '<script>window.location.href="main.php"</script>';
}
$whr=1;
if($_GET['typ']=='vendor'){
    $typ = 'vendor';
    $typ2 = 'VE';
    $whr .=" and typ='ve'";
}elseif($_GET['typ']=='customer'){
    $typ = 'customer';
    $typ2 = 'CU';
    $whr .=" and typ='cu'";
}

if($_POST['doAction']=='add'){
    //var_Dump($_POST);
    $contactname= mysqli_real_escape_string($conn,$_POST['contactName']);
    $cemail= mysqli_real_escape_string($conn,$_POST['cemail']);
    $gst= mysqli_real_escape_string($conn,$_POST['gst']);
    $countery= mysqli_real_escape_string($conn,$_POST['country']);
    $state= mysqli_real_escape_string($conn,$_POST['states']);
    $city= mysqli_real_escape_string($conn,$_POST['city']);
    $pincode= mysqli_real_escape_string($conn,$_POST['pincode']);
    $address= mysqli_real_escape_string($conn,$_POST['address']);
    $usertype=$typ2;

    $contactperson= mysqli_real_escape_string($conn,$_POST['contactPerson']);
    $designation= mysqli_real_escape_string($conn,$_POST['designation']);
    $email= mysqli_real_escape_string($conn,$_POST['email']);
    $countrycode= mysqli_real_escape_string($conn,$_POST['countrycode']);
    $contactNumber= mysqli_real_escape_string($conn,$_POST['contactNumber']);

    //if(!empty($contactname)){
        $adduser  = "insert into customers set
        name='$contactname',
        email = '$cemail',
        country = '$countrycode',
        state = '$state',
        city = '$city',
        address ='$address',
        gst = '$gst',
        pincode = '$pincode',
        typ = '$usertype'";
        echo $adduser;
        
        $res1 = mysqli_query($GLOBALS["conn"], $adduser);
        $cust_id=mysqli_insert_id($GLOBALS["conn"]);
        //var_dump(mysqli_error($GLOBALS["conn"]));
    //}
    echo 'stage1';
    //var_dump(mysqli_error($GLOBALS["conn"]));
    if(mysqli_error($GLOBALS["conn"])){
        //var_Dump('Opps');
        echo '<script>window.location.href="main.php?paction=contact_view&typ='.$typ.'&errmsg=Something went wrong!"</script>';
        // if(mysqli_errno($GLOBALS["conn"])=='1062'){
        //     echo '<script>window.location.href="main.php?paction=contact_add&typ='.$typ.'&errmsg=User Name Already Taken! Please Select Another User Name."</script>';
        // }else{
        //     echo '<script>window.location.href="main.php?paction=contact_view&typ='.$typ.'&errmsg=Something went wrong!"</script>';
        // }
    
    }else{
        if(!empty($contactperson)){
            $addcontact="insert into contacts set
            cust_id = '$cust_id',
            cperson = '$contactperson',
            designation = '$designation',
            email = '$email',
            countrycode = '$countrycode',
            contact='$contactNumber',
            `prime`='1',
            createdon = '$createdon',
            createdby = '$createdby'";
            //echo '<br><br>';
            echo $addcontact;
            $res2 = mysqli_query($GLOBALS["conn"], $addcontact);
            //echo 'stage2';
            var_Dump(mysqli_error($GLOBALS["conn"]));
            if(mysqli_error($GLOBALS["conn"])){
                echo '<script>window.location.href="main.php?paction=contact_view&typ='.$typ.'&errmsg=User Created But Contact Deatils Were not Saved!"</script>';
            }else{
                echo '<script>window.location.href="main.php?paction=contact_view&typ='.$typ.'&msg='.$type.' created successfully!"</script>';
            }
        }else{
            echo 'success';
            echo '<script>window.location.href="main.php?paction=contact_view&typ='.$typ.'&msg='.$type.' created successfully!"</script>';
        }
    }
}elseif($_POST['doAction']=='edit'){
    //var_Dump($_POST);
    $custid =  mysqli_real_escape_string($conn,$_POST['aid']);
    $contactname= mysqli_real_escape_string($conn,$_POST['contactName']);
    // $username= mysqli_real_escape_string($conn,$_POST['username']);
    // $username = cleanstring($username);
    $cemail= mysqli_real_escape_string($conn,$_POST['cemail']);
    $gst= mysqli_real_escape_string($conn,$_POST['gst']);
    $countery= mysqli_real_escape_string($conn,$_POST['country']);
    $state= mysqli_real_escape_string($conn,$_POST['states']);
    $city= mysqli_real_escape_string($conn,$_POST['city']);
    $pincode= mysqli_real_escape_string($conn,$_POST['pincode']);
    $address= mysqli_real_escape_string($conn,$_POST['address']);
    $usertype=$typ2;
    $adduser  = "update customers set
    name='$contactname',
    email = '$cemail',
    country = '$countery',
    state = '$state',
    city = '$city',
    address ='$address',
    gst = '$gst',
    pincode='$pincode' 
    where cust_id='$custid'";
    //echo $adduser;
     $res1 = mysqli_query($GLOBALS["conn"], $adduser);
     //var_Dump(mysqli_error($GLOBALS["conn"]));
    //$admin_id=mysqli_insert_id($GLOBALS["conn"]);
    echo '<script>window.location.href="main.php?paction=contact_view&typ='.$typ.'&msg='.$type.' Updated successfully!"</script>';
    
}

if(isset($_GET['contact_id'])){
    if(is_numeric($_GET['contact_id'])){
        $whr .=" and cust_id='".$_GET['contact_id']."'";
        // $esquery = "SELECT customers.cust_id,customers.`name`,customers.`user`,customers.pass,customers.email,customers.mobile,customers.`status`,
        // customers.country,customers.state,customers.city, customers.address, customers.gst, customers.pincode, customers.typ FROM customers where $whr";
        $esquery = "SELECT customers.cust_id,customers.`name`,customers.email,customers.mobile,customers.`status`,
        customers.country,customers.state,customers.city, customers.address, customers.gst, customers.pincode, customers.typ FROM customers where $whr";
        $esres = mysqli_query($GLOBALS["conn"], $esquery);
        $editrow=mysqli_fetch_assoc($esres);
        //  var_Dump($esquery);
        // var_dump($editrow);
    }
}
// $q="select * from customers where cust_id='$_GET[admin_id]' ";
// $q=mysqli_query($GLOBALS["conn"], $q) or die (mysqli_error($GLOBALS["conn"]));
// if($editrow=mysqli_fetch_array($q)){}

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
        <?php if(empty($_GET['contact_id'])){ ?>
        <input type="hidden" id="cid" name="cid" value="1">
        <?php  } ?>
        <h1 class="page-heading ebold heading5"><?php echo($_GET['contact_id']?"Edit ":"Add "); echo($typ); ?> </h1>
        <ul class="list-inline breadcrumb breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Contacts</li>
            <li class="breadcrumb-item"><?php echo($_GET['contact_id']?"Edit ":"Add "); echo($typ);?> User</li>
        </ul>
    </div>
    
    <div class="page-content container-max">
        <div class="article">
            <div class="article-heading flex-heading">
                <h5 class="text-center"><?php echo($_GET['contact_id']?"Edit ":"Add "); echo($typ);?></h5>
                <a href="main.php?paction=users_view" class="btn btn-basic btn-sm">All <?php echo($typ);?></a>
            </div>
            <div class="article-content">
                <form action="" method="post">
                    <h6 class="bold text-uppercase mb-3"><?php echo($typ);?>&#39;s Basic Details</h6>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="contactName"><?php echo($typ);?> Name</label>
                                <input type="text" name="contactName" class="form-control" id="contactName" value="<?php echo $editrow['name'];?>" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="gst">GST</label>
                                <input type="text" name="gst" class="form-control" id="gst" value="<?php echo $editrow['gst'];?>">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <select name="country" class="form-control select2me" id="country" required>
                                    <option value="">Select Country</option>
                                    <?php $cnsql = "select id,name,phonecode from countries";
                                    $cnsqlq=mysqli_query($conn, $cnsql) or die (mysqli_error($conn));
                                    $cnsqlq2=mysqli_query($conn, $cnsql) or die (mysqli_error($conn));
                                     while($cnsqlr=mysqli_fetch_array($cnsqlq)){
                                         //echo '<option '.($cnsqlr['id']==$editrow['country']?'selected':'').' value="'.$cnsqlr['id'].'">'.ucwords($cnsqlr['name']).'</option>';
                                         //echo '<option value="'.$cnsqlr['id'].'" '.($cnsqlr['id']==$editrow['country']?'selected':'').'>'.ucwords($cnsqlr['name']).'</option>';
                                         echo '<option value="'.$cnsqlr['id'].'" ';
                                         if(!empty($editrow['country'])){
                                            echo ($cnsqlr['id']==$editrow['country']?'selected':'');
                                         }else{
                                            echo ($cnsqlr['id']=='101'?'selected':'');
                                         }
                                         
                                         
                                         echo '>'.ucwords($cnsqlr['name']).'</option>';
                                     } ?>  
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="states">State</label>
                                <select name="states" class="form-control select2me" id="states" required>
                                    <option value="">Select State</option>
                                    <?php
                                    if(isset($_GET['contact_id'])){
                                        $sstate = "select id,name from states where country_id = $editrow[country]";
                                        $sstateq=mysqli_query($conn, $sstate) or die (mysqli_error($conn));
                                        while($sstater=mysqli_fetch_array($sstateq)){
                                           //echo '<option '.($sstater['id']==$editrow['state']?'selected':'').' value="'.$sstater['id'].'">'.ucwords($sstater['name']).'</option>';
                                           echo '<option value="'.$sstater['id'].'" '.($sstater['id']==$editrow['state']?'selected':'').'>'.ucwords($sstater['name']).'</option>';
                                        }  
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="city">City</label>
                                <input type="text" name="city" class="form-control" id="city"  value="<?php echo $editrow['city'];?>"  required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pincode">Pin/Zip Code</label>
                                <input type="text" name="pincode" class="form-control" id="pincode"  value="<?php echo $editrow['pincode'];?>" >
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" name="address" class="form-control" id="address" value="<?php echo $editrow['address'];?>">
                            </div>
                        </div>
                        <!-- <div class="col-sm-6">
                            <div class="form-group">
                                <label for="username">User Name</label>
                                <input type="text" name="username" class="form-control" id="username" value="<?php #echo $editrow['user'];?>" required>
                            </div>
                        </div> -->
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="cemail">Company E-mail</label>
                                <input type="email" name="cemail" class="form-control" id="cemail" value="<?php echo $editrow['email'];?>">
                            </div>
                        </div>
                    </div>
                    <?php if(!isset($_GET['contact_id'])){ ?>
                    <h6 class="bold text-uppercase mt-4 mb-3"><?php echo($typ);?>&#39;s Contact Details</h6>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="contactPerson">Contact Person</label>
                                <input type="text" name="contactPerson" class="form-control" id="contactPerson" value="<?php echo $editrow['name'];?>" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="designation">Designation</label>
                                <input type="text" name="designation" class="form-control" id="designation" value="<?php echo $editrow['name'];?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="email">E-mail</label>
                                <input type="email" name="email" class="form-control" id="email" value="<?php echo $editrow['name'];?>">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="contactNumber">Contact Number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <select name="countrycode" class="form-control select2me">
                                        <option value="">Select Country Code</option>
                                    <?php while($cnsqlr2=mysqli_fetch_array($cnsqlq2)){
                                         echo '<option value="'.$cnsqlr2['id'].'" '.($cnsqlr2['id']=='101'?' selected':'').'>+'.ucwords($cnsqlr2['phonecode']).'</option>';
                                     } ?>  
                                    </select>
                                </div>
                                <input type="number" min="0" name="contactNumber" class="form-control" id="contactNumber" value="<?php echo $editrow['name'];?>">
                            </div>
                        </div>
                        
                    </div>
                    <?php } ?>
                    <div class="btns text-right mt-4">
                        <input type="hidden" name="paction" value="contact_add">
                        
                        <input type="hidden" name="doAction" value="<?php echo ($_GET['contact_id']!=""?"edit":"add")?>" />
                        <input type="hidden" name="aid" value="<?php echo $editrow['cust_id']; ?>">
                        <button type="submit" name="submt_btn" value="1" class="btn btn-basic"><i class="fa fa-check"></i> 
                        <?php echo ($_GET['contact_id']!=""?"Update":"Add")?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php }?>
</div>
<script type="text/javascript">
     let cid = $('#cid').val();
        if(cid == '1'){
           // alert(cid);
           $(document).ready(function(){
             $('#country').trigger('change');
        });
    }
	$('#country').change(function(e){
		e.preventDefault();
		var country_id=$(this).val();
        console.log(country_id);
		$.ajax({
            url: "Ajax.php",
            type:"Post",
            data:{'typs':'S','countryid':country_id},
			success: function(data){
                $('#states').html(data);
                console.log(data);
			}
		});
	});

	// $('#state').change(function(e){
	// 	e.preventDefault();

	// 	// var state_id=$(this).val();
	// 	// //alert(state_id);
		
	// 	// $.ajax({
    //     //     url: "Ajax.php";
    //     //     typs=CI&state_id="+state_id, 
	// 	// 	success: function(result){
	// 	// 		//alert(JSON.stringify(result));
	// 	// 		//$("#div_city").val(result);
	// 	// 		$("#div_city").html(result);
	// 	// 	}
	// 	// });
	// });
</script>