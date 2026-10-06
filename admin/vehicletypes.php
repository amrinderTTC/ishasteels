<?php
$perm=check_permission("A");

// Store record to database table
if($_GET['doAction']=='create'){ //new record
    $vtype = mysqli_real_escape_string($conn, $_GET['vehicletype']);
    $vtype=htmlentities($vtype);
    $insql = 'insert into vehicletype set vtname="'.$vtype.'"';
    $insqlq=mysqli_query($conn, $insql) or die(mysqli_error($conn));
    if(mysqli_insert_id($conn)>0){
        echo '<script>window.location.href="main.php?paction=vehicletypes&msg=New Vehicle Type Creted Successfully."</script>';
    }else{
        echo '<script>window.location.href="main.php?paction=vehicletypes&errmsg=Something Went Wrong."</script>';
    }
}elseif($_GET['doAction']=='update'){
    $vid = mysqli_real_escape_string($conn, $_GET['vtid']);
    $vtype = mysqli_real_escape_string($conn, $_GET['vehicletype']);
    $usql = 'update vehicletype set vtname="'.$vtype.'" where vtid="'.$vid.'"';
    $usqlq=mysqli_query($conn, $usql) or die(mysqli_error($conn));
    echo '<script>window.location.href="main.php?paction=vehicletypes&msg=Vehicle Type Updated Successfully."</script>';
}

// Get selected vehicle type for update
if(isset($_GET['vetypeid'])){
    if(!empty($_GET['vetypeid']) && is_numeric($_GET['vetypeid'])){
        $vid=mysqli_real_escape_string($conn, $_GET['vetypeid']);
        $editsql = "select * from vehicletype where vtid=$vid";
        $esqlq=mysqli_query($conn, $editsql) or die(mysqli_error($conn));        
        $erw=mysqli_fetch_assoc($esqlq);
    }else{
        echo '<script>window.location.href="main.php?paction=vehicletypes"</script>';
    }
}elseif(isset($_GET['vdtypeid'])){
    if(!empty($_GET['vdtypeid']) && is_numeric($_GET['vdtypeid'])){
        $vid = mysqli_real_escape_string($conn, $_GET['vdtypeid']);
        /**
         * THIS STAGE IS PENDING
         * first check if vehicle type is not used for any entry at gate section 
         * only if above section is not satisfied then delete record else show error 
         * 
         */
        $dsql = "delete from vehicletype where vtid=$vid";
        $dsqlq=mysqli_query($conn, $dsql) or die(mysqli_error($conn));        
        if(mysqli_affected_rows($conn)>0){
            echo '<script>window.location.href="main.php?paction=vehicletypes&msg=Vehicle type deleted Successfully"</script>';
        }
    }
}

// Get all records from database table
if($_GET[per_page]){
    $noprd=$_GET[per_page];
}else{
    $noprd=15;
}
$orderby = " order by vid desc";
$cpage=$_REQUEST['page'];
if ($cpage == 0){
    $cpage=1;
    $cnt=1;
}else{
    $cnt=($cpage * $noprd) - $noprd + 1;
}
$frm=($cpage * $noprd) - $noprd;

$showsql = "select * from vehicletype";
$sql = $showsql;
$showsqlq=mysqli_query($conn, $showsql) or die(mysqli_error($conn));

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
    <?php if($perm){ ?>
    <div class="page-header">
        <h1 class="page-heading h6 ebold">Vehicles</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Gate</li>
            <li class="breadcrumb-item">Vehicle Types</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">All Vehicle Types</h5>
        </div>
        <div class="article-content container-max">
            <form action="" method="get" autocomplete="off">
                <input type="hidden" name="paction" value="vehicletypes">
                <input type="hidden" name="doAction" value="<?php echo(($_GET['vetypeid'])?'update':'create'); ?>">
                <?php if(isset($_GET['vetypeid'])){ ?>
                    <input type="hidden" name="vtid" value="<?php echo $erw['vtid']; ?>">
                <?php } ?>
                <div class="input-group">
                    <input type="text" name="vehicletype" id="vehicletype" class="form-control" value="<?php echo (!empty($_GET['vetypeid'])?$erw['vtname']:'') ?>">
                    
                    <div class="input-group-append"><input type="submit" class="btn btn-basic" value="<?php echo(($_GET[vetypeid])?'Update List':'Add To List'); ?>"></div>
                </div>
            </form>
            <div class="table-responsive mt-4">
                <table class="table table-bordered table-big">
                    <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Vehicle Type</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($srw=mysqli_fetch_assoc($showsqlq)){ ?>
                        <tr>
                            <td><?php echo $cnt; ?> </td>
                            <td><?php echo ucwords($srw['vtname']); ?></td>
                            <td>
                                <a class="btn btn-danger action-btn" href="main.php?paction=vehicletypes&vdtypeid=<?php echo $srw['vtid'] ?>"><i class="bi bi-trash"></i></a>
                                <a class="btn btn-primary action-btn" href="main.php?paction=vehicletypes&vetypeid=<?php echo $srw['vtid'] ?>"><i class="bi bi-pencil"></i></a>
                            </td>
                        </tr>
                        <?php $cnt++;} ?>
                    </tbody>
                </table>
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
