<?php 

require_once 'config.php';

if($_SERVER['REQUEST_METHOD'] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT admin_id, password FROM admins WHERE username=? ";

    $run = $conn->prepare($sql);
    $run->bind_param("s", $username);
    $run->execute();

    $results = $run->get_result();

    if ($results->num_rows == 1) {
        $admin = $results->fetch_assoc();

        if (password_verify($password, $admin['password'])) {
            $conn->close();
            header('location: admin_dashboard.php');
            $_SESSION['admin_id'] = $admin['admin_id'];
        }
        else {
            $_SESSION['error'] = "Netacan password"; 

            $conn->close();
            header('location: index.php');
            exit();
        }
    }
    else {
        $_SESSION['error'] = "Netacan username";

        $conn->close();
        header('location: index.php');
        exit();
    }

}

?>

<?php
if(isset($_SESSION['error'])){
    echo $_SESSION['error'] . "<br>";
    unset($_SESSION['error']);
}
?>


<html>

    <head>
        <title>Gym</title>

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    </head>



    <body>
        
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card shadow bg-secondary text-white">
                        <div class="card-body p-4">
                            <h2 class="text-center mb-4">Login</h2>
                            <form action="" method="POST" class="needs-validation" novalidate>
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username:</label>
                                    <input type="text" name="username" id="username" class="form-control" required>
                                    <div class="invalid-feedback">Please enter your username.</div>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password:</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    <div class="invalid-feedback">Please enter your password.</div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary">Login</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    </body>

</html>