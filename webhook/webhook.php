<?php
header("Content-Type: application/json");

// Get JSON payload from Dialogflow
$incomingData = file_get_contents("php://input");
$data = json_decode($incomingData, true);

$conn = new mysqli("localhost", "bilalturabi_bilalturabi", "Dlink789&*(", "bilalturabi_pharma_chatbot");
if ($conn->connect_error) {
    echo json_encode(["fulfillmentText" => "Error: Could not connect to the database."]);
    exit;
}

if (isset($data['queryResult']['intent']['displayName'])) {
    $intent = $data['queryResult']['intent']['displayName'];
    switch ($intent) {
         case "check.product - context: check_product":
            $product = $data['queryResult']['parameters']['product'][0] ?? "unknown";
            file_put_contents("webhook.log", "Checking product: $product\n", FILE_APPEND);

            $stmt = $conn->prepare("SELECT product_count,product_cost FROM product WHERE product_name = ?");
            $stmt->bind_param("s", $product);
            $stmt->execute();
            $stmt->bind_result($product_count, $product_cost);

            if ($stmt->fetch()) {
                if ($product_count > 0) {
                    $response = "$product is available in our stock.Its cost is Rs $product_cost /=  Would you like to order it now?";
                } else {
                    $response = "$product is currently out of stock. Would you like to check another product?";
                }
            } else {
                $response = "Sorry, I could not find information about $product.";
            }
            $stmt->close();
                break;
                
            case "order.status - context:order_status":
            $order_id = $data['queryResult']['parameters']['number'];
            file_put_contents("webhook.log", "Checking Order Status: $order_id\n", FILE_APPEND);
            
            $stmt = $conn->prepare("
                SELECT status 
                FROM orders 
                WHERE order_id = ?
            ");
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->bind_result($status);
            
            if ($stmt->fetch()) {
                $response = "The status of your order (ID: $order_id) is **$status**.";
            } else {
                $response = "Sorry, I couldn't find an order with ID $order_id. Please check the order ID and try again.";
            }
            
            $stmt->close();
            break;

        case "order.new - context: order_create":
            $product_name = $data['queryResult']['parameters']['product'][0]; 
            $quantity = $data['queryResult']['parameters']['number'][0];
            $email_login = $data['queryResult']['parameters']['email'];
           
if (!empty($product_name) && !empty($quantity) && !empty($email_login)) {

    $stmt = $conn->prepare("SELECT product_id FROM product WHERE product_name = ?");
    $query = "Quantity =  $quantity product_id = $product_name email is $email_login";
    $stmt->bind_param("s", $product_name);
    $stmt->execute();
    $stmt->bind_result($product_id);

    if ($stmt->fetch()) {
        $stmt->close();

        $stmt = $conn->prepare("UPDATE product SET product_count = product_count - ? WHERE product_id = ?");
        $stmt->bind_param("ii", $quantity, $product_id);
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare("SELECT quantity FROM cart WHERE user_email = ? AND product_id = ? AND checkedout = 0");
        $stmt->bind_param("si", $email_login, $product_id);
        $stmt->execute();
        $stmt->bind_result($existing_quantity);

        if ($stmt->fetch()) {
            $stmt->close();
            $stmt = $conn->prepare("UPDATE cart SET quantity = quantity + ? WHERE user_email = ? AND product_id = ? AND checkedout = 0");
            $stmt->bind_param("isi", $quantity, $email_login, $product_id);
            $stmt->execute();
            $response = "$product_name have been updated in your cart";
        } else {
            $stmt->close();
            $stmt = $conn->prepare("INSERT INTO cart (product_id, user_email, quantity) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $product_id, $email_login, $quantity);
            $stmt->execute();
            $response = "$product_name have been added in your cart kindly proceed";
        }

        $stmt->close();

    } else {
            $response = "$product_name is out of stock please try another product";
        }
    } else {
        $response = "Something went wrong please try again";
    }
            break;
        default:
            $response = "Sorry, I can't handle the intent: $intent.";
            break;
    }
} else {
    $response = "Error: Invalid request structure.";
}

$conn->close();

// Send response back to Dialogflow
echo json_encode(["fulfillmentText" => $response]);

?>
