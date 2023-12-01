<?php 
    session_start(); 
    isset($_SESSION['email']) ? $_SESSION['email'] : " ";
    $error = isset($_GET['error']) ? $_GET['error'] :null;
    checkErrorCode($error);

    //Check error code
    function checkErrorCode($errorCode){
        // Check if the "error" parameter is present in the URL
        if (isset($_GET['error'])) {
            $errorCode = $_GET['error'];

            // Display something based on the error code
            switch ($errorCode) {
                case 'none':
                    echo '<script type="text/javascript">alert("Create/Update/Delete successfully.");</script>';
                    break;
                case 'invaliduser':
                    echo "Invalid email error!";
                    break;
                case 'delete_failed':
                    echo '<script type="text/javascript">alert("Delete Failed. Try later.");</script>';
                    break;
                case 'update_failed':
                    echo '<script type="text/javascript">alert("Update Failed. Try later.");</script>';
                    break;
                case 'urlsupdatefailed':
                    echo '<script type="text/javascript">alert("Update Failed. Try later.");</script>';
                    break;
                case 'urlinsertfailed':
                    echo '<script type="text/javascript">alert("Update/Delete Failed. Try later.");</script>';
                    break;
                case 'update_failed':
                    echo '<script type="text/javascript">alert("Update/Delete Failed. Try later.");</script>';
                    break;
                case 'emptyinput':
                    echo "Empty input error!";
                    break;
                case 'stmtfailed':
                    echo '<script type="text/javascript">alert("Create/Update/Delete Failed. Try later.");</script>';
                    break;
                    
                default:
                    echo "Unknown error!";
                    break;
            }
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Learning Paths Management</title>
</head>
<body>
    <?php include 'includes/class-autoload.inc.php'?>
    <?php include 'includes/navbar.inc.php'?>
    <?php include 'includes/pathsManager.inc.php'?>

   
    
   <section class="container">
        <div class="container" id="view">
        <h1 class="container-heading">View Your Learning Paths</h1>
        <?php     
                
            $email       = $_SESSION['email'];
            LearningPathContr::viewLearningPath($email); 

        ?>
        </div>
    </section>
    


</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</html>