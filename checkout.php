<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

if(isset($_POST['order'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $method = mysqli_real_escape_string($conn, $_POST['method']);
    $address = mysqli_real_escape_string($conn, 'Address Line: '. $_POST['addressline'].', '. $_POST['village'].', '. $_POST['subdistrict'].', '. $_POST['city'].', '. $_POST['state'].', '. $_POST['country'].' - '. $_POST['pin_code']);
    $placed_on = date('d-M-Y');

    $cart_total = 0;
    $cart_products[] = '';

    $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    if(mysqli_num_rows($cart_query) > 0){
        while($cart_item = mysqli_fetch_assoc($cart_query)){
            $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
            $sub_total = ($cart_item['price'] * $cart_item['quantity']);
            $cart_total += $sub_total;
        }
    }

    $total_products = implode(', ',$cart_products);

    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

    if($cart_total == 0){
        $message[] = 'Your Cart Is Empty!';
    }elseif(mysqli_num_rows($order_query) > 0){
        $message[] = 'Order Placed Already!';
    }else{
        mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        $message[] = 'Order Placed Successfully!';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Raff | Flowers</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

   <link rel="shortcut icon" href="images/raffneptune.jpg" type="image/x-icon" />

</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="heading">
    <h3>checkout order</h3>
    <p> <a href="home.php">Home</a> / Checkout </p>
</section>

<section class="display-order">
    <?php
        $grand_total = 0;
        $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        if(mysqli_num_rows($select_cart) > 0){
            while($fetch_cart = mysqli_fetch_assoc($select_cart)){
            $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
            $grand_total += $total_price;
    ?>    
    <p> <?php echo $fetch_cart['name'] ?> <span>(<?php echo '$'.$fetch_cart['price'].'/-'.' x '.$fetch_cart['quantity']  ?>)</span> </p>
    <?php
        }
        }else{
            echo '<p class="empty">your cart is empty</p>';
        }
    ?>
    <div class="grand-total">Grand Total : <span>$<?php echo $grand_total; ?>/-</span></div>
</section>

<section class="checkout">

    <form action="" method="POST">

        <h3>place your order</h3>

        <div class="flex">
            <div class="inputBox">
                <span>Your Name :</span>
                <input type="text" name="name" placeholder="Enter Your Name">
            </div>
            <div class="inputBox">
                <span>Your Number :</span>
                <input type="number" name="number" min="0" placeholder="Enter Your Number">
            </div>
            <div class="inputBox">
                <span>Your Email :</span>
                <input type="email" name="email" placeholder="Enter Your Email">
            </div>
            <div class="inputBox">
                <span>Payment Method :</span>
                <select name="method">
                    <option value="Cash On Delivery">Cash On Delivery</option>
                    <option value="Credit Card">Credit Card</option>
                    <option value="Gopay">Gopay</option>
                    <option value="Dana">Dana</option>
                </select>
            </div>
            <div class="inputBox">
                <span>Address Line :</span>
                <input type="text" name="addressline" placeholder="e.g. Jl. Jenderal Sudirman No.1, RT.1/RW.3">
            </div>
            <div class="inputBox">
                <span>Village :</span>
                <input type="text" name="village" placeholder="e.g. Gelora">
            </div>
            <div class="inputBox">
                <span>Subdistrict :</span>
                <input type="text" name="subdistrict" placeholder="e.g.  Tanah Abang">
            </div>
            <div class="inputBox">
                <span>City :</span>
                <input type="text" name="city" placeholder="e.g. Jakarta Pusat">
            </div>
            <div class="inputBox">
                <span>State :</span>
                <input type="text" name="state" placeholder="e.g. Jakarta Raya">
            </div>
            <div class="inputBox">
                <span>Country :</span>
                <input type="text" name="country" placeholder="e.g. Indonesia">
            </div>
            <div class="inputBox">
                <span>Pin Code :</span>
                <input type="number" min="0" name="pin_code" placeholder="e.g. 123456">
            </div>
        </div>

        <input type="submit" name="order" value="order now" class="btn">

    </form>

</section>






<?php @include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>