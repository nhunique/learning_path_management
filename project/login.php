<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Login</title>
</head>
<body>
    <?php include 'includes/class-autoload.inc.php'?>
    <?php include 'includes/navbar.inc.php'?>
    <?php include 'includes/login.inc.php' ?>

   
    <!-- login form -->
    <div class="container-fluid vertical-center">
        <form action="login.php" method="post">
            <div class="container-xl mt-5">
                <div class="row">
                    <div class="col-sm-3 mx-auto">
                        <h1 class="text-center">Login</h1>
                        <p class="text-center">Fill up your login informaton.</p>
                        <hr class="mb-3">
                        
                        <label for="email"><b>Email</b></label>
                        <input class="form-control" type="email" name="email" id="email" required>
                        
                        <label for="password"><b>Password</b></label>
                        <input class="form-control" type="password" name="password" id="password" required>
                        
                        <hr class="mb-3">
                        <input class="btn btn-primary btn-block" type="submit" name="login" value="Login">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <?php
    // Check if the "error" parameter is present in the URL
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        if (isset($_GET['error'])) {
            $errorCode = $_GET['error'];

            // Display something based on the error code
            switch ($errorCode) {
                case 'none':
                    echo '<script type="text/javascript">alert("Loged in successfully.");</script>';
                    //going back to login page
                    break;
                case 'invalidemail':
                    echo '<script type="text/javascript">alert("Invalid email error!");</script>';
                    break;
                case 'emptyinput':
                    echo '<script type="text/javascript">alert("Empty Inputs error!");</script>';
                    break;
                case 'wrongpassword':
                    echo '<script type="text/javascript">alert("Wrong password error!");</script>';
                    break;
                case 'usernotfound':
                    echo '<script type="text/javascript">alert("User not found error!");</script>';
                    break;
                default:
                echo 'Unknown error!';
                break;
            }
        }
    }
    ?>



    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</html>
<?php include 'includes/footer.inc.php';?>