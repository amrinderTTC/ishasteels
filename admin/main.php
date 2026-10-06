<?php
include_once "../conn.php";
if ($qlist['ip'] != $ip & $_SESSION["user_typ"] != 'A') {
	echo '<script>window.location.href="main.php?paction=unauthorize&errmsg=Ip address blocked"</script>';
}
$_SESSION['url'] = $_SERVER['REQUEST_URI'];

if ($_SESSION["sid"] != "Admin_loggedin") {
	header("location:index.php");
	echo '<script>window.location.href="index.php"</script>';
	die();
}
$action = (array_key_exists('paction', $_GET) ? $_GET['paction'] : "");

?>

<!DOCTYPE html>
<html>

<head>
	<title>Isha Steel Enterprises</title>
	<meta charset="utf-8">
	<!-- amit verma -->
	<!-- <meta Http-Equiv="Cache-Control" Content="no-cache">
		<meta Http-Equiv="Pragma" Content="no-cache">
		<meta Http-Equiv="Expires" Content="0">  -->
	<?php header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
	header('Cache-Control: no-store, no-cache, must-revalidate');
	header('Cache-Control: post-check=0, pre-check=0', FALSE);
	header('Pragma: no-cache'); ?>
	<!-- amit verma -->
	<link rel="icon" type="image/gif" href="assets/images/favicon.png">
	<meta name="viewport" content="width = device-width, initial-scale = 1.0">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="mobile-web-app-capable" content="yes">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/all.css">
	<link href="assets/select2/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/responsive.css">
	<script type="text/javascript" src="assets/js/jquery-3.4.1.min.js"></script>
	<script type="text/javascript" src="assets/js/popper.min.js"></script>
	<script type="text/javascript" src="assets/js/bootstrap.js"></script>
	<script type="text/javascript" src="assets/js/script.js"></script>
	<script src="assets/select2/select2.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
	<script type="text/javascript">
		$(document).ready(function() {
			$('.select2me').select2();
		});
	</script>
	<script language="JavaScript" type="text/JavaScript">

	</script>
</head>

<body class="">
	<div class="wrapper h-100">
		<!-- HEader starts -->
		<section class="header-wrapper no-print">
			<div class="header">
				<div class="logo-wrapper">
					<a href="main.php" class="navbar-brand"><img class="img-fluid icon" src="assets/images/vsb-logo-icon.png">
						<img class="img-fluid logo" src="assets/images/vsb-logo.png"></a>
					<button class="menubar-toggler"><span></span><span></span><span></span></button>
				</div>
				<div class="header-right">
					<span id="fullscreen"><i class="bi bi-fullscreen"></i></span>
					<a id="header-user" href="#">
						<img class="img-fluid" src="assets/images/profiles/profile-pic.png">
						<span><?php echo ucwords($_SESSION['name']); ?></span>
					</a>
					<div class="userdrop">
						<a href="#"><i class="bi bi-person"></i>My Profile</a>
						<!--<a href="#"><i class="bi bi-lock"></i>Lock Screen</a> -->
						<a href="main.php?paction=change_password"><i class="bi bi-key"></i>Change Password</a>
						<?php 
							if($_SESSION["user_typ"] == 'A'){
						?>
						<a href="main.php?paction=delgidreport"><i class="bi bi-truck"></i>Removed Vehicles</a>
						<a href="main.php?paction=ordermodified"><i class="bi bi-boxes"></i>Order Modified</a>
						<?php } ?>
						<a href="logout.php" class="logout"><i class="bi bi-power"></i>Logout</a>
					</div>
				</div>
			</div>
		</section>
		<!-- Header ends -->

		<!-- Sidebar Starts -->
		<div class="sidebar no-print">
			<!-- <button class="sidebarClose"><i class="far fa-arrow-alt-circle-left"></i></button> -->
			<ul class="list-unstyled menubar">
				<li class="menu-item">
					<a href="main.php" class="menu-link"><i class="bi bi-qr-code-scan"></i><span class="menu-title">Dashbord</span></a>
				</li>
				<li class="menu-item">
					<a href="main.php?paction=clientwise_size_report" class="menu-link <?php echo (($_GET['paction'] == 'clientwise_size_report') ? 'active' : ''); ?>"><i class="bi bi-slack"></i><span class="menu-title">Size report</span></a>
				</li>
				<?php
				if ($_SESSION['user_typ'] == "OD"){
					?>
					<li class="menu-item has-submenu">
						<a href="main.php" class="menu-link"><i class="bi bi-cart"></i><span class="menu-title">Manage Orders</span></a>
						<ul class="list-unstyled submenu">
							<!-- <li><a href="main.php?paction=sale_deals_view">Deals</a></li> -->
							<li><a href="main.php?paction=sale_order_view">Orders</a></li>

						</ul>
					</li>
					<?php
				}
				if ($_SESSION['user_typ'] == "A" || $_SESSION['user_typ'] == "SA") { ?>
					<li class="menu-item">
						<a href="main.php?paction=ip-add" class="menu-link"><i class="bi bi-wifi menu-icon"></i><span class="menu-title">Ip</span></a>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=users_view" class="menu-link <?php echo (($_GET['paction'] == 'users_view') ? 'active' : ''); ?>"><i class="bi bi-person"></i><span class="menu-title">Manage Users</span></a>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=print_dispatch_lists" class="menu-link <?php echo (($_GET['paction'] == 'print_dispatch_lists') ? 'active' : ''); ?>"><i class="bi bi-printer"></i><span class="menu-title">Print Dispatch</span></a>
					</li>
					<li class="menu-item has-submenu">
						<a href="main.php" class="menu-link"><i class="bi bi-person-rolodex"></i><span class="menu-title">Manage Contacts</span></a>
						<ul class="list-unstyled submenu">
							<!-- <li><a href="main.php?paction=contact_view&typ=vendor">All Vendor</a></li> -->
							<li><a href="main.php?paction=contact_view&typ=customer">All Customers</a></li>
						</ul>
					</li>
					<li class="menu-item has-submenu">
						<a href="main.php" class="menu-link"><i class="bi bi-boxes"></i><span class="menu-title">Manage Products</span></a>
						<ul class="list-unstyled submenu">
							<li><a href="main.php?paction=brands_view">Brands</a></li>
							<li><a href="main.php?paction=products_view">Products</a></li>
							<li><a href="main.php?paction=sizes_view">Sizes</a></li>
						</ul>
					</li>
					<li class="menu-item has-submenu">
						<a href="main.php" class="menu-link"><i class="bi bi-cart"></i><span class="menu-title">Manage Orders</span></a>
						<ul class="list-unstyled submenu">
							<li><a href="main.php?paction=sale_deals_view">Deals</a></li>
							<li><a href="main.php?paction=sale_order_view">Orders</a></li>

						</ul>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=grades_view" class="menu-link <?php echo (($_GET['paction'] == 'grades_view') ? 'active' : ''); ?>"><i class="bi bi-slack"></i><span class="menu-title">Grades</span></a>
					</li>
					<?php
				}

				if ($_SESSION['user_typ'] == "OF") {
					?>
					<li class="menu-item has-submenu">
						<a href="main.php" class="menu-link"><i class="bi bi-person-rolodex"></i><span class="menu-title">Manage Contacts</span></a>
						<ul class="list-unstyled submenu">
							<!-- <li><a href="main.php?paction=contact_view&typ=vendor">All Vendor</a></li> -->
							<li><a href="main.php?paction=contact_view&typ=customer">All Customers</a></li>
						</ul>
					</li>
					<li class="menu-item has-submenu">
						<a href="main.php" class="menu-link"><i class="bi bi-boxes"></i><span class="menu-title">Manage Products</span></a>
						<ul class="list-unstyled submenu">
							<li><a href="main.php?paction=brands_view">Brands</a></li>
							<li><a href="main.php?paction=products_view">Products</a></li>
							<li><a href="main.php?paction=sizes_view">Sizes</a></li>
						</ul>
					</li>
					<li class="menu-item has-submenu">
						<a href="main.php" class="menu-link"><i class="bi bi-cart"></i><span class="menu-title">Manage Orders</span></a>
						<ul class="list-unstyled submenu">
							<li><a href="main.php?paction=sale_deals_view">Deals</a></li>
							<li><a href="main.php?paction=sale_order_view">Orders</a></li>

						</ul>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=client_report" class="menu-link <?php echo (($_GET['paction'] == 'client_report') ? 'active' : ''); ?>"><i class="bi bi-journal-text"></i><span class="menu-title">Client Wise Order (All)</span></a>
					</li>
					<?php
				} 
				
				if ($_SESSION['user_typ'] == "DP") { 
					?>
					<li class="menu-item">
						<a href="main.php?paction=client_report" class="menu-link <?php echo (($_GET['paction'] == 'client_report') ? 'active' : ''); ?>"><i class="bi bi-journal-text"></i><span class="menu-title">Client Wise Order (All)</span></a>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=dispatch_report" class="menu-link <?php echo (($_GET['paction'] == 'dispatch_report') ? 'active' : ''); ?>"><i class="bi bi-journal-text"></i><span class="menu-title">Dispatch Report</span></a>
					</li>
					<?php
				}
				
				if ($_SESSION['user_typ'] == "SK") {
					?>
					<li class="menu-item">
						<a href="main.php?paction=stock_view" class="menu-link <?php echo (($_GET['paction'] == 'stock_view') ? 'active' : ''); ?>"><i class="bi bi-boxes"></i><span class="menu-title">Stock</span></a>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=add_to_stock" class="menu-link <?php echo (($_GET['paction'] == 'add_to_stock') ? 'active' : ''); ?>"><i class="bi bi-boxes"></i><span class="menu-title">Add To Stock</span></a>
					</li>
					<?php
				}
				
				if ($_SESSION['user_typ'] == "GT") {
					?>
					<!-- Gate Security Management -->

					<!-- <li class="menu-item">
						<a href="main.php?paction=material_in" class="menu-link <?php #echo(($_GET['paction'] =='material_in')?'active':''); 
																				?>"><i class="bi bi-truck"></i><span class="menu-title">Material In</span></a>
					</li> -->
					<li class="menu-item">
						<a href="main.php?paction=material_out" class="menu-link <?php echo (($_GET['paction'] == 'material_out') ? 'active' : ''); ?>"><i class="bi bi-truck-flatbed"></i><span class="menu-title">Material Out</span></a>
					</li>

					<li class="menu-item">
						<a href="main.php?paction=vehicletypes" class="menu-link <?php echo (($_GET['paction'] == 'vehicletypes') ? 'active' : ''); ?>"><i class="bi bi-list-stars"></i><span class="menu-title">Vehicle Types</span></a>
					</li>
					<!-- Gate Security Management end -->
					<?php 
				}
				
				if (in_array($_SESSION["user_typ"], array("A", "SA"))) {
					?>
					<li class="menu-item">
						<a href="main.php?paction=stocklocation" class="menu-link <?php echo (($_GET['paction'] == 'stocklocation') ? 'active' : ''); ?>"><i class="bi bi-map"></i><span class="menu-title">Stock Locations</span></a>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=stock_view" class="menu-link <?php echo (($_GET['paction'] == 'stock_view') ? 'active' : ''); ?>"><i class="bi bi-boxes"></i><span class="menu-title">Stock</span></a>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=add_to_stock" class="menu-link <?php echo (($_GET['paction'] == 'add_to_stock') ? 'active' : ''); ?>"><i class="bi bi-boxes"></i><span class="menu-title">Add To Stock</span></a>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=client_report" class="menu-link <?php echo (($_GET['paction'] == 'client_report') ? 'active' : ''); ?>"><i class="bi bi-journal-text"></i><span class="menu-title">Client Wise Order (All)</span></a>
					</li>
					<li class="menu-item">
						<a href="client_report_print.php" target="_blank" class="menu-link"><i class="bi bi-journal-text"></i><span class="menu-title">Client Wise Order (All - P)</span></a>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=client_report_pending" class="menu-link <?php echo (($_GET['paction'] == 'client_report_pending') ? 'active' : ''); ?>"><i class="bi bi-journal-text"></i><span class="menu-title">Client Wise Order(Pending)</span></a>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=order_entry_report" class="menu-link <?php echo (($_GET['paction'] == 'order_entry_report') ? 'active' : ''); ?>"><i class="bi bi-journal-text"></i><span class="menu-title">Order Entry Report</span></a>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=deals_entry_report" class="menu-link <?php echo (($_GET['paction'] == 'deals_entry_report') ? 'active' : ''); ?>"><i class="bi bi-journal-text"></i><span class="menu-title">Deals Entry Report</span></a>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=dispatch_report" class="menu-link <?php echo (($_GET['paction'] == 'dispatch_report') ? 'active' : ''); ?>"><i class="bi bi-journal-text"></i><span class="menu-title">Dispatch Report</span></a>
					</li>

					<li class="menu-item">
						<a href="main.php?paction=vehicle_history" class="menu-link <?php echo (($_GET['paction'] == 'vehicle_history') ? 'active' : ''); ?>"><i class="bi bi-clock-history"></i><span class="menu-title">Vehicle History</span></a>
					</li>
					<?php
				}
				?>
				<!-- Finished Stock -->

				<!-- <li class="menu-item">
						<a href="main.php?paction=dashboard_finished_stock" class="menu-link <?php echo (($_GET['paction'] == 'dashboard_finished_stock') ? 'active' : ''); ?>"><i class="bi bi-boxes"></i><span class="menu-title">Dashboard Finished Stock</span></a>
					</li>
					<li class="menu-item">
						<a href="main.php?paction=finished_stock" class="menu-link <?php echo (($_GET['paction'] == 'finished_stock') ? 'active' : ''); ?>"><i class="bi bi-boxes"></i><span class="menu-title">Finished Stock</span></a>
					</li> -->
				<!-- Finished Stock end -->
			</ul>
		</div>
		<!-- Sidebar Ends -->
		<div class="page-wrapper">
			<div class="main-content">
				<?php
				//var_dump($_SESSION);	
				if ($action == "users_view") {
					include "users_view.php";
				}
				if ($action == "print_dispatch_lists") {
					include "print_dispatch_lists.php";
				} elseif ($action == "users_add") {
					include "users_add.php";
				} elseif ($action == "contact_add") {
					include "contact_add.php";
				} elseif ($action == "print_deal") {
					include "print_deal.php";
				} else if ($action == "unauthorize") {
					include "maintainance.php";
				} else if ($action == "ip-add") {
					include "ip_add.php";
				} elseif ($action == "print_order") {
					include "print_order.php";
				} elseif ($action == "delgidreport") {
					include "delgidreport.php";
				} elseif ($action == "contact_view") {
					include "contact_view.php";
				} elseif ($action == "contact_details") {
					include "contact_details.php";
				} elseif ($action == "change_password") {
					include "change_password.php";
				} elseif ($action == "brands_view") {
					include "brands_view.php";
				} elseif ($action == "grades_view") {
					include "grades_view.php";
				} elseif ($action == "vehicletypes") {
					include "vehicletypes.php";
				} elseif ($action == "material_in") {
					include "material_in.php";
				} elseif ($action == "material_out") {
					include "material_out.php";
				} elseif ($action == "vehicle_view") {
					include "vehicle_view.php";
				} elseif ($action == "vehicle_history") {
					include "vehicle_history.php";
				} elseif ($action == "brands_view") {
					include "brands_view.php";
				} elseif ($action == "products_view") {
					include "products_view.php";
				} elseif ($action == "sizes_view") {
					include "sizes_view.php";
				} elseif ($action == "add_size") {
					include "add_size.php";
				} elseif ($action == "clientwise_size_report") {
					include "clientwise_size_report.php";
				} elseif ($action == "history_clientwise_report") {
					include "history_clientwise_report.php";
				} elseif ($action == "sale_order_view") {
					include "sale_order_view.php";
				} elseif ($action == "sale_deals_view") {
					include "sale_deals_view.php";
				} elseif ($action == "add_deal_order") {
					include "add_deal_order.php";
				} elseif ($action == "edit_deal_order") {
					include "edit_deal_order.php";
				} elseif ($action == "sale_deal_details") {
					include "sale_deal_details.php";
				} elseif ($action == "sale_order_details") {
					include "sale_order_details.php";
				} elseif ($action == "add_sale_order") {
					//include "add_sale_order.php"; // changed on 14/03/2022
					include "add_sale_order_new.php";
				} elseif ($action == "edit_sale_order") {
					//include "add_sale_order.php"; // changed on 14/03/2022
					include "edit_sale_order_new.php";
				} elseif ($action == "sale_order_print") {
					include "sale_order_print.php";
				} elseif ($action == "stocklocation") {
					include "stock_location.php";
				} elseif ($action == "dispatch_slip_add") {
					include "dispatch_slip_add.php";
				} elseif ($action == "dispatch_slip_edit") {
					include "dispatch_slip_edit.php";
				} elseif ($action == "dispatch_slip_print") {
					include "dispatch_slip_print.php";
				} elseif ($action == "dashboard_weight") {
					include "dashboard_weight.php";
				} elseif ($action == "material_in_weight") {
					include "material_in_weight.php";
				} elseif ($action == "material_out_weight") {
					include "material_out_weight.php";
				} elseif ($action == "dispatch_plan") {
					include "dispatch_plan.php";
				} elseif ($action == "material_in_stock") {
					include "material_in_stock.php";
				} elseif ($action == "stock") {
					include "stock.php";
				} elseif ($action == "ordermodified") {
					include "ordermodified.php";
				} elseif ($action == "add_to_stock") {
					include "add_to_stock.php";
				} elseif ($action == "stock_view") {
					include "stock_view.php";
				} elseif ($action == "dashboard_weight") {
					include "dashboard_weight.php";
				} elseif ($action == "client_report") {
					include "client_report.php";
				} elseif ($action == "client_report_print") {
					include "client_report_print.php";
				} elseif ($action == "client_report_pending") {
					include "client_report_pending.php";
				} elseif ($action == "dispatch_report") {
					include "dispatch_report.php";
				} elseif ($action == "add_sale_order_new") {
					include "add_sale_order_new.php";
				} elseif ($action == "asignweighttodispatchslip") {
					include "asign_weight_to_dispatchslip.php";
				} elseif ($action == "dispatch_slip_print_customer") {
					include "dispatch_slip_print_customer.php";
				} elseif ($action == "dispatch_report_detailed") {
					include "dispatch_report_detailed.php";
				} elseif ($action == "print_token") {
					include "print-token.php";
				} elseif ($action == "order_entry_report") {
					include "order_entry_report.php";
				} elseif ($action == "deals_entry_report") {
					include "deals_entry_report.php";
				} else {
					//include "dashboard_gate.php";
					if ($_SESSION["user_typ"] == "A") {
						include "dashboard.php";
					} elseif ($_SESSION["user_typ"] == "GT") {
						include "dashboard_gate.php";
					} elseif ($_SESSION["user_typ"] == "WT") {
						include "dashboard_weight.php";
					} elseif ($_SESSION["user_typ"] == "OF") {
						include "sale_order_view.php";
					} elseif ($_SESSION["user_typ"] == "DP") {
						include "dashboard.php";
					} elseif ($_SESSION["user_typ"] == 'SK')
						include "stock_view.php";
				}
				?>
			</div>
			<footer class="footer text-center no-print">
				&copy;<span class="currentyear"></span> Isha Steel Enterpises<sup>&reg;</sup>. Developed by <a href="//ttcrobotronics.com">TTCR Pvt. Ltd.</a>
			</footer>
			<div id="ppi"></div>
		</div>
	</div>
</body>

</html>