<?php
include 'functions.php';

redirectIfNotLoggedIn();

$conn = establishConnection();

$sql = "SELECT id_projet, titre, description, nombre_actions_a_vendre, nombre_actions_vendues, prix_action 
        FROM projet";

if (isset($_POST['search'])) {
    $keyword = $_POST['keyword'];
    $sql .= " WHERE description LIKE '%$keyword%'";
}

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Projets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .description {
            max-width: 700px;
            word-wrap: break-word;
        }
    </style>
</head>

<body>
    <nav class="navbar bg-light fixed-top px-5">
        <a class="navbar-brand fs-4" href="#">WAVE</a>
        <ul class="navbar-nav d-flex flex-row justify-content-center align-items-center flex-grow-1 pe-3">
            <li class="nav-item mx-2"><a class="nav-link active" href="#">Explore</a></li>
            <li class="nav-item mx-2"><a class="nav-link active" href="capital_projets.php">My Projects</a></li>
        </ul>
        <a href="login.php"><button id="logoutbtn">Log out</button></a>
    </nav><br><br><br>

    <div class="parallax">
        <div class="container mt-5">
            <h2>Explore Projects</h2>
            <form method="post" class="form-inline mt-3 mb-3">
                <input type="text" class="form-control mr-2" name="keyword" placeholder="Research by keywords">
                <button type="submit" class="btn btn-primary" name="search">Rechercher</button>
            </form>
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result && $result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $shares_unsold = $row['nombre_actions_a_vendre'] - $row['nombre_actions_vendues'];
                            if ($shares_unsold > 0) {
                                echo "<tr onclick=\"openPopup('popup_" . $row['id_projet'] . "')\">";
                                echo "<td>" . $row['titre'] . "</td>";
                                echo "<td class='description'>" . $row['description'] . "</td>";
                                echo "</tr>";
                                // Popup avec toutes les informations du projet
                                echo "<div id='popup_" . $row['id_projet'] . "' class='popup' style='display:none;'>";
                                echo "<span class='close-popup' onclick=\"closePopup('popup_" . $row['id_projet'] . "')\">&times;</span><br>";
                                echo "<h3>" . $row['titre'] . "</h3><br>";
                                echo "<p class='description'><strong>Description : </strong> " . nl2br($row['description']) . "</p>";
                                echo "<p><strong>Shares unsold : </strong> $shares_unsold</p>";
                                echo "<p><strong>shares Price : </strong> " . $row['prix_action'] . "</p><br>";
                                echo "<form method='post'>";
                                echo "<input type='hidden' name='projectId' value='" . $row['id_projet'] . "'>";
                                echo "<label for='actions'>Number of actions to buy  :</label>";
                                echo "<input type='number' id='actions' name='actions' min='1' max='$shares_unsold' required>";
                                echo "<button type='submit' class='btn btn-primary' name='buy-actions'>Buy Actions</button>";
                                echo "</form>";
                                echo "</div>";
                            }
                        }
                    } else {
                        echo "<tr><td colspan='2'>No projects submitted.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="scripts.js"></script>
</body>

</html>

<?php
if (isset($_POST['buy-actions'])) {
    $projectId = $_POST['projectId'];
    $actions_to_buy = $_POST['actions'];
    $id_capital_risque = getCapitalRisqueId($conn, $_SESSION['username']);
    if ($id_capital_risque) {

        $sql = "SELECT nombre_actions_a_vendre, nombre_actions_vendues FROM projet WHERE id_projet = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $projectId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $shares_unsold = $row['nombre_actions_a_vendre'] - $row['nombre_actions_vendues'];
            if ($actions_to_buy <= $shares_unsold) {
                // Vérifier si le projet existe déjà dans la table capital_risque_projet
                $checkSql = "SELECT * FROM capital_risque_projet WHERE id_projet = ? AND id_capital_risque = ?";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->bind_param("ii", $projectId, $id_capital_risque);
                $checkStmt->execute();
                $checkResult = $checkStmt->get_result();
                if ($checkResult->num_rows > 0) {
                    // Mise à jour du nombre d'actions achetées
                    $updateSql = "UPDATE capital_risque_projet SET nombre_actions_achetees = nombre_actions_achetees + ? WHERE id_projet = ? AND id_capital_risque = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param("iii", $actions_to_buy, $projectId, $id_capital_risque);
                    $updateStmt->execute();
                } else {
                    // Insérer les détails de l'achat
                    $insertSql = "INSERT INTO capital_risque_projet (id_projet, id_capital_risque, nombre_actions_achetees) VALUES (?, ?, ?)";
                    $insertStmt = $conn->prepare($insertSql);
                    $insertStmt->bind_param("iii", $projectId, $id_capital_risque, $actions_to_buy);
                    $insertStmt->execute();
                }
                // Mettre à jour le nombre d'actions vendues
                $updateSql = "UPDATE projet SET nombre_actions_vendues = nombre_actions_vendues + ? WHERE id_projet = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("ii", $actions_to_buy, $projectId);
                $updateStmt->execute();
                echo "<script>window.location.href = window.location.pathname;</script>";
                exit();
            } else {
                echo "<script>alert('The number of shares requested exceeds the number of shares remaining for sale.')</script>";
            }
        }
    } else {
        echo "<script>alert('Venture capital not found. Please Login again')</script>";
    }
}

$conn->close();
?>