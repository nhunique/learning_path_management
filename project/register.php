<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Registration</title>
</head>
<body>
    <?php include 'includes/class-autoload.inc.php'?>
    <?php include 'includes/navbar.inc.php'?>
    <?php require 'includes/register.inc.php'?>

    <?php

    // Check if the "error" parameter is present in the URL
    if (isset($_GET['error'])) {
        $errorCode = $_GET['error'];

        // Display something based on the error code
        switch ($errorCode) {
            case 'none':
                echo '<script type="text/javascript">alert("Register successfully.");</script>';
                //going back to login page
                header("Location: login.php?error=none&signup=success");
                break;
            case 'invalidemail':
                echo '<script type="text/javascript">alert("Invalid email error!");</script>';
                break;
            case 'usertaken':
                echo '<script type="text/javascript">alert("User taken error!");</script>';
                break;
            case 'emptyinput':
                echo '<script type="text/javascript">alert("IEmpty Inputs error!");</script>';
                break;
            case 'wrongpassword':
                echo '<script type="text/javascript">alert("Wrong password error!");</script>';
                break;
            default:
            echo 'unknown error.';
            break;
        }
    }
    ?>

    <!--sign up form -->
    <div>
        <form action="register.php" method="post" enctype="multipart/form-data">
            <div class="container-xl mt-5 ">

                <div class="row">
                    <div class="col-sm-5">
                        <h1>Registration</h1>
                        <p>Fill up the form with correct values.</p>
                        <hr class="mb-3">
                        <label for="firstname"><b>First Name</b></label>
                        <input class="form-control" type="text" name="firstname" id="firstname" required>

                        <label for="lastname"><b>Last Name</b></label>
                        <input class="form-control" type="text" name="lastname" id="lastname" required>

                        <label for="email"><b>Email </b></label>
                        <input class="form-control" type="email" name="email" id="email" required>
                        
                        <label for="password"><b>Password</b></label>
                        <input class="form-control" type="password" name="password" id="password" required>
                        
                        <label for="image">Select Image:</label>
                        <input class="form-control-file" type="file" name="image" id="image" accept="image/*" >
        
                        <hr class="mb-3">
                        <input class="btn btn-primary" type="submit" name="create" value="Sign Up">

                    </div>
                </div>
            </div>
        </form>
    </div>
        
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</html>
