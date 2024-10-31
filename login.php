<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "startupinvest";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Registration form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["register-startuper"])) {
        // Startuper registration form
        $nom = $_POST["lastname"];
        $prenom = $_POST["firstname"];
        $cin = $_POST["cin"];
        $email = $_POST["email"];
        $nom_entreprise = $_POST["companyName"];
        $adresse_entreprise = $_POST["companyAddress"];
        $numero_registre_commerce = $_POST["companyCode"];
        $photo = file_get_contents($_FILES["photo"]["tmp_name"]);
        $pseudo = $_POST["username"];
        $password = $_POST["password"];

        // Check if the username already exists in the capital_risque table
        $checkSql = "SELECT * FROM capital_risque WHERE pseudo = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $pseudo);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        if ($checkResult->num_rows > 0) {
            echo "<script>alert('Error: Username unavailable.');</script>";
        }

        $sql = "INSERT INTO startuper (nom, prenom, CIN, email, nom_entreprise, adresse_entreprise, numero_registre_commerce, photo, pseudo, pwrd)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssss", $nom, $prenom, $cin, $email, $nom_entreprise, $adresse_entreprise, $numero_registre_commerce, $photo, $pseudo, $password);

        if (!$stmt->execute()) {
            echo "Erreur: " . $sql . "<br>" . $conn->error;
        }
    } elseif (isset($_POST["register-capital"])) {
        // Capital-risque registration form
        $nom = $_POST["cap-lastname"];
        $prenom = $_POST["cap-firstname"];
        $cin = $_POST["cap-cin"];
        $email = $_POST["cap-email"];
        $pseudo = $_POST["cap-username"];
        $password = $_POST["cap-password"];

        // Check if the username already exists in the startuper table
        $checkSql = "SELECT * FROM startuper WHERE pseudo = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("s", $pseudo);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        if ($checkResult->num_rows > 0) {
            echo "<script>alert('Error: Username unavailable');</script>";
        }

        $sql = "INSERT INTO capital_risque (nom, prenom, email, CIN, pseudo, pwrd)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $nom, $prenom, $email, $cin, $pseudo, $password);

        if (!$stmt->execute()) {
            echo "Erreur: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["login"])) {

        $username = $_POST["logusername"];
        $password = $_POST["logpassword"];

        $sql = "SELECT * FROM startuper WHERE pseudo = '$username' AND pwrd = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            session_start();
            $_SESSION["username"] = $username;
            header("Location: profile.php");
            exit();
        } else {
            // No match in startuper table, check capital_risque table
            $sql = "SELECT * FROM capital_risque WHERE pseudo = '$username' AND pwrd = '$password'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                session_start();
                $_SESSION["username"] = $username;
                header("Location: capital_explore.php");
                exit();
            } else {
                echo "<script>alert('Invalid username or password.');</script>";
            }
        }
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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="login.css">

</head>

<body>
    <!--------------------------- navbar ----------------------------->
    <nav class="navbar bg-transparent fixed-top px-5">
        <a class="navbar-brand fs-4" href="#" onclick="showHomeContent()">WAVE</a>
        <!-- Login / signup -->
        <div>
            <button id="loginbtn" onclick="showForm('login-form')">login</button>
            <button id="signupbtn" onclick="showForm('register')">signup</button>
        </div>
    </nav>

    <!-------------------------- background --------------------------->
    <div class="parallax">
        <section>
            <h2 id="text"><span>let's Dive into the Future</span><br>WAVE</h2>
            <div class="wave" id="wave1" style="--i:1;"></div>
            <div class="wave" id="wave2" style="--i:2;"></div>
            <div class="wave" id="wave3" style="--i:3;"></div>
            <div class="wave" id="wave4" style="--i:4;"></div>
        </section>
        <div class="home">
            <h1 id="home-heading">What To Expect From WAVE ?</h1>
            <p id="home-paragraph">
                Welcome to the vibrant world of WAVE, a platform designed to revolutionize the way startups connect with venture capitalists. In today's fast-paced economic landscape, where innovation reigns supreme, WAVE stands out as a beacon of opportunity, offering a seamless and secure interface for entrepreneurs and investors alike. <br><br>
                The essence of WAVE lies in its ability to create ripples of change within the traditional startup financing ecosystem. Just as waves in the ocean carry energy across vast distances, WAVE aims to channel the energy of innovation, propelling groundbreaking ideas forward and unlocking new avenues of growth for both startups and investors. <br><br>
                At its core, WAVE is driven by a relentless commitment to innovation. By harnessing the power of cutting-edge technology and forward-thinking strategies, we strive to provide a platform that not only facilitates investment transactions but also fosters meaningful connections and collaborations between visionary entrepreneurs and savvy investors. <br><br>
                With WAVE, startups have the opportunity to showcase their potential to a global audience of investors, while venture capitalists gain access to a diverse range of high-potential ventures. By democratizing access to capital and streamlining the investment process, WAVE empowers startups to thrive and investors to capitalize on the next big ideas shaping the future.
            </p>
        </div>
    </div>
    <!-------------------------- background --------------------------->

    <div id="login-form">
        <div class="login-wrapper">
            <!------------------- login form ------------------->
            <form id="login" class="login-input-group" action="" method="post">
                <span>Don't have an account? <a href="#" id="registerLink" onclick="register()">Register</a></span>

                <h1>Login</h1>
                <div class="input-box">
                    <input type="text" placeholder="Username" name="logusername" required>
                    <i class='bx bxs-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" placeholder="Password" name="logpassword" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <div class="remember-forgot">
                    <div class="check-box">
                        <input type="checkbox" id="register-check">
                        <label>Remember Me</label>
                    </div>
                    <a href="#">Forgot Password</a>
                </div>

                <button type="submit" class="submit-btn" name="login">Login</button>
            </form>
            <!------------------ login form ------------------->
        </div>
    </div>
    <!--------------- registration form --------------->
    <div id="register">
        <div class="signup-wrapper">

            <!------------------ toggle button ------------------>
            <div class="button-box">
                <div id="btn"></div>
                <button type="button" onclick="startuper()" class="toggle-btn">venturecapital</button>
                <button type="button" onclick="capital()" class="toggle-btn">startuper</button>
            </div>
            <!------------------ toggle button ------------------>
            <span>Have an account? <a href="#" id="loginLink" onclick="login()">Login</a></span>
            <!------------------ startuper form ------------------>
            <form id="startuper" class="startuper-input-group" action="" method="post" enctype="multipart/form-data" onsubmit="return validateStartuperForm()">
                <div class="two-forms">
                    <div class="input-field">
                        <input type="text" placeholder="Firstname" id="firstname" name="firstname" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-field">
                        <input type="text" placeholder="Lastname" id="lastname" name="lastname" required>
                        <i class='bx bxs-user'></i>
                    </div>
                </div>

                <div class="two-forms">
                    <div class="input-field">
                        <input type="text" placeholder="ID card number" id="cin" name="cin" required>
                        <i class='bx bxs-id-card'></i>
                    </div>
                    <div class="input-field">
                        <input type="text" placeholder="Username" id="username" name="username" required>
                        <i class='bx bxs-user'></i>
                    </div>
                </div>

                <div class="input-field">
                    <input type="email" placeholder="Username@gmail.com" id="email" name="email" required>
                    <i class='bx bxs-envelope'></i>
                </div>

                <div class="two-forms">
                    <div class="input-field">
                        <input type="text" placeholder="Company Name" id="companyName" name="companyName" required>
                        <i class='bx bxs-building'></i>
                    </div>
                    <div class="input-field">
                        <input type="text" placeholder="A0000000000" id="companyCode" name="companyCode" required>
                        <i class='bx bxs-building'></i>
                    </div>
                </div>

                <div class="input-field">
                    <input type="text" placeholder="Company Address" id="companyAddress" name="companyAddress" required>
                    <i class='bx bxs-building'></i>
                </div>

                <div class="input-field">
                    <input type="password" placeholder="Password" id="password" name="password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input-field">
                    <input type="password" placeholder="Confirm Password" id="confirmPassword" name="confirmPassword" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <div class="image-field">
                    <input type="file" placeholder="Photo" id="photo" name="photo" required>
                    <i class='bx bxs-photo'></i>
                </div>

                <div class="check-box">
                    <input type="checkbox" id="rememberMe">
                    <label for="rememberMe">Remember Me</label>
                </div>
                <div class="check-box">
                    <input type="checkbox" id="agreeCheckbox">
                    <label for="agreeCheckbox">I agree to <a href="#">terms and conditions</a></label>
                </div>

                <button type="submit" class="submit-btn" name="register-startuper">Register</button>
            </form>
            <!------------------ startuper form ------------------>

            <!------------------ capital-risque form ------------------>
            <form id="capital" class="capital-input-group" action="" method="post" onsubmit="return validateCapitalForm()">
                <div class="two-forms">
                    <div class="input-field">
                        <input type="text" placeholder="Firstname" name="cap-firstname" required>
                        <i class='bx bxs-user'></i>
                    </div>
                    <div class="input-field">
                        <input type="text" placeholder="Lastname" name="cap-lastname" required>
                        <i class='bx bxs-user'></i>
                    </div>
                </div>

                <div class="two-forms">
                    <div class="input-field">
                        <input type="text" placeholder="CIN" id="cap-cin" name="cap-cin" required>
                        <i class='bx bxs-id-card'></i>
                    </div>
                    <div class="input-field">
                        <input type="text" placeholder="Username" name="cap-username" required>
                        <i class='bx bxs-user'></i>
                    </div>
                </div>

                <div class="input-field">
                    <input type="email" placeholder="Username@gmail.com" name="cap-email" required>
                    <i class='bx bxs-envelope'></i>
                </div>

                <div class="input-field">
                    <input type="password" placeholder="Password" id="cap-password" name="cap-password" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>
                <div class="input-field">
                    <input type="password" placeholder="Confirm Password" id="cap-confirmPassword" name="cap-confirmPassword" required>
                    <i class='bx bxs-lock-alt'></i>
                </div>

                <div class="check-box">
                    <input type="checkbox" id="rememberMe">
                    <label for="rememberMe">Remember Me</label>
                </div>
                <div class="check-box">
                    <input type="checkbox" id="cap-agreecheck">
                    <label for="cap-agreecheck">I agree to <a href="#">terms and conditions</a></label>
                </div>

                <button type="submit" class="submit-btn" name="register-capital">Register</button>
            </form>
            <!------------------ capital-risque form ------------------>
        </div>
    </div>
    <!------------- registration form ------------>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
        function showHomeContent() {
            hideForm('login-form');
            hideForm('register');
        }
    </script>

    <script>
        function showForm(formId) {
            var form = document.getElementById(formId);
            var homeHeading = document.getElementById('home-heading');
            var homeParagraph = document.getElementById('home-paragraph');
            var logo = document.getElementById('text');

            homeHeading.style.display = 'none';
            homeParagraph.style.display = 'none';
            logo.style.display = 'none';

            form.style.display = 'block';
        }

        function hideForm(formId) {
            var form = document.getElementById(formId);
            var homeHeading = document.getElementById('home-heading');
            var homeParagraph = document.getElementById('home-paragraph');
            var logo = document.getElementById('text');

            homeHeading.style.display = 'block';
            homeParagraph.style.display = 'block';
            logo.style.display = 'block';

            form.style.display = 'none';
        }
    </script>


    <!-------- Registration toggle button javascript --------->
    <script>
        function capital() {
            var x = document.getElementById('capital');
            var y = document.getElementById('startuper');
            var z = document.getElementById('btn');

            x.style.left = '-500px';
            y.style.left = '50px';
            z.style.left = '160px';

            var screenWidth = window.innerWidth;

            if (screenWidth > 650) {
                document.querySelector('.signup-wrapper').style.height = '730px';
            } else {
                document.querySelector('.signup-wrapper').style.height = '930px';
            }
        }

        function startuper() {
            var x = document.getElementById('capital');
            var y = document.getElementById('startuper');
            var z = document.getElementById('btn');

            x.style.left = '50px';
            y.style.left = '600px';
            z.style.left = '0px';

            var screenWidth = window.innerWidth;

            if (screenWidth > 650) {
                document.querySelector('.signup-wrapper').style.height = '570px';
            } else {
                document.querySelector('.signup-wrapper').style.height = '570px';
            }
        }

        function login() {
            document.getElementById('login-form').style.display = 'block';
            document.getElementById('register').style.display = 'none';
        }

        function register() {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('register').style.display = 'block';
        }
    </script>

    <!----------- close forms when clicking outside ------------->
    <script>
        document.addEventListener('click', function(event) {
            var loginForm = document.getElementById('login-form');
            var registerForm = document.getElementById('register');
            var loginLink = document.getElementById('loginLink');
            var registerLink = document.getElementById('registerLink');

            // click target is not within login form or its button
            if (!loginForm.contains(event.target) && event.target !== document.getElementById('loginbtn') && event.target !== loginLink) {
                loginForm.style.display = 'none';
            }

            // click target is not within register form or its button
            if (!registerForm.contains(event.target) && event.target !== document.getElementById('signupbtn') && event.target !== registerLink) {
                registerForm.style.display = 'none';
            }
        });
    </script>

    <!---------------------- validate Forms ---------------------->
    <script>
        function validateStartuperForm() {
            var cin = document.getElementById('cin').value;
            var companyCode = document.getElementById('companyCode').value;
            var password = document.getElementById('password').value;
            var confirmPassword = document.getElementById('confirmPassword').value;
            var photo = document.getElementById('photo').value;

            var cinPattern = /^\d{8}$/;
            var companyCodePattern = /^[A-Z]\d{10}$/;
            var passwordPattern = /^(?=.*[A-Za-z0-9])(?=.*[$#])[A-Za-z0-9$#]{8,}$/;

            var errors = [];

            if (!cin.match(cinPattern)) {
                errors.push("CIN must be 8 digits.");
            }

            if (!companyCode.match(companyCodePattern)) {
                errors.push("Company code must start with a capital letter followed by 10 digits.");
            }

            if (photo.trim() === '') {
                errors.push("Please upload a photo.");
            }

            if (!password.match(passwordPattern)) {
                errors.push("Password must be at least 8 characters long, containing letters or digits, and ending with a symbol $ or #.");
            }

            if (password !== confirmPassword) {
                errors.push("Password and Confirm Password do not match.");
            }

            var agreeCheckbox = document.getElementById('agreeCheckbox');
            var agreeChecked = agreeCheckbox.checked;

            if (!agreeChecked) {
                errors.push("Please agree to the terms and conditions.");
            }

            if (errors.length > 0) {
                alert(errors.join("\n"));
                return false;
            }
            return true;
        }

        function validateCapitalForm() {
            var cin = document.getElementById('cap-cin').value;
            var password = document.getElementById('cap-password').value;
            var confirmPassword = document.getElementById('cap-confirmPassword').value;

            var cinPattern = /^\d{8}$/;
            var passwordPattern = /^(?=.*[A-Za-z0-9])(?=.*[$#])[A-Za-z0-9$#]{8,}$/;

            var errors = [];

            if (!cin.match(cinPattern)) {
                errors.push("CIN must be 8 digits.");
            }

            if (!password.match(passwordPattern)) {
                errors.push("Password must be at least 8 characters long, containing letters or digits, and ending with a symbol $ or #.");
            }

            if (password !== confirmPassword) {
                errors.push("Password and Confirm Password do not match.");
            }

            var agreeCheckbox = document.getElementById('cap-agreecheck');
            var agreeChecked = agreeCheckbox.checked;

            if (!agreeChecked) {
                errors.push("Please agree to the terms and conditions.");
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