<!-- navbar-->

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
                    <a href="index.php" class="nav-link">Home</a>
                </li>
                <li class="nav-item">
                    <a href="login.php" class="nav-link">Login</a>
                </li>
                <li class="nav-item">
                    <a href="register.php" class="nav-link">Register</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Learning Path Management
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item" href="view.php">View/Update/Delete</a></li>
                        <li><a class="dropdown-item" href="pathsManager.php">Create</a></li>
                    </ul>
                </li>

                <!-- search box -->
                <div class="container-fluid">
                    <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>

                 <!-- menu member-->
                 <li class="nav-item container" >
                    <div class="row align-items-center">
                        <?php 
                        if(isset($_SESSION['userid'])){
                        ?>
                        <div class="col-auto "><a href="#"><?= $_SESSION["userid"]; ?></a></div>
                        <button class="col-auto btn btn-outline-primary"><a href="includes/logout.inc.php" class="header-login-a">LOG OUT </a></button>
                        <?php
                        }   else {
                        ?>
                        <button class="col-auto btn btn-outline-primary"><a href="#">SIGN UP</a></button>
                        <button class="col-auto btn btn-outline-primary"><a href="#" class="header-login-a">LOG IN</a></button>
                        <?php }?>
                    </div>
                </li>
               
             
               
            </ul>
        </div>
    </div>
</nav>
