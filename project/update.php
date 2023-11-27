<?php 
    session_start(); 
    isset($_SESSION['email']) ? $_SESSION['email'] : " ";
    isset($_POST['update']) ? $_POST['update'] : " ";
  
  
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

   <h1>Update</h1>
   <section class="container">
        <div class="container" id="view">
        <h1 class="container-heading">View</h1>
        <?php     
            if($_SERVER['REQUEST_METHOD'] === 'POST'){
                $pathID       = $_POST['pathID'];
                $email =$_SESSION['email'];
                $learningPath = new LearningPath();
               
               
                $paths = $learningPath->getSpecificPath($pathID);

                $urls = $learningPath->getUrls( $pathID);
                $numOfUrls = count($urls);
                echo "NumOfUrls: " . $numOfUrls;
                
                foreach ($paths as $num => $path) {
                    $pathTitle = $paths[$num]['title'];
                    $pathDescription = $paths[$num]['description'];

                }
            }
            ?>

            <form id="updateForm" method="post" action="update-process.php">
                <fieldset>
                    <legend>Update New Learning Path</legend>
                    
                    <div class="form-group">
                        <label for="title">Title:</label>
                        <input type="text" class="form-control" id="title" name="updateTitle" value="<?= $pathTitle?>">
                    </div>
                    <div class="form-group">
                        <label for="description">Description:</label>
                        <input class="form-control" id="description" name="updateDescription" rows="5" cols="100" value="<?= $pathDescription ?>">
                    </div>
                   

                    <div class="form-group">
                        <label for="url">URL:</label>
                        <div id="urlFields">
                            <?php 
                            echo '<ul class="list-group list-group-flush">';
                            foreach ($urls as $num => $url) {
                                $urlTitle = $urls[$num]['urlTitle'];
                                $urlLink = $urls[$num]['urlLink'];
                                echo '<li class="list-group-item">';
                                echo '<div class="input-group mb-2">';
                                echo '<input type="text" class="form-control" name="updateUrlTitles[]" value="' . htmlspecialchars($urlTitle) . '">';
                                echo '<input type="url" class="form-control" name="updateUrlLinks[]" value="' . htmlspecialchars($urlLink) . '">';
                                echo '<div class="input-group-append">';
                                echo '<button class="btn btn-outline-secondary" type="button" onclick="removeUrlField(this)">Remove</button>';
                                echo '</div>';
                                echo '</div>';
                                echo '</li>';
                            }
                            echo '</ul>';
                            ?>
                        </div>
                    </div>

                    </div>
                        <input type="hidden" name="pathID" value="<?=$pathID?>">

                        <button class="btn btn-outline-secondary" type="button" onclick="addUrlField()">Add URL</button>
                    </div>
                    <div>
                    <button type="submit" class="btn btn-primary mt-3" name="update" value="update">Update</button>
                    </div>
                <fieldset>
            </form>
        </div>
    </section>





</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script>
    function addUrlField() {
        const urlFields = document.getElementById('urlFields');
        const newUrlField = document.createElement('div');
        newUrlField.className = 'input-group mb-2';
        newUrlField.innerHTML = `
        <input type="text" class="form-control" name="updateUrlTitles[]" placeholder="Enter URL Title" required>
        <input type="url" class="form-control" name="updateUrlLinks[]" placeholder="Enter URL" required>
        <div class="input-group-append">
            <button class="btn btn-outline-secondary" type="button" onclick="removeUrlField(this)">Remove</button>
        </div>
        `;
        urlFields.appendChild(newUrlField);
    }

    function removeUrlField(button) {
        const urlField = button.closest('.input-group');
        urlField.remove();
    }
</script>
</html>