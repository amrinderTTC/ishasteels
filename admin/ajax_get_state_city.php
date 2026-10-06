<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Origin");
header('P3P: CP="CAO PSA OUR"'); // Makes IE to support cookies

//header("Content-Type: application/json; charset=utf-8");
//print_r($_GET);

include "conn.php";
$arr[sucess]=0;
if($_GET[typs]=="S"){
	$qq="select * from states where country_id='$_GET[country_id]' ";
	$qq=mysqli_query($GLOBALS["conn"], $qq) or die (mysqli_error($GLOBALS["conn"]));
	?>
	<select name="state" id="state" class="form-item" required>
		<option value="">Select</option>
		<?php
		while($row = mysqli_fetch_array($qq)){
			if($row[id]==$_GET[sel]){
				$sel="selected";
			}else{
				$sel="";
			}
			?>
			<option <?php echo $sel;?> value="<?php echo $row[id];?>"><?php echo $row[name];?></option>
			<?php
		}
		?>
	</select>
	<?php
}elseif($_GET[typs]=="CI"){
	$qq="select * from cities where state_id='$_GET[state_id]' order by name ";
	$qq=mysqli_query($GLOBALS["conn"], $qq) or die (mysqli_error($GLOBALS["conn"]));
	?>
	<select name="city" id="city" class="form-item" required >
		<option value="">Select</option>
		<?php
		while($row = mysqli_fetch_array($qq)){
			if($row[id]==$_GET[sel]){
				$sel="selected";
			}else{
				$sel="";
			}
			?>
			<option <?php echo $sel;?> value="<?php echo $row[id];?>"><?php echo $row[name];?></option>
			<?php
		}
		?>
	</select>
	<?php
}
?>