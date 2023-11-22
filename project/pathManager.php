<?php
require_once('dbconfig.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Learning Path Management</title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
    <header>
        <!-- nav -->
        <nav class="navbar navbar-expand-md navbar-light">
            <div class="container-xxl">
                <a href="#" class="navbar-brand">
                    <span class="fw-bold text-secondary">
                        Learning Path Creator
                    </span>
                </a>
                <!-- toggle button for mobile nav -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" 
                data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false"
                aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- navbar links -->
                <div class="collapse navbar-collapse justify-content-end align-center" id="main-nav">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a href="home.php" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item">
                            <a href="login.php" class="nav-link">Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="registration.php" class="nav-link">Registration</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="pathManager.php" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Learning Path Management
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="create.php">Create</a></li>
                                <li><a class="dropdown-item" href="update.php">Update</a></li>
                                <li><a class="dropdown-item" href="view.php">View</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="delete.php">Delete</a></li>
                            </ul>
                        </li>
                        <form class="d-flex" role="search">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit">Search</button>
                        </form>
                    </ul>
                </div>
                
            </div>
        </nav>
    </header>
    <main>
        <!-- section -->
        <section id="create">
            <div class="container-lg">
                <div class="row justify-content-center align-items-center">
                    <div class="col-md-5 text-center text-md-start">
                        <h1>
                            <div class="display-2">Create</div>
                            <div class="display-1">Title</div>
                        </h1>
                            <p class="lead my-4 text-muted">Lorem ipsum dolor sit amet consectetur adipisicing elit. 
                                Nemo quos qui nobis, nisi facere tempora.</p>
                            <a href="#" class="btn btn-secondary btn-lg">Create</a>
                    </div>
    
                </div>
            </div>
        </section>

        <section id="view">
            
        </section>

        <section id="delete">
            
        </section>
    </main>
    <footer>

    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>