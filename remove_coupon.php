<?php
	session_start();
	if(isset($_SESSION["coupon_cost"])){
		$code = $_POST["c_code"];
		
		$sql = "select * from deal where deal_name='$code'";
		
        include_once('./db_connect.php');
		
		$result = mysqli_query($conn, "UPDATE deal SET deal_count = deal_count + 1 where deal_name='$code'");
		if($result){				
			unset($_SESSION["coupon_cost"]);
			echo "Coupon is removed.";
		}
	}
	else{
		echo $_SESSION["coupon_cost"];
	}
?>