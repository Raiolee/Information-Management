<?php 
// Log out function
if (isset($_POST['logout'])) {
    unset($_SESSION['user']);
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>

<body>

    <!-- Header -->
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="homepage.php">Vendo Central</a>
                <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="homepage.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="order.php">Orders</a>
                        </li>
                    </ul>
                    <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarSupportedContent">
                        <form class="d-flex" method="post" action="SearchBar.php"> 
                            <input class="form-control me-2" type="search" name="searchBar" placeholder="Search" aria-label="Search">
                            <button class="btn btn-outline-success" type="submit" name="search">Search</button>
                        </form>
                    </div>
                    <div class="row mx-2">
                        <a href="cart.php">
                            <button class="btn btn-outline-primary" type="submit">Cart</button>
                        </a>
                    </div>
                    <form method="post">
                        <button type="submit" name="logout" class="btn btn-outline-danger">Log Out</button>
                    </form>
                </div>
            </div>
        </nav>
    </header>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>