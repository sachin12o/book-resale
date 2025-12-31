<?php
include("dbconnection.php"); 
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}




// Fetch cart items count
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;
$cart_rows_number = 0;

if ($user_id) {
   $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('Query failed');
   $cart_rows_number = mysqli_num_rows($select_cart_number);
}
?>

<header class="header">
   <div class="header-1">
      <div class="flex">
         <div class="share">
            <a href="#" class="fab fa-facebook-f"></a>
            <a href="#" class="fab fa-twitter"></a>
            <a href="#" class="fab fa-instagram"></a>
            <a href="#" class="fab fa-linkedin"></a>
         </div>
         <p> 
            <a href="LOGIN.php">Login</a> | <a href="registration_form.php">Register</a> 
         </p>
      </div>
   </div>

   <div class="header-2">
      <div class="flex">
         <a href="home.php" class="logo">Book Resale</a>

         <nav class="navbar">
            <a href="home.php">HOME</a>
            <a href="about.php">ABOUT</a>
            <a href="shop.php">SHOP</a>
            <a href="contact.php">CONTACT</a>
            <a href="orders.php">ORDERS</a>
         </nav>

         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <a href="cart.php"> 
               <i class="fas fa-shopping-cart"></i> 
               <span>(<?php echo $cart_rows_number; ?>)</span> 
            </a>
         </div>

         <div class="account-box">
            <?php if (isset($_SESSION['user_name'])): ?>
               <p>Username: <span><?php echo $_SESSION['user_name']; ?></span></p>
               <p>Email: <span><?php echo $_SESSION['user_email']; ?></span></p>
               <a href="logout.php" class="delete-btn">Logout</a>
            <?php else: ?>
               <p>You are not logged in.</p>
               <a href="LOGIN.php" style="font-size:2rem; text-decoration:underline;">Login</a>
            <?php endif; ?>
         </div>

      </div>
   </div>
</header>

<!-- JavaScript for icons interaction -->
<script>
    let userBox = document.querySelector('.account-box');
    let navbar = document.querySelector('.navbar');

    document.querySelector('#user-btn').onclick = () => {
        userBox.classList.toggle('active');
        navbar.classList.remove('active');
    }

    document.querySelector('#menu-btn').onclick = () => {
        navbar.classList.toggle('active');
        userBox.classList.remove('active');
    }

    window.onscroll = () => {
        userBox.classList.remove('active');
        navbar.classList.remove('active');
    }
</script>

<style>
   
</style>




