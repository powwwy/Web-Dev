<?php
session_start();
include 'connect.php';

// Check if the form is submitted for login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
    // ... (existing login code)
    $email= $_POST["email"];
    $password = $_POST["passWord"];

    // Validate form fields
    $errors = array();

    if (empty($email)) {
        $errors[] = "Email is required";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }
    if (empty($errors)) {
        // Check if the user exists in the database
        $sql = "SELECT * FROM things WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $storedPassword = $row["password"];
            // Redirect to the profile page with the user ID as a query parameter
            if ($password === $storedPassword) {
                // Password is correct

                // Store the user ID in the session
                $_SESSION["user_id"] = $row["id"];

                // Redirect to the profile page
                header("Location: profilepage.php");
                exit();
            } else {
                // Password is incorrect
                $errors[] = "Invalid email or password";
            }
        } else {
            // User does not exist
            $errors[] = "Invalid email or password";
        }
    }
}elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"])) {
    // Process signup form
    $name = $_POST["userName"];
    $email = $_POST["userEmail"];
    $password = $_POST["passWord"];

    // Validate form fields
    $errors = array();

    if (empty($name)) {
        $errors[] = "Name is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }
   // If there are no errors, proceed with further processing
   if (empty($errors)) {
    // Check if the email is already registered
    $check_sql = "SELECT * FROM things WHERE email = '$email'";
    $check_result = $conn->query($check_sql);

    if ($check_result && $check_result->num_rows > 0) {
        // Email is already registered
        $errors[] = "Email is already registered";
    } else {
        // Insert new user into the database
        $insert_sql = "INSERT INTO things (name, email, password)
                VALUES ('$name', '$email', '$password')";

        if ($conn->query($insert_sql) === TRUE) {
            // Registration successful

            // Retrieve the auto-incremented ID of the newly inserted row
            $newUserId = $conn->insert_id;

            // Store the user ID in the session
            $_SESSION["user_id"] = $newUserId;

            // Redirect to the profile page
            header("Location: andromaxlogin.php");
            exit();
        } else {
            // Error inserting user
            $errors[] = "Error registering user: " . $conn->error;
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
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
  <link rel="stylesheet" href="andromaxlogin.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@500&display=swap" rel="stylesheet">
</head>
<body>
  <div class="heads">
    <h1 id="formTitle" class="form__title">Login for an Andromax experience</h1>
  </div>

  <div class="container">
    <form class="form" id="login" action="" method="post">
      <h1 class="form__title">Login</h1>
      <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"]) && !empty($errors)) : ?>
        <div class="form__message form__message--error">
          <?php foreach ($errors as $error) : ?>
            <p><?php echo $error; ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div class="form__input-group">
        <input type="email" class="form__input" autofocus placeholder="Email" name="email">
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <input type="password" class="form__input" autofocus placeholder="Password" name="passWord">
        <div class="form__input-error-message"></div>
      </div>
      <button class="form__button" type="submit" name="login">Continue</button>
      <p class="form__text">
        <a class="form__link" href="#" id="linkCreateAccount">Don't have an account? Create account</a>
      </p>
    </form>

    <form class="form form--hidden" id="createAccount" action="" method="post">
      <h1 class="form__title">Create Account</h1>
      <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"]) && !empty($errors)) : ?>
        <div class="form__message form__message--error">
          <?php foreach ($errors as $error) : ?>
            <p><?php echo $error; ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <div class="form__input-group">
        <input type="text" id="userName" class="form__input" autofocus placeholder="Username" name="userName">
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <input type="email" class="form__input" autofocus id="userEmail" placeholder="Email Address" name="userEmail">
        <div class="form__input-error-message"></div>
      </div>
      <div class="form__input-group">
        <input type="password" class="form__input" autofocus placeholder="Password" name="passWord">
        <div class="form__input-error-message"></div>
      </div>
      <button class="form__button" type="submit" name="signup">Continue</button>
      <p class="form__text">
        <a class="form__link" href="./" id="linkLogin">Already have an account? Sign in</a>
      </p>
    </form>
  </div>
  <script src="andromaxlogin.js"></script>
</body>
</html>
