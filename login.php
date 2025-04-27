<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("connection/connect.php");

$message = "";
$success = "";

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($db, $_POST['username']);
    $password = mysqli_real_escape_string($db, $_POST['password']);

    if (!empty($username) && !empty($password)) {
        // Corrected table name from 'users' to 'customers' and matching password
        $loginquery = "SELECT * FROM customers WHERE name='$username' AND password='".md5($password)."'";
        $result = mysqli_query($db, $loginquery);
        $row = mysqli_fetch_array($result);

        if (is_array($row)) {
            // Corrected field from 'u_id' to 'user_id'
            $_SESSION["user_id"] = $row['user_id'];
            header("Location: index.php");
            exit();
        } else {
            $message = "❌ Invalid Username or Password!";
        }
    } else {
        $message = "Please enter Username and Password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login || Code Camp BD</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css">
    <link rel="stylesheet prefetch" href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,500,700,900|RobotoDraft:400,100,300,500,700,900">
    <link rel="stylesheet prefetch" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/login.css">

    <style type="text/css">
    #buttn {
        color: #fff;
        background-color: #5c4ac7;
    }
    </style>

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>

<body>

    <header id="header" class="header-scroll top-header headrom">
        <nav class="navbar navbar-dark">
            <div class="container">
                <button class="navbar-toggler hidden-lg-up" type="button" data-toggle="collapse" data-target="#mainNavbarCollapse">&#9776;</button>
                <a class="navbar-brand" href="index.php"> <img class="img-rounded" src="images/logo.png" alt="" width="18%"> </a>
                <div class="collapse navbar-toggleable-md float-lg-right" id="mainNavbarCollapse">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link active" href="vendors.php">Restaurants</a></li>
                        <?php
                        if (empty($_SESSION["user_id"])) {
                            echo '<li class="nav-item"><a href="login.php" class="nav-link active">Login</a></li>';
                            echo '<li class="nav-item"><a href="registration.php" class="nav-link active">Register</a></li>';
                        } else {
                            echo '<li class="nav-item"><a href="your_orders.php" class="nav-link active">My Orders</a></li>';
                            echo '<li class="nav-item"><a href="logout.php" class="nav-link active">Logout</a></li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <div style="background-image: url('images/img/pimg.jpg');">

        <div class="pen-title"></div>

        <div class="module form-module">
            <div class="toggle"></div>

            <div class="form">
                <h2>Login to your account</h2>

                <?php if (!empty($message)): ?>
                    <span style="color:red;"><?php echo $message; ?></span>
                <?php endif; ?>
                
                <?php if (!empty($success)): ?>
                    <span style="color:green;"><?php echo $success; ?></span>
                <?php endif; ?>

                <form action="" method="post">
                    <input type="text" placeholder="Username" name="username" required />
                    <input type="password" placeholder="Password" name="password" required />
                    <input type="submit" id="buttn" name="submit" value="Login" />
                </form>
            </div>

            <div class="cta">
                Not registered? <a href="registration.php" style="color:#5c4ac7;">Create an account</a>
            </div>
        </div>

        <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

    </div>

    <div class="container-fluid pt-3">
        <p></p>
    </div>

    <?php include "include/footer.php" ?>

</body>
</html>
