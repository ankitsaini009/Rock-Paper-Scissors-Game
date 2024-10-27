<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['address']);
    $dob = trim($_POST['dob']);
    $cheked = isset($_POST['cheked']) ? 1 : 0;
    if (empty($name)) $errors['name'] = "Please enter your name.";
    if (empty($email)) {
        $errors['email'] = "Please enter your email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }
    if (empty($phone_number)) $errors['phone_number'] = "Please enter your phone number.";
    elseif (!is_numeric($phone_number)) $errors['phone_number'] = "Phone number must be numeric.";
    elseif (strlen($phone_number) < 10) $errors['phone_number'] = "Phone number must be at least 10 digits long.";
    if (empty($address)) $errors['address'] = "Please enter your address.";
    if (empty($dob)) $errors['dob'] = "Please enter your date of birth.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone_number, address, dob, cheked, created) VALUES (?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("ssssss", $name, $email, $phone_number, $address, $dob, $cheked);

        if ($stmt->execute()) {
            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['phone_number'] = $phone_number;
            $_SESSION['message'] = 'Form submitted successfully!';
            $message = 'Form submitted successfully!';
            header("Location: success.php");
            exit();
        } else {
            $errors['sql'] = 'Error: ' . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Form</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php if (isset($message)) { ?>
        <div class="alert alert-success mt-3"><?php echo $message; ?></div>
    <?php } ?>
    <div class="container mt-5">
        <h2 class="text-center">Session Form</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Your Name</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" placeholder="Your name">

                <div class="text-danger"><?php echo isset($errors['name']) ? $errors['name'] : ''; ?></div>
            </div>
            <div class="form-group">
                <label for="email">Your Email</label>
                <input type="email" id="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" name="email" class="form-control" placeholder="Your email">
                <div class="text-danger"><?php echo isset($errors['email']) ? $errors['email'] : ''; ?></div>
            </div>
            <div class="form-group">
                <label for="phone_number">Phone Number</label>
                <input type="Number" id="phone_number" value="<?php echo isset($_POST['phone_number']) ? htmlspecialchars($_POST['phone_number']) : ''; ?>" name="phone_number" class="form-control" placeholder="Your phone number">
                <div class="text-danger"><?php echo isset($errors['phone_number']) ? $errors['phone_number'] : ''; ?></div>
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" id="address" name="address" class="form-control" placeholder="Your address">
                <div class="text-danger"><?php echo isset($errors['address']) ? $errors['address'] : ''; ?></div>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth</label>
                <input type="date" value="<?php echo isset($_POST['date']) ? htmlspecialchars($_POST['date']) : ''; ?>" id="dob" name="dob" class="form-control">
                <div class="text-danger"><?php echo isset($errors['dob']) ? $errors['dob'] : ''; ?></div>
            </div>
            <div class="form-check">
                <input type="checkbox" id="cheked" name="cheked" value="1" class="form-check-input">
                <label for="cheked" class="form-check-label">I agree to the <a href="#">Terms of Service</a></label>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>
</body>

</html>