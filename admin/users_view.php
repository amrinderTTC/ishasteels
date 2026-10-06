<?php

$perm=check_permission("A");

if($_GET['per_page']){
    $noprd=$_GET['per_page'];
}else{
    $noprd=15;
}
//$orderby = "order by createdon desc";
$cpage=$_REQUEST['page'];
if ($cpage == 0){
    $cpage=1;
    $cnt=1;
}else{
    $cnt=($cpage * $noprd) - $noprd + 1;
}
$frm=($cpage * $noprd) - $noprd;
$whr=1;
$whr .=" and typ<>'VE' and typ<>'CU' ";
$query = "select admin_id,name,user,email,mobile,status,typ from admin where $whr";
$sql=$query;
$q=mysqli_query($conn, $query) or die(mysqli_error($conn));
?>

<div class="main-content-inner">
    <div class="page-header">
        <h1 class="page-heading h6 ebold">All Users</h1>
        <ul class="list-inline breadcrumb d-none d-md-flex">
            <li class="breadcrumb-item">Home</li>
            <li class="breadcrumb-item">Manage Users</li>
        </ul>
    </div>
    <div class="article">
        <div class="article-heading flex-heading">
            <h5 class="text-center">All Users</h5>
            <a href="main.php?paction=users_add" class="btn btn-basic btn-sm">Add User</a>
        </div>
        <div class="article-content container-max">
            <div class="table-responsive">
                <table class="table table-bordered table-big">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>User Name</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while($rw=mysqli_fetch_assoc($q)){
                        echo '<tr>
                            <td>'.$rw['name'].'</td>
                            <td>'.$rw['user'].'</td>
                            <td>'.$rw['mobile'].'</td>
                            <td>'.$rw['email'].'</td>
                            <td>';
                            switch($rw['typ']){
                                case 'A':
                                    echo "Admin";
                                break;
                                case 'GT':
                                    echo "Gate";
                                break;
                                case 'WT':
                                    echo "Weight";
                                break;
                                case 'OF':
                                    echo "Order Feeding";
                                break;
                                case 'DP':
                                    echo "Dispatch";
                                break;
                                case 'SK':
                                    echo "Store Keeping";
                                break;
                                case 'AC':
                                    echo "Account";
                                break;
                                case 'MN':
                                    echo "Manager";
                                break;
                            }
                            echo '</td>
                            <td class="text-center">';
                            if($rw['admin_id']!='1'){
                                echo '<button data-toggle="tooltip" title="'.($rw['status']=='1'?'Disable':'Enable').'" data-id ="'.$rw['admin_id'].'" class="btn btn-default statusToggle '.($rw['status']=='1'?'active':'inactive').'"><i class="fa fa-toggle-'.($rw['status']=='1'?'on':'off').'"></i></button>';
                            }
                            echo '</td>
                            <td class="ws-nowrap">';
                            if($rw['admin_id']!='1'){
                                echo '<a data-toggle="tooltip" title="Edit" href="main.php?paction=users_add&admin_id='.$rw['admin_id'].'"  class="btn action-btn btn-primary"><i class="bi bi-pencil-square"></i></a>';
                                // echo '<button class="btn action-btn btn-primary"><i class="fa fa-edit"></i></button>
                                // <button  class="btn action-btn btn-danger"><i class="fa fa-trash"></i></button>';
                            }
                            echo '</td>
                        </tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagging -->
                <div class="pagging">
                    <div class="right">
                        <?php
                        include('ps_pagination.php');
                        $pager = new PS_Pagination($conn, $sql, $noprd, 6, "paction=$_GET[paction]");
                        $pager->setDebug(false);
                        $pager->total_rows=$result;
                        $rs = $pager->paginate();
                        echo $pager->renderFirst();
                        echo $pager->renderPrev("Back");
                        echo $pager->renderNav('<span>', '</span>');
                        echo $pager->renderNext("Next");
                        echo $pager->renderLast();
                        ?>
                    </div>
                </div>
            <!-- End Pagging -->
        </div>
    </div>
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
