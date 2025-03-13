<?php
session_start();
if(isset($_SESSION["email_login"])){
		$fname = $_POST["c_fname"];
		$lname = $_POST["c_lname"];
		$address = $_POST["c_address"];
		$state_country = $_POST["c_state_country"];
		$postal_code = $_POST["c_postal_zip"];
		$email = $_POST["c_email_address"];
		$phone = $_POST["c_phone"];	
		$customer_id = $_SESSION["email_login"];
		$coupon = $_POST['c_code'];
		$total = $_POST['discount'];
		
		$update_order = "INSERT INTO orders(`fname`,`lname`,`address`,`state_country`,`postalcode`,`email`,`phone_number`,`customer_id`,`deal_code`,`total`) VALUES ('$fname', '$lname', '$address', '$state_country', '$postal_code', '$email', '$phone', '$customer_id','$coupon','$total')";

        include_once('./db_connect.php');
		
		$result = mysqli_query($conn, $update_order);
		
        if ($result){
			$sql = "DELETE FROM cart where user_email='$customer_id'";
			if(mysqli_query($conn, $sql)){
				unset($_SESSION["coupon_cost"]);
				echo "success";	
			}
			else			
				echo "Error deleting record: " . mysqli_error($conn);			
        }
		else{
			//echo mysqli_error($conn);
			die("Failed to update");
		}
    


    
}
else echo "Invalid request";

?>