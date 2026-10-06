<?php

$perm=check_permission("A");

if(!isset($_GET['contact_id']) || empty($_GET['contact_id'])){
    echo '<script>window.location.href="main.php"</script>';
}

$coid=mysqli_real_escape_string($conn,$_GET['contact_id']);

if($_GET['typ']=='customer'){
    $whr .=" and typ='cu'";
}else if($_GET['typ']=='vendor'){
    $whr .=" and typ='ve'";
}

if($_POST['doAction']=="updatecontact"){
    $contactperson= mysqli_real_escape_string($conn,$_POST['contactPerson']);
    $designation= mysqli_real_escape_string($conn,$_POST['designation']);
    $email= mysqli_real_escape_string($conn,$_POST['email']);
    $countrycode= mysqli_real_escape_string($conn,$_POST['countrycode']);
    $contactNumber= mysqli_real_escape_string($conn,$_POST['contactNumber']);
    $cid= mysqli_real_escape_string($conn,$_POST['cid']);
    $cust_id= mysqli_real_escape_string($conn,$_POST['cust_id']);

    $updatesql = "update contacts set 
    cust_id='$cust_id',
    cperson='$contactperson',
    designation='$designation',
    email='$email',
    countrycode='$countrycode',
    contact='$contactNumber'
    where cid='$cid' and cust_id='$cust_id'";
    //echo $updatesql;
    $uq=mysqli_query($conn, $updatesql) or die(mysqli_error($conn));
    $urw=mysqli_fetch_assoc($uq);
    //if(mysqli_affected_rows($conn)>0){
        //echo '<script>window.location.href="main.php?paction=contact_details&contact_id='.$coid.'&typ='.$_GET['typ'].'&editcontact='.$rw2['cid'].'"</script>';
    echo '<script>window.location.href="main.php?paction=contact_details&contact_id='.$coid.'&typ='.$_GET['typ'].'&msg=Contact Updates Successfully"</script>';
    //}
}else if($_POST['doAction']=='addcontent'){
    //var_dump($_POST);
    $contactperson= mysqli_real_escape_string($conn,$_POST['contactPerson']);
    $designation= mysqli_real_escape_string($conn,$_POST['designation']);
    $email= mysqli_real_escape_string($conn,$_POST['email']);
    $countrycode= mysqli_real_escape_string($conn,$_POST['countrycode']);
    $contactNumber= mysqli_real_escape_string($conn,$_POST['contactNumber']);
    $cid= mysqli_real_escape_string($conn,$_POST['cid']);
    $cust_id= mysqli_real_escape_string($conn,$_POST['contact_id']);
    if(!empty($contactperson)){
        $inssql = "insert into contacts set 
        cust_id='$cust_id',
        cperson='$contactperson',
        designation='$designation',
        email='$email',
        countrycode='$countrycode',
        contact='$contactNumber',
        createdon = '$createdon',
        createdby = '$createdby'";
        $uq=mysqli_query($conn, $inssql) or die(mysqli_error($conn));
        $conid = mysqli_insert_id($conn);
        if ($conid>0){
            echo '<script>window.location.href="main.php?paction=contact_details&contact_id='.$coid.'&typ='.$_GET['typ'].'&msg=New Contact Created Successfully"</script>';
        }else{
            echo '<script>window.location.href="main.php?paction=contact_details&contact_id='.$coid.'&typ='.$_GET['typ'].'&errmsg=Something Went Wrong"</script>';
        }
    }else{
        echo '<script>window.location.href="main.php?paction=contact_details&contact_id='.$coid.'&typ='.$_GET['typ'].'&errmsg=Nothing to add.Atleast Add Contact Person to save new Contact Person."</script>';
    }
}




// $query = "SELECT countries.`name` as country, states.`name` as state,admin.`user`, admin.email, 
// admin.`name`, admin.cust_id, admin.`status`, admin.city, admin.address, admin.gst,
// admin.pincode, admin.typ FROM admin INNER JOIN states ON states.id = admin.state
// INNER JOIN countries ON countries.id = admin.country
// where admin_id = $coid";

$query = "SELECT
states.`name` as `state`,
countries.`name` as `country`,
customers.`name`,
customers.email,
customers.mobile,
customers.`status`,
customers.city,
customers.address,
customers.gst,
customers.pincode,
customers.typ
FROM
customers
INNER JOIN states ON states.id = customers.state
INNER JOIN countries ON countries.id = customers.country
where cust_id = $coid";
//echo $query;
$q=mysqli_query($conn, $query) or die(mysqli_error($conn));
$rw=mysqli_fetch_assoc($q);
 //var_Dump($rw);
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
    <?php if($perm){  ?>
    <div class="page-header">
        <h1 class="page-heading heading6 ebold"><?php echo(($_GET['typ']=='vendor')?'Vendors':'Customers')?> Details</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Manage Contacts</li>
            <li class="breadcrumb-item"><?php echo(($_GET['typ']=='vendor')?'Vendor':'Customer')?> Details</li>
        </ul>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="article">
                <div class="article-heading flex-heading">
                    <h5 class="text-center"><?php echo(($_GET['typ']=='vendor')?'Vendor':'Customer')?>&#39;s Basic Details</h5>
                    <a href="main.php?paction=contact_add&typ=customer&contact_id=<?php echo $coid; ?>" class="btn btn-basic btn-sm"><i class="bi bi-pencil-square"></i> <?php echo(($_GET['typ']=='vendor')?'Vendor':'Customer')?></a>
                </div>
                <div class="article-content">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th><?php echo(($_GET['typ']=='vendor')?'Vendors':'Customers')?> Name</th>
                                <td><?php echo ucwords($rw['name']); ?></td>
                            </tr>
                            <tr>
                                <th>GST No.</th>
                                <td><?php echo strtoupper($rw['gst']); ?></td>
                            </tr>
                            <!-- <tr>
                                <th>User Name</th>
                                <td><?php #echo $rw['user'];?></td>
                            </tr> -->
                            <tr>
                                <th>Address</th>
                                <td><?php echo ucwords($rw['address']);?> </td>
                            </tr>
                            <tr>
                                <th>City</th>
                                <td><?php echo ucwords($rw['city']); ?></td>
                            </tr>
                            <tr>
                                <th>Pin Code</th>
                                <td><?php echo strtoupper($rw['pincode']); ?></td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td><?php echo ucwords($rw['state']); ?></td>
                            </tr>
                            <tr>
                                <th>Country</th>
                                <td><?php echo ucwords($rw['country']); ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="article mt-4">
                <div class="article-heading flex-heading">
                    <h5 class="text-center"><?php echo(($_GET['typ']=='vendor')?'Vendor':'Customer')?>&#39;s Contact Details</h5>
                </div>
                <div class="article-content">
                    <div class="table-responsive">
                        <table class="table table-bordered table-med">
                            <thead>
                                <tr>
                                    <th>Contact Person</th>
                                    <th>Designation</th>
                                    <th>Contact No.</th>
                                    <th>E-mail</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                    <?php
                                        $query2 = "SELECT contacts.cid,countries.phonecode,contacts.cperson,contacts.designation,contacts.cust_id,
                                        contacts.cid,contacts.email,contacts.contact,contacts.createdon,contacts.createdby FROM contacts
                                        INNER JOIN countries ON countries.id = contacts.countrycode where cust_id = $coid";
                                        $q2=mysqli_query($conn, $query2) or die(mysqli_error($conn));
                                        while($rw2=mysqli_fetch_assoc($q2)){
                                    ?>
                                <tr>
                                    <td><?php echo ucwords($rw2['cperson']); ?></td>
                                    <td><?php echo ucwords($rw2['designation']); ?></td>
                                    <td><?php 
                                    if(!empty($rw2['contact'])){
                                        echo '+'.$rw2['phonecode'].'-'.$rw2['contact'];
                                    }
                                     ?></td>

                                    <td><?php echo $rw2['email']; ?></td>
                                    <td><a href="main.php?paction=contact_details&contact_id=<?php echo $coid; ?>&typ=<?php echo $_GET['typ']; ?>&editcontact=<?php echo $rw2['cid']; ?>" class="btn btn-primary action-btn"><i class="bi bi-pencil-square"></i></a></td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="article">
                <div class="article-heading flex-heading">
                    <h5 class="text-center"><?php echo(($_GET['editcontact'])?'Edit':'Add New')?> Contact</h5>
                </div>
                <div class="article-content">
                    <?php
                        if(isset($_GET['editcontact'])){
                            if(empty($_GET['editcontact']) || !is_numeric($_GET['editcontact'])){
                                echo '<script>window.location.href="main.php?paction=contact_details&contact_id='.$coid.'&typ='.$_GET['typ'].'&editcontact='.$rw2['cid'].'"</script>';
                            }
                            $sql3="select * from contacts where cust_id=$coid and cid=$_GET[editcontact]";
                            $q3=mysqli_query($conn, $sql3) or die(mysqli_error($conn));
                            $rw3=mysqli_fetch_assoc($q3);
                        }
                    ?>
                    <form action="" method="post">
                        <input type="hidden" name="paction" value="contact_details">
                        <input type="hidden" name="contact_id" value="<?php echo (!empty($rw3['cust_id'])?$rw3['cust_id']:$_GET['contact_id']); ?>">
                        <input type="hidden" name="typ" value="<?php echo $_GET['typ']; ?>">
                        <input type="hidden" name="editcontact" value="<?php echo $rw3['cid']; ?>">
                        <input type="hidden" name="doAction" value="<?php echo (isset($_GET['editcontact'])?'updatecontact':'addcontent'); ?>">
                        <div class="form-group">
                            <label for="contactPerson">Contact Person</label>
                            <input type="text" name="contactPerson" id="contactPerson" class="form-control" value="<?php echo $rw3['cperson']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="designation">Designation</label>
                            <input type="text" name="designation" id="designation" class="form-control" value="<?php echo $rw3['designation']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" name="email" id="email" class="form-control"  value="<?php echo $rw3['email']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="contactNumber">Contact Number</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <select name="countrycode" class="form-control select2me">
                                    <option value="" disabled>Contry Code</option>
                                    <?php 
                                    $cnsql = "select id,name,phonecode from countries";
                                    $cnsqlq2=mysqli_query($conn, $cnsql) or die (mysqli_error($conn));
                                    while($cnsqlr2=mysqli_fetch_array($cnsqlq2)){
                                         echo '<option value="'.$cnsqlr2['id'].'" ';
                                         if(!isset($_GET['editcontact'])){
                                            echo ($cnsqlr2['id']==101?'selected':'');
                                         }else{
                                            echo ($cnsqlr2['id']==$rw3['countrycode']?'selected':'');
                                         }
                                        echo '>+'.ucwords($cnsqlr2['phonecode']).'</option>';
                                     } ?>  
                                       
                                    </select>
                                </div>
                                <input type="number" name="contactNumber" id="contactNumber" class="form-control"  value="<?php echo (!empty($rw3['contact'])?$rw3['contact']:''); ?>">
                            </div>
                        </div>
                        <div class="text-right">
                            <input type="hidden" name="cid" value="<?php echo $rw3['cid']; ?>">
                            <input type="hidden" name="cust_id" value="<?php echo $rw3['cust_id']; ?>">
                            <input type="submit" name="contactSubmit" class="btn btn-basic btn-sm" value="<?php echo(($_GET['editcontact'])?'Update Contact':'Add To Contact')?>">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php } ?>
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
</script>
