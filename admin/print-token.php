<?php
    $perm=check_permission("A");
    if(isset($_GET['tokenid']) && !empty($_GET['tokenid']) && is_numeric($_GET['tokenid'])){
        $tokenid=mysqli_real_escape_string($conn, $_GET['tokenid']);
        $query = "SELECT gate.gid,gate.tokenid,gate.vehicleno,customers.`name` as partyname,gate.createdon
        FROM gate INNER JOIN customers ON customers.cust_id = gate.cid where gid='$tokenid'";
        $qq = mysqli_query($conn,$query);
        $rw = mysqli_fetch_assoc($qq);
    }else{
        echo '<script>window.location.href="main.php&errmsg=Token cannot be printed."</script>';
    }
?>
<style>
    @media print {
        @page{
            size: A7;
        }
        body{
            background-color: #fff;
            width: 100%;
            height:100%
        }
        .no-print{
            display: none;
        }
        .page-wrapper{
            margin-left: 0px;
            width: 100%;
            height:100%;
        }
        .page-wrapper .main-content{
            padding: 0px;
        }
    }
    h4.bold{
        border-width: 2px 0px 2px 0px;
        border-style: double;
        border-color: #222;
        font-size: 2rem;
    }
    h4.bold span.small{
        font-weight: 600;
    }
    .table td{
        font-size: 1rem;
        font-weight: 500;
    }
</style>
<div class="main-content-inner">
    <table class="table table-borderless">
        <thead>
            <tr>
                <th colspan="2">
                    <h4 class="bold mb-0 py-3 text-center"><span class="small mr-4">Token No. </span> <?php echo $rw['tokenid']; ?></h4>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="pb-0"><p class="mb-0 mt-4">Vehicle No.</p></td>
                <td class="pb-0"><p class="mb-0 mt-4">Date</p></td>
            </tr>
            <tr>
                <td class=""><h5><?php echo strtoupper($rw['vehicleno']); ?></h5></td>
                <td class=""><h5><?php echo $rw['createdon']; ?></h5></td>
            </tr>
            <tr>
                <td class="pb-0" colspan="2"><p class="mb-0">Party Name</p></td>
            </tr>
            <tr>
                <td class="" colspan="2"><h5><?php echo ucwords($rw['partyname']); ?></h5></td>
            </tr>
        </tbody>
    </table>
</div>

<script>
	$(document).ready(function(){
        window.print();
    })
    window.addEventListener('afterprint', (event) => {
     window.location.href = 'main.php'
    });
</script>