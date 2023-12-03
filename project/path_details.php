<?php
session_start();
include 'includes/class-autoload.inc.php';
include 'includes/navbar.inc.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Path Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

</head>
<body>

<?php
    if($_SERVER['REQUEST_METHOD'] === 'GET'){

        $learningPath = new LearningPath();

        if (isset($_GET['clone'])) {
        
            //Display clone Path information
            $learningPath->displayClonePathInfo($_SESSION['cloneID']);

        } else if ( isset($_GET['identifier'])) {
            // Handle shared path retrieval
            $sharedIdentifier = $_GET['identifier'];

            $sharedPathID = $learningPath->getPathByUniqueIdentifier($sharedIdentifier);

            $current_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        
            
            if ($sharedPathID) {
                //Display the shared path information
                echo '<br>';
                echo '<p class="pt-10">Copy this link and share to your friends:</p>';
                echo '<a  href="' .$current_url .'" class="card-link">'. $current_url.'</a>';
                echo '<br>';
                $learningPath->viewSpecificPath($sharedPathID[0]['pathID']);
          } else {
                // Handle invalid or expired shared path
                echo "Invalid or Expired Shared Path";
            }
        }
    }
?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>

</html>


<?php include 'includes/footer.inc.php';?>