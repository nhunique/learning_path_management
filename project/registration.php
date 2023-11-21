<?php
require_once('config.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
<div>
    <?php
    if(isset($_POST['create'])){
        $email         = $_POST['email'];
        $firstname     = $_POST['firstname'];
        $lastname      = $_POST['lastname'];
        $password      = $_POST['password'];
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        

        $sql = "INSERT INTO users (first_name, last_name, email, password_hash)
            VALUES(?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([$firstname, $lastname, $email, $password_hash]);

        if($result){
            echo "Successfully saved.";
        } else {
            echo "Error in saving.";
        }
        echo "\nHi ".$firstname . " " . $lastname;
    }
    
    ?>
</div>


<div>
    <form action="registration.php" method="post">
        <div class="container">

            <div class="row">
                <div class="col-sm-3">
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
                    <hr class="mb-3">
                    <input class="btn btn-primary" type="submit" name="create" value="Sign Up">
                </div>
            </div>
        </div>
    </form>
</div>
    
</body>
</html>