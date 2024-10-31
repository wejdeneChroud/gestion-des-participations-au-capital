<?php
include 'functions.php';

redirectIfNotLoggedIn();

$conn = establishConnection();

$sql = "SELECT id_startuper FROM startuper WHERE pseudo='" . $_SESSION['username'] . "'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $user_id = $row['id_startuper'];
} else {
    echo "User information not found.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $shares = $_POST['shares'];
    $price = $_POST['prices'];
    $sold = 0;
    $id_startuper = $user_id;

    $sql = "INSERT INTO projet (titre, description, nombre_actions_a_vendre, nombre_actions_vendues, prix_action, id_startuper)
            VALUES ('$title', '$description', '$shares', $sold, '$price', '$id_startuper')";

    if ($conn->query($sql) === FALSE) {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WAVE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <nav class="navbar bg-light fixed-top px-5">
        <a class="navbar-brand fs-4" href="#">WAVE</a>
        <ul class="navbar-nav d-flex flex-row justify-content-center align-items-center flex-grow-1 pe-3">
            <li class="nav-item mx-2"><a class="nav-link active" href="profile.php">Profile</a></li>
            <li class="nav-item mx-2"><a class="nav-link active" href="change_password.php">Password</a></li>
            <li class="nav-item mx-2"><a class="nav-link active" href="#">New</a></li>
            <li class="nav-item mx-2"><a class="nav-link active" href="project_list.php">Projects</a></li>
        </ul>
        <a href="login.php"><button id="logoutbtn">Log out</button></a>
    </nav><br><br><br>

    <div class="parallax">
        <form id="myForm" class="container" action="" method="post">
            <div class="card-body pb-2">
                <div class="input-field">
                    <label class="form-label">Project title</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Project Title" require>
                </div>
                <div class="input-field">
                    <label class="form-label">Project description</label>
                    <textarea class="form-control" rows="5" id="description" name="description"></textarea>
                </div>
                <div class="input-field">
                    <label class="form-label">Number of Shares</label>
                    <input type="number" class="form-control" placeholder="5" id="shares" name="shares">
                </div>
                <div class="input-field">
                    <label class="form-label">Share Prices</label>
                    <input type="number" class="form-control" placeholder="100" id="prices" name="prices">
                </div>
            </div>
            <div class="px-3">
                <button type="submit" class="btn btn-primary" name="add-project">Submit</button>
                &nbsp;
                <button type="reset" class="btn btn-default" onclick="resetForm()">Cancel</button>
            </div>
        </form>
        <br><br><br><br><br><br>
        <section>
            <div class="wave" id="wave1" style="--i:1;"></div>
            <div class="wave" id="wave2" style="--i:2;"></div>
            <div class="wave" id="wave3" style="--i:3;"></div>
            <div class="wave" id="wave4" style="--i:4;"></div>
        </section>
        <div class="filler_element">
            <footer class="py-3 bg-transparent text-light">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <h5>Contact Us</h5>
                            <ul class="list-unstyled">
                                <li>Email: info@wave.com</li>
                                <li>Phone: +1 (123) 456-7890</li>
                                <li>Address: 123 Street, City, Country</li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h5>Follow Us</h5>
                            <ul class="list-unstyled">
                                <a href="#"><i class="fab fa-facebook-f"></i></a>
                                <a href="#"><i class="fab fa-instagram"></i></a>
                                <a href="#"><i class="fab fa-x"></i></a>
                                <a href="#"><i class="fab fa-telegram"></i></a>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h5>About Us</h5>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <hr class="bg-light my-4">
                    <p class="mb-0">© 2024 WAVE. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="scripts.js"></script>
</body>

</html>