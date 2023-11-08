<?php 

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
        <div class="d-block w-100">
            <nav class="navbar navbar-expand-lg bg-body-tertiary">
                <div class="container-fluid">
                    <h4> Vendo Central </h4>
                    <div class="collapse navbar-collapse d-flex justify-content-end" id="navbarSupportedContent">
                    <form method="post">
                        <button type="submit" name="logout" class="btn btn-outline-danger">Log Out</button>
                    </form>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.9/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>