<?php

include('includes/db.php');

if(isset($_GET['delete_c'])){
	$delete_id = $_GET['delete_c'];

	$delete_c = "delete from customers where customer_id = '$delete_id'";

	$run_delete = mysqli_query($conn, $delete_c);

	if($run_delete){
		echo "<script>alert('Customer has been deleted')</script>";
		echo "<script>window.open('index.php?view_customers','_self')</script>";
	}
}

?>