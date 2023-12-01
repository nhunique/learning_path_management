<?php 
    session_start();
    isset($_SESSION['userid']) ? $_SESSION['userid'] : "";
    isset($_GET['vote']) ? $_GET['vote'] :"";
    isset($_GET['error']) ?$_GET['error'] : "" ;
    include 'includes/class-autoload.inc.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <title>Home</title>
</head>
<body>
    <?php include 'includes/navbar.inc.php'?>

        <?php
        if($_SESSION['email']){
            $view = new UserView($_SESSION['email']);?>
            <div class="container mr-10 mt-5">
            <?php $view->helloName($_SESSION['email']); ?>
        
            </div>
        <?php } else {
            echo "You need to login.";
            header("Location: login.php");
        }
        ?>
    
    <div class="container">
        <h2 class="container-heading mt-5 mb-5">Explore Learning Paths </h2>
        <?php
        $allPaths= new LearningPath();
        $allPaths->displayLearningPaths();

        ?>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</html>

<?php
    // Check if the "error" parameter is present in the URL
    if($_SERVER['REQUEST_METHOD'] === 'GET'){
        if (isset($_GET['vote'])) {
           

            // Display something based on the error code
            switch ($_GET['vote']) {
                case 'up':
                    echo '<script type="text/javascript">alert("Upvote successfully.");</script>';
                    //going back to login page
                    break;
                case 'down':
                    echo '<script type="text/javascript">alert("Downvote Successfully!");</script>';
                    break;
               
                default:
                echo 'Unknown error!';
                break;
            }
        }
        else if(isset($_GET['error']) === 'already_voted'){
            echo '<script type="text/javascript">alert("Already voted.");</script>';

        } else if( isset($_GET['error']) === 'stmtfailed'){
            echo '<script type="text/javascript">alert("Fetching data failed.");</script>';

        } 
        else if(isset($_GET['delete']) == 'success'){
            echo '<script type="text/javascript">alert("Delete Clone Path successfully!");</script>';
            
        } else if(isset($_GET['update']) == 'success'){
            echo '<script type="text/javascript">alert("Update Clone Path successfully!");</script>';
            
        }
    }
    ?>
