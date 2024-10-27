<?php
include('db.php');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $errors = [];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['Address']);
    $dob = trim($_POST['DOB']);
    $cheked = isset($_POST['cheked']) ? 1 : 0;

    if (empty($name)) {
        $errors['nameerror'] = "Please enter your name.";
    }
    if (empty($email)) {
        $errors['emailerror'] = "Please enter your email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['emailerror'] = "Invalid email format.";
    }
    if (empty($phone_number)) {
        $errors['phoneerror'] = "Please enter your phone number.";
    } elseif (!is_numeric($phone_number)) {
        $errors['phoneerror'] = "Phone number must be numeric.";
    } elseif (strlen($phone_number) < 10) {
        $errors['phoneerror'] = "Phone number must be at least 10 digits long.";
    }
    if (empty($address)) {
        $errors['addresserror'] = "Please enter your address.";
    }
    if (empty($dob)) {
        $errors['doberror'] = "Please enter your date of birth.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO users (name, email, phone_number, Address, DOB,cheked,created) VALUES (?, ?, ?, ?, ?,?,NOW())");
        $stmt->bind_param("ssssss", $name, $email, $phone_number, $address, $dob, $cheked);
                   
        if ($stmt->execute()) {

            $_SESSION['name'] = $name;
            $_SESSION['email'] = $email;
            $_SESSION['phone_number'] = $phone_number;

            $response = ['success' => true, 'message' => 'Form submitted successfully!'];
        } else {
            $response = ['success' => false, 'errors' => ['sqlerror' => 'Error: ' . $stmt->error]];
        }
    } else {
        $response = ['success' => false, 'errors' => $errors];
    }

    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Section Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <section class="vh-100" style="background-color: #eee;">
        <div class="container h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-lg-12 col-xl-11">
                    <div class="card text-black" style="border-radius: 25px;">
                        <div class="card-body p-md-5">
                            <div class="row justify-content-center">
                                <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">

                                    <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Sign up</p>


                                    <form id="signupForm2">
                                        <input type="hidden" name="id" value="<?php echo htmlspecialchars(isset($id) ? $id : ''); ?>">

                                        <div class="form-group mb-4">
                                            <label for="name" class="form-label"><i class="fas fa-user me-2"></i>Your Name</label>
                                            <input type="text" id="name" name="name" class="form-control" value="<?php echo isset($name) ? $name : ''; ?>" placeholder="your name">
                                            <div class="text-danger" id="nameerror"></div>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="email" class="form-label"><i class="fas fa-envelope me-2"></i>Your Email</label>
                                            <input type="email" id="email" name="email" class="form-control" value="<?php echo isset($email) ? $email : ''; ?>" placeholder="your email">
                                            <div class="text-danger" id="emailerror"></div>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="phone_number" class="form-label"><i class="fas fa-lock me-2"></i>Phone Number</label>
                                            <input type="text" id="phone_number" name="phone_number" class="form-control" value="<?php echo isset($phone_number) ? $phone_number : ''; ?>" placeholder="your Phone Number">
                                            <div class="text-danger" id="phoneerror"></div>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="Address" class="form-label"><i class="fas fa-key me-2"></i>Address</label>
                                            <input type="text" name="Address" id="Address" class="form-control" value="<?php echo isset($Address) ? $Address : ''; ?>" placeholder="your Address">
                                            <div class="text-danger" id="addresserror"></div>
                                        </div>

                                        <div class="form-group mb-4">
                                            <label for="DOB" class="form-label"><i class="fas fa-key me-2"></i>DOB</label>
                                            <input type="date" name="DOB" id="DOB" class="form-control" value="<?php echo isset($DOB) ? $DOB : ''; ?>">
                                            <div class="text-danger" id="doberror"></div>
                                        </div>

                                        <div class="form-check mb-4">
                                            <input class="form-check-input" type="checkbox" name="cheked" value="1" id="cheked">
                                            <label class="form-check-label" for="terms">
                                                I agree to the <a href="#">Terms of Service</a>
                                            </label>
                                        </div>

                                        <div class="d-flex justify-content-center">
                                            <button type="submit" class="btn btn-primary btn-lg text-center">Save</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                                    <img src="https://www.hubspot.com/hs-fs/hubfs/contact-form_0.webp?width=650&height=394&name=contact-form_0.webp"
                                        class="img-fluid" alt="Sample image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('#signupForm2').on('submit', function(e) {
                e.preventDefault();

                var formData = $(this).serialize();

                $.ajax({
                    type: 'POST',
                    url: 'index.php',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        if (response.success) {
                            $('#responseMessage').html('<div class="alert alert-success">' + response.message + '</div>');
                            $('#signupForm2')[0].reset();
                            $('.text-danger').empty();
                            setTimeout(function() {
                                window.location.href = 'Success.php';
                            }, 1000);
                        } else {
                            $('#responseMessage').html('');
                            $.each(response.errors, function(key, value) {
                                $('#' + key).text(value);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#responseMessage').html('<div class="alert alert-danger">An error occurred: ' + error + '</div>');
                    }
                });
            });
        });

        function confirmDeletion() {
            return confirm('Are you sure you want to delete this item?');
        }
    </script>
</body>

</html>