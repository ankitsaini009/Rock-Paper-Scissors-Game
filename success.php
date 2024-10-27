<?php
include('db.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Display with Bootstrap</title>
    <!-- Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 20px;
        }

        .table-container {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 class="my-4"><?php echo $_SESSION['message'] ?></h1>

        <div class="table-container">


            <div class="container mt-4">
                <table class="table table-striped table-bordered">
                    <tbody>
                        <tr>
                            <th>Name: </th>
                            <td><?php echo $_SESSION['name'] ?></td>
                        </tr>
                        <tr>
                            <th>Email: </th>
                            <td><?php echo $_SESSION['email'] ?></td>
                        </tr>
                        <tr>
                            <th>Phone: </th>
                            <td><?php echo $_SESSION['phone_number'] ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.min.js"></script>
</body>

</html>