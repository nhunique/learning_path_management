<?php session_start(); ?>

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
    <?php include 'includes/pathManager.inc.php'?>

    <!-- Path Manager System  -->
    <div class="container mt-5 mb-5">
        <h1>Path Manager System </h1>
    </div>

    <!-- -->
    <section id="pathManager" class="container">
        <div class="accordion accordion-flush" id="pathManager">
            <div class="accordion-item" id="creare">
                <h2 class="accordion-header" id="flush-headingCreate">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseCreate" aria-expanded="false" aria-controls="flush-collapseCreate">
                    Create
                </button>
                </h2>
                <div id="flush-collapseCreate" class="accordion-collapse collapse" aria-labelledby="flush-headingCreate" data-bs-parent="#pathManager">
                <div class="accordion-body">
                    <form id="createForm" method="post" action="pathsManager.php">
                        <fieldset>
                            <legend>Create A New Path</legend>
                            <input type="hidden" name="userID" value="<?php $_SESSION['userid']?>">
                            <div class="form-group">
                                <label for="title">Title:</label>
                                <input type="text" class="form-control" id="title" placeholder="Enter your title" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea class="form-control" id="description" name="description" rows="5" cols="100" placeholder="Enter your description"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="url">URL:</label>
                                <div id="urlFields">
                                    <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="urlTitles[]" placeholder="Enter URL Title" required>
                                    <input type="url" class="form-control" name="urls[]" placeholder="Enter URL" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" onclick="removeUrlField(this)">Remove</button>
                                    </div>
                                    </div>
                                </div>
                                <button class="btn btn-outline-secondary" type="button" onclick="addUrlField()">Add URL</button>
                                </div>


                            <button type="submit" class="btn btn-primary mt-3" name="create" value="create">Create</button>
                        <fieldset>
                    </form>
                </div>
            </div>
            <div class="accordion-item" id="update">
                <h2 class="accordion-header" id="flush-headingUpdate">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseUpdate" aria-expanded="false" aria-controls="flush-collapseUpdate">
                    Update
                </button>
                </h2>
                <div id="flush-collapseUpdate" class="accordion-collapse collapse" aria-labelledby="flush-headingUpdate" data-bs-parent="#pathManager">
                <div class="accordion-body">
                    
            
                </div>
            </div>
            <div class="accordion-item" id="view">
                <h2 class="accordion-header" id="flush-headingView">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseView" aria-expanded="false" aria-controls="flush-collapseView">
                    View
                </button>
                </h2>
                <div id="flush-collapseView" class="accordion-collapse collapse" aria-labelledby="flush-headingView" data-bs-parent="#pathManager">
                <div class="accordion-body">



                </div>
            </div>
            <div class="accordion-item" id="delete">
                <h2 class="accordion-header" id="flush-headingDelete">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseDelete" aria-expanded="false" aria-controls="flush-collapseDelete">
                    Delete
                </button>
                </h2>
                <div id="flush-collapseDelete" class="accordion-collapse collapse" aria-labelledby="flush-headingDelete" data-bs-parent="#pathManager">
                <div class="accordion-body">


                </div>
            </div>
    </section>



    

    <!-- -->

    
    
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script>
    function addUrlField() {
        const urlFields = document.getElementById('urlFields');
        const newUrlField = document.createElement('div');
        newUrlField.className = 'input-group mb-2';
        newUrlField.innerHTML = `
        <input type="text" class="form-control" name="urlTitles[]" placeholder="Enter URL Title" required>
        <input type="url" class="form-control" name="urls[]" placeholder="Enter URL" required>
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
