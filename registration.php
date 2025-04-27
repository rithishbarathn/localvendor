<?php
session_start();
error_reporting(0);
include("connection/connect.php");

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($db, $_POST['name']);
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $phone = mysqli_real_escape_string($db, $_POST['phone']);
    $password = mysqli_real_escape_string($db, $_POST['password']);
    $cpassword = mysqli_real_escape_string($db, $_POST['cpassword']);
    $state = mysqli_real_escape_string($db, $_POST['state']);
    $city = mysqli_real_escape_string($db, $_POST['city']);
    $street = mysqli_real_escape_string($db, $_POST['street']);
    $pincode = mysqli_real_escape_string($db, $_POST['pincode']);

    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($cpassword) || empty($state) || empty($city)) {
        $message = "All fields must be filled!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email address!";
    } elseif ($password !== $cpassword) {
        $message = "Passwords do not match!";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters!";
    } elseif (strlen($phone) < 10) {
        $message = "Phone number must be at least 10 digits!";
    } elseif (strlen($pincode) < 6) {
        $message = "Pincode must be 6 digits!";
    } else {
        $check_email = mysqli_query($db, "SELECT email FROM customers WHERE email='$email'");
        if (mysqli_num_rows($check_email) > 0) {
            $message = "Email already registered!";
        } else {
            $insert = mysqli_query($db, "INSERT INTO customers (name, email, password, phone) VALUES ('$name', '$email', '".md5($password)."', '$phone')");
            if ($insert) {
                $user_id = mysqli_insert_id($db);
                mysqli_query($db, "INSERT INTO locations (user_id, state, city, street, pincode) VALUES ('$user_id', '$state', '$city', '$street', '$pincode')");
                $success = "Account created successfully! Redirecting to login...";
                header("refresh:2;url=login.php");
            } else {
                $message = "Registration failed! Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register | Food Delivery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        body {
            background: url('images/img/pimg.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
        }
        .register-container {
            width: 480px;
            background: rgba(255,255,255,0.95);
            margin: 60px auto;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0px 10px 25px rgba(0,0,0,0.25);
        }
        .register-container h2 {
            text-align: center;
            color: #5c4ac7;
            margin-bottom: 25px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #333;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }
        .form-group input:focus, .form-group select:focus {
            border-color: #5c4ac7;
            outline: none;
        }
        .btn-submit {
            width: 100%;
            background-color: #5c4ac7;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        .btn-submit:hover {
            background-color: #4a38b0;
        }
        .message {
            text-align: center;
            color: red;
            margin-bottom: 15px;
        }
        .success {
            text-align: center;
            color: green;
            margin-bottom: 15px;
        }
        .cta {
            text-align: center;
            margin-top: 20px;
            font-size: 15px;
        }
        .cta a {
            color: #5c4ac7;
            text-decoration: none;
        }
    </style>

</head>
<body>

<div class="register-container">
    <h2>Create New Account</h2>

    <?php if(!empty($message)): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if(!empty($success)): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" required/>
        </div>

        <div class="form-group">
            <label for="email">Email Address</label>
            <input type="email" id="email" name="email" required/>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" required/>
        </div>

        <div class="form-group">
            <label for="state">State</label>
            <select id="state" name="state" onchange="populateCities()" required>
                <option value="">-- Select State --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="city">City</label>
            <select id="city" name="city" required>
                <option value="">-- Select City --</option>
            </select>
        </div>

        <div class="form-group">
            <label for="street">Street Address</label>
            <input type="text" id="street" name="street" required/>
        </div>

        <div class="form-group">
            <label for="pincode">Pincode</label>
            <input type="text" id="pincode" name="pincode" required/>
        </div>

        <div class="form-group">
            <label for="password">Password (Min 6 characters)</label>
            <input type="password" id="password" name="password" required/>
        </div>

        <div class="form-group">
            <label for="cpassword">Confirm Password</label>
            <input type="password" id="cpassword" name="cpassword" required/>
        </div>

        <button type="submit" name="submit" class="btn-submit">Register</button>
    </form>

    <div class="cta">
        Already have an account? <a href="login.php">Login</a>
    </div>
</div>

<script>
// All Indian States + Union Territories + Few Cities
const statesAndCities = {
    "Andhra Pradesh": ["Visakhapatnam", "Vijayawada", "Guntur"],
    "Arunachal Pradesh": ["Itanagar", "Tawang"],
    "Assam": ["Guwahati", "Silchar", "Dibrugarh"],
    "Bihar": ["Patna", "Gaya", "Muzaffarpur"],
    "Chhattisgarh": ["Raipur", "Bilaspur", "Durg"],
    "Goa": ["Panaji", "Margao", "Mapusa"],
    "Gujarat": ["Ahmedabad", "Surat", "Vadodara", "Rajkot"],
    "Haryana": ["Gurgaon", "Faridabad", "Panipat"],
    "Himachal Pradesh": ["Shimla", "Manali", "Dharamshala"],
    "Jharkhand": ["Ranchi", "Jamshedpur", "Dhanbad"],
    "Karnataka": ["Bengaluru", "Mysuru", "Mangaluru", "Hubballi"],
    "Kerala": ["Thiruvananthapuram", "Kochi", "Kozhikode"],
    "Madhya Pradesh": ["Bhopal", "Indore", "Gwalior", "Jabalpur"],
    "Maharashtra": ["Mumbai", "Pune", "Nagpur", "Nashik"],
    "Manipur": ["Imphal", "Thoubal"],
    "Meghalaya": ["Shillong", "Tura"],
    "Mizoram": ["Aizawl", "Lunglei"],
    "Nagaland": ["Kohima", "Dimapur"],
    "Odisha": ["Bhubaneswar", "Cuttack", "Rourkela"],
    "Punjab": ["Ludhiana", "Amritsar", "Jalandhar"],
    "Rajasthan": ["Jaipur", "Udaipur", "Jodhpur", "Kota"],
    "Sikkim": ["Gangtok", "Namchi"],
    "Tamil Nadu": ["Chennai", "Coimbatore", "Madurai", "Tiruchirappalli"],
    "Telangana": ["Hyderabad", "Warangal", "Nizamabad"],
    "Tripura": ["Agartala"],
    "Uttar Pradesh": ["Lucknow", "Kanpur", "Agra", "Varanasi"],
    "Uttarakhand": ["Dehradun", "Haridwar", "Nainital"],
    "West Bengal": ["Kolkata", "Howrah", "Durgapur"],
    "Andaman and Nicobar Islands": ["Port Blair"],
    "Chandigarh": ["Chandigarh"],
    "Dadra and Nagar Haveli and Daman and Diu": ["Daman", "Silvassa"],
    "Delhi": ["New Delhi", "South Delhi", "Dwarka"],
    "Jammu and Kashmir": ["Srinagar", "Jammu"],
    "Ladakh": ["Leh", "Kargil"],
    "Lakshadweep": ["Kavaratti"],
    "Puducherry": ["Puducherry", "Karaikal", "Yanam"]
};

window.onload = function() {
    const stateSelect = document.getElementById("state");
    for (let state in statesAndCities) {
        let opt = document.createElement("option");
        opt.value = state;
        opt.text = state;
        stateSelect.appendChild(opt);
    }
};

function populateCities() {
    const citySelect = document.getElementById("city");
    const selectedState = document.getElementById("state").value;
    citySelect.innerHTML = '<option value="">-- Select City --</option>';

    if (selectedState && statesAndCities[selectedState]) {
        statesAndCities[selectedState].forEach(city => {
            let opt = document.createElement("option");
            opt.value = city;
            opt.text = city;
            citySelect.appendChild(opt);
        });
    }
}
</script>

</body>
</html>
