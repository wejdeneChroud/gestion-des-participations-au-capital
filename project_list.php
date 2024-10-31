<?php
include 'functions.php';

redirectIfNotLoggedIn();

$conn = establishConnection();

$sql = "SELECT id_projet, titre, description, nombre_actions_a_vendre, nombre_actions_vendues, prix_action 
        FROM projet 
        WHERE id_startuper IN (
            SELECT id_startuper FROM startuper WHERE pseudo = ?
        )";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();

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
            <li class="nav-item mx-2"><a class="nav-link active" href="add_project.php">New</a></li>
            <li class="nav-item mx-2"><a class="nav-link active" href="#">Projects</a></li>
        </ul>
        <a href="login.php"><button id="logoutbtn">Log out</button></a>
    </nav><br><br><br>

    <div class="parallax">
        <div class="container" action="" method="post">
            <div class="card-body pb-2">
                <h2>Projects submitted by <?php echo $_SESSION['username']; ?></h2><br><br>

                <?php
                if ($result && $result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $shares_unsold = $row['nombre_actions_a_vendre'] - $row['nombre_actions_vendues'];
                        echo "<div>";
                        echo "<h2>¤ " . $row['titre'] . "</h2>";
                        echo "<button class='btn btn-primary' onclick=\"openPopup('popup_" . $row['id_projet'] . "')\">Learn More</button>";
                        echo "</div><br><br>";

                        // Popup avec toutes les informations du projet
                        echo "<div id='popup_" . $row['id_projet'] . "' class='popup' style='display:none;'>";
                        echo "<span class='close-popup' onclick=\"closePopup('popup_" . $row['id_projet'] . "')\">&times;</span><br>";
                        echo "<h3>" . $row['titre'] . "</h3><br>";
                        echo "<p class='description'><strong>Description : </strong> " . nl2br($row['description']) . "</p>";
                        echo "<p><strong>Shares unsold : </strong> $shares_unsold</p>";
                        echo "<p><strong>Shares sold : </strong> " . $row['nombre_actions_vendues'] . "</p>";
                        echo "<p><strong>shares Price : </strong> " . $row['prix_action'] . "</p><br>";
                        if ($row['nombre_actions_vendues'] == 0) {
                            echo "<form method='post'>";
                            echo "<input type='hidden' name='projectId' value='" . $row['id_projet'] . "'>";
                            echo "<button type='submit' class='btn btn-primary' name='delete-project'>Delete</button>";
                            echo "</form>";
                        }
                        echo "</div>";
                    }
                } else {
                    echo "No projects submitted yet. Let's get Started with WAVE!";
                }
                ?>

            </div>
        </div>
        <br><br><br><br><br><br><br><br><br><br>
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
        <div id="overlay" class="overlay hidden"></div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="scripts.js"></script>
</body>

</html>

<?php
if (isset($_POST['delete-project'])) {
    $projectId = $_POST['projectId'];
    deleteProject($conn, $projectId);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
} 

$conn->close();
?>