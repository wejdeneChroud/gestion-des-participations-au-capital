<?php
include 'functions.php';

redirectIfNotLoggedIn();

$conn = establishConnection();

$sql = "SELECT * FROM startuper WHERE pseudo='" . $_SESSION['username'] . "'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();

    $profileImageBinary = $user['photo'];
    $profileImageBase64 = base64_encode($profileImageBinary);
    $username = $user['pseudo'];
    $firstname = $user['prenom'];
    $lastname = $user['nom'];
    $cin = $user['CIN'];
    $email = $user['email'];
    $companyName = $user['nom_entreprise'];
    $companyCode = $user['numero_registre_commerce'];
    $companyAddress = $user['adresse_entreprise'];
} else {
    echo "<script>alert('Error: User information not found.');</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = $_POST['username'];
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $cin = $_POST['cin'];
    $email = $_POST['email'];
    $companyName = $_POST['companyName'];
    $companyCode = $_POST['companyCode'];
    $companyAddress = $_POST['companyAddress'];

    // Check if a new photo is uploaded
    if ($_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo = file_get_contents($_FILES["photo"]["tmp_name"]); // Get the image data

        // Update user information including the new photo
        $sql = "UPDATE startuper SET nom=?, prenom=?, CIN=?, email=?, nom_entreprise=?, adresse_entreprise=?, numero_registre_commerce=?, photo=? WHERE pseudo=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssss", $lastname, $firstname, $cin, $email, $companyName, $companyAddress, $companyCode, $photo, $_SESSION['username']);
    } else {
        // Update user information without changing the photo
        $sql = "UPDATE startuper SET nom=?, prenom=?, CIN=?, email=?, nom_entreprise=?, adresse_entreprise=?, numero_registre_commerce=? WHERE pseudo=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $lastname, $firstname, $cin, $email, $companyName, $companyAddress, $companyCode, $_SESSION['username']);
    }

    // Execute the update query
    if ($stmt->execute()) {
        echo "User information updated successfully.";
    } else {
        echo "Error while updating user information: " . $conn->error;
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
            <li class="nav-item mx-2"><a class="nav-link active" href="#">Profile</a></li>
            <li class="nav-item mx-2"><a class="nav-link active" href="change_password.php">Password</a></li>
            <li class="nav-item mx-2"><a class="nav-link active" href="add_project.php">New</a></li>
            <li class="nav-item mx-2"><a class="nav-link active" href="project_list.php">Projects</a></li>
        </ul>
        <a href="login.php"><button id="logoutbtn">Log out</button></a>
    </nav><br><br><br>

    <div class="parallax">
        <!------------------------- Edit Profile -------------------------->
        <form id="myForm" class="container" action="" method="post" enctype="multipart/form-data" onsubmit="return validateStartuperForm()">
            <h4 class="font-weight-bold">Edit Profile</h4>
            <div>
                <img src="data:image/jpg;base64,<?php echo $profileImageBase64; ?>" class="size">
                <div class="upload-photo"><br>
                    <label class="btn btn-outline-primary">Upload new photo<input type="file" id="photo" name="photo" class="fileinput"></label>
                    &nbsp;
                    <button type="reset" class="btn btn-default">Reset</button>
                </div>
            </div>

            <div class="input-field">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" readonly style="background: #e3e3e4; color: grey">
            </div>
            <div class="input-field">
                <label class="form-label">Firstname</label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $firstname; ?>" required>
            </div>
            <div class="input-field">
                <label class="form-label">Lastname</label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $lastname; ?>" required>
            </div>
            <div class="input-field">
                <label class="form-label">ID card number</label>
                <input type="text" class="form-control" id="cin" name="cin" value="<?php echo $cin; ?>" required>
            </div>
            <div class="input-field">
                <label class="form-label">E-mail</label>
                <input type="text" class="form-control" id="email" name="email" value="<?php echo $email; ?>" required>
            </div>
            <div class="input-field">
                <label class="form-label">Company Name</label>
                <input type="text" class="form-control" id="companyName" name="companyName" value="<?php echo $companyName; ?>" required>
            </div>
            <div class="input-field">
                <label class="form-label">Commercial Registry Number</label>
                <input type="text" class="form-control" id="companyCode" name="companyCode" value="<?php echo $companyCode; ?>" required>
            </div>
            <div class="input-field">
                <label class="form-label">Company Address</label>
                <input type="text" class="form-control" id="companyAddress" name="companyAddress" value="<?php echo $companyAddress; ?>" required>
            </div><br>

            <div class="px-3">
                <button type="submit" class="btn btn-primary" name="update-startuper">Save changes</button>
                &nbsp;
                <button type="reset" class="btn btn-default" onclick="resetForm()">Cancel</button>
            </div><br><br><br><br><br>
        </form>
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
                    <p class="mb-0">© 2024 WAVE . All rights reserved.</p>
                </div>
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        function validateStartuperForm() {
            var cin = document.getElementById('cin').value;
            var companyCode = document.getElementById('companyCode').value;

            var cinPattern = /^\d{8}$/;
            var companyCodePattern = /^[A-Z]\d{10}$/;

            var errors = [];

            if (!cin.match(cinPattern)) {
                errors.push("CIN must be 8 digits.");
            }

            if (!companyCode.match(companyCodePattern)) {
                errors.push("Commenrcial Registery Number must start with a capital letter followed by 10 digits.");
            }

            if (errors.length > 0) {
                alert(errors.join("\n"));
                return false;
            }
            return true;
        }
    </script>
    <script src="scripts.js"></script>

</body>

</html>