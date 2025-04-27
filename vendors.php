<?php
include("connection/connect.php");
error_reporting(0);
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Vendors List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">
    <link href="css/animsition.min.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

    <style>
        .vendor-card {
            background: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0px 5px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: all 0.3s ease;
        }
        .vendor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0px 10px 30px rgba(0,0,0,0.2);
        }
        .vendor-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
            cursor: pointer;
        }
        .vendor-card h5 {
            margin-bottom: 10px;
            color: #5c4ac7;
        }
        .vendor-card p {
            margin-bottom: 5px;
            color: #555;
        }
        #searchInput {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 25px;
            font-size: 16px;
        }
        .view-products-btn {
            background-color: #5c4ac7;
            color: white;
            padding: 8px 15px;
            margin-top: 10px;
            display: inline-block;
            border-radius: 5px;
            text-decoration: none;
        }
    </style>
</head>

<body>

    <?php include "include/header.php"; ?>

    <div class="container mt-5 mb-5">

        <h2 class="text-center mb-4">List of Vendors</h2>

        <input type="text" id="searchInput" onkeyup="searchVendor()" placeholder="Search for vendors..">

        <div id="vendorList">
            <div class="row">
                <?php
                $query = mysqli_query($db, "SELECT * FROM vendors");
                while ($vendor = mysqli_fetch_assoc($query)) {
                    echo '
                    <div class="col-md-4 mb-4 vendor-entry">
                        <div class="vendor-card">
                            <a href="products.php?vendor_id='.$vendor['vendor_id'].'">
                                <img src="uploads/vendors/'.$vendor['vendor_image'].'" alt="'.$vendor['vendor_name'].' Logo">
                            </a>
                            <h5>'.$vendor['vendor_name'].'</h5>
                            <p><strong>Address:</strong> '.$vendor['vendor_address'].'</p>
                            <p><strong>Phone:</strong> '.$vendor['vendor_phone'].'</p>
                            <a href="products.php?vendor_id='.$vendor['vendor_id'].'" class="view-products-btn">View Products</a>
                        </div>
                    </div>';
                }
                ?>
            </div>
        </div>

    </div>

    <?php include "include/footer.php"; ?>

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>

    <script>
        function searchVendor() {
            var input, filter, cards, cardContainer, h5, title, i;
            input = document.getElementById("searchInput");
            filter = input.value.toUpperCase();
            cardContainer = document.getElementById("vendorList");
            cards = cardContainer.getElementsByClassName("vendor-entry");
            for (i = 0; i < cards.length; i++) {
                title = cards[i].getElementsByTagName("h5")[0];
                if (title.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    cards[i].style.display = "";
                } else {
                    cards[i].style.display = "none";
                }
            }
        }
    </script>

</body>
</html>
