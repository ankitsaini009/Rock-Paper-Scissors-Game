<?php
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // echo '<pre>';print_r();die;
    $errors = [];
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $address = trim($_POST['Address']);
    $dob = trim($_POST['DOB']);
    $cheked = isset($_POST['cheked']) ? 1 : 0;
    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;


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
        if ($id) {
            $stmt = $conn->prepare("UPDATE users SET name = ?, email = ?,cheked = ?, phone_number = ?, Address = ?, DOB = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $name, $email, $phone_number, $address, $dob, $id, $cheked);
            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Form Update successfully!'];
            } else {
                $response = ['success' => false, 'errors' => ['sqlerror' => 'Error: ' . $stmt->error]];
            }
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, email, phone_number, Address, DOB,cheked,created) VALUES (?, ?, ?, ?, ?,?,NOW())");
            $stmt->bind_param("ssssss", $name, $email, $phone_number, $address, $dob, $cheked);

            if ($stmt->execute()) {
                $response = ['success' => true, 'message' => 'Form submitted successfully!'];
            } else {
                $response = ['success' => false, 'errors' => ['sqlerror' => 'Error: ' . $stmt->error]];
            }
        }
    } else {
        $response = ['success' => false, 'errors' => $errors];
    }

    echo json_encode($response);
    exit;
}

if (isset($_GET["type"]) &&  $_GET["type"] == "delete") {

    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
} elseif (isset($_GET['type']) && $_GET['type'] === 'edit') {
    $id = (int) $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    if ($user) {
        $name = htmlspecialchars($user['name']);
        $email = htmlspecialchars($user['email']);
        $phone_number = htmlspecialchars($user['phone_number']);
        $Address = htmlspecialchars($user['Address']);
        $DOB = htmlspecialchars($user['DOB']);
    }
}


$defaultRecordsPerPage = 8;
$recordsPerPage = isset($_GET['records_per_page']) ? (int)$_GET['records_per_page'] : $defaultRecordsPerPage;
if ($recordsPerPage <= 0) {
    $recordsPerPage = $defaultRecordsPerPage;
}

$pageNumber = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($pageNumber - 1) * $recordsPerPage;

$searchQuery = isset($_GET['search']) ? $_GET['search'] : '';

$totalRecordsQuery = "SELECT COUNT(*) as total FROM users WHERE name LIKE '%" . $conn->real_escape_string($searchQuery) . "%'";
$totalRecordsResult = $conn->query($totalRecordsQuery);
$totalRecordsRow = $totalRecordsResult->fetch_assoc();
$totalRecords = $totalRecordsRow['total'];

$totalPages = ceil($totalRecords / $recordsPerPage);

$sql = "SELECT * FROM users WHERE name LIKE '%" . $conn->real_escape_string($searchQuery) . "%' ORDER BY id DESC LIMIT $start, $recordsPerPage";
$result = $conn->query($sql);   
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User</title>
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

                                    <form id="signupForm">
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
                                    <img src="https://static.vecteezy.com/system/resources/thumbnails/023/588/162/small_2x/register-new-user-account-registration-form-or-submission-sign-up-information-online-apply-new-job-or-membership-self-service-concept-businesswoman-with-pencil-complete-online-registration-form-vector.jpg"
                                        class="img-fluid" alt="Sample image">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>
    <section class="py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12" style="padding-top:200px;">
                    <div class="card">
                        <div id="responseMessage"></div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-5">
                                    <a class="btn btn-success" target="_blank" href="sesstion.php">Session Form</a>
                                </div>
                                <div class="col-md-7 text-md-end">
                                    <h4 class="card-title mb-4">User Data Table</h4>
                                </div>
                            </div>
                            <div id="dataTable">
                                <form method="get" class="mb-3">
                                    <div class="form-group col-md-2">
                                        <label for="records_per_page">Records per Page:</label>
                                        <select name="records_per_page" id="records_per_page" class="form-control" onchange="this.form.submit()">
                                            <option value="8" <?php if ($recordsPerPage == 8) echo 'selected'; ?>>8</option>
                                            <option value="15" <?php if ($recordsPerPage == 15) echo 'selected'; ?>>15</option>
                                            <option value="25" <?php if ($recordsPerPage == 25) echo 'selected'; ?>>25</option>
                                            <option value="50" <?php if ($recordsPerPage == 50) echo 'selected'; ?>>50</option>
                                        </select>
                                    </div>
                                </form>
                                <div id="listingSection">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Email</th>
                                                <th scope="col">Phone Number</th>
                                                <th scope="col">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($result->num_rows > 0) {
                                                $rowNumber = $start + 1;
                                                while ($row = $result->fetch_assoc()) {
                                            ?>
                                                    <tr>
                                                        <th scope="row"><?php echo $rowNumber++; ?></th>
                                                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['phone_number']); ?></td>
                                                        <td>
                                                            <a href="index.php?id=<?php echo urlencode($row['id']); ?>&type=edit" class="btn btn-warning btn-sm">Edit</a>
                                                            <a href="index.php?id=<?php echo urlencode($row['id']); ?>&type=delete"
                                                                class="btn btn-danger btn-sm"
                                                                onclick="return confirmDeletion();">Delete</a>
                                                        </td>
                                                    </tr>
                                                <?php
                                                }
                                            } else {
                                                ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">No records found</td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                                <nav aria-label="Page navigation">
                                    <ul class="pagination">
                                        <li class="page-item <?php if ($pageNumber <= 1) echo 'disabled'; ?>">
                                            <a class="page-link" href="?page=<?php echo $pageNumber - 1; ?>&records_per_page=<?php echo $recordsPerPage; ?>&search=<?php echo urlencode($searchQuery); ?>" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                            </a>
                                        </li>

                                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?php if ($i == $pageNumber) echo 'active'; ?>">
                                                <a class="page-link" href="?page=<?php echo $i; ?>&records_per_page=<?php echo $recordsPerPage; ?>&search=<?php echo urlencode($searchQuery); ?>"><?php echo $i; ?></a>
                                            </li>
                                        <?php endfor; ?>

                                        <li class="page-item <?php if ($pageNumber >= $totalPages) echo 'disabled'; ?>">
                                            <a class="page-link" href="?page=<?php echo $pageNumber + 1; ?>&records_per_page=<?php echo $recordsPerPage; ?>&search=<?php echo urlencode($searchQuery); ?>" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('#signupForm').on('submit', function(e) {
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
                            $('#signupForm')[0].reset();
                            $('.text-danger').empty();
                            $('html, body').animate({
                                scrollTop: $('#listingSection').offset().top
                            }, 1000, function() {
                                setTimeout(function() {
                                    window.location.reload();
                                }, 3000);
                            });
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