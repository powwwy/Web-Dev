<?php
session_start();
include 'connect.php';

// Check if the user is logged in
if (!isset($_SESSION["user_id"])) {
    // Redirect to the login page if not logged in
    header("Location: andromaxlogin.php");
    exit();
}

// Fetch the logged-in user's ID from the session
$loggedInUserId = $_SESSION["user_id"];

// Fetch the user data from the database based on the logged-in user's ID
$sql = "SELECT name, email, streak FROM things WHERE id = '$loggedInUserId'";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    // User found in the database
    $user = $result->fetch_assoc();
} else {
    // User not found or an error occurred
    $user = null;
}

// Check if the form is submitted for editing or saving
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["edit"])) {
    $editing = true;
} else {
    $editing = false;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["save"])) {
    // Retrieve the updated data from the form
    $name = $_POST["name"];
    $email = $_POST["email"];

    // Update the user details in the database using prepared statement
    $sql = "UPDATE things SET name = ?, email = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $name, $email, $loggedInUserId);

        if (mysqli_stmt_execute($stmt)) {
            // Update the user information in the session
            $user["name"] = $name;
            $user["email"] = $email;
            $_SESSION["user"] = $user;
            $editing = false; // Hide the edit form after saving
        } else {
            echo "Error updating user details: " . $conn->error;
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    // Delete the user using prepared statement
    $sql = "DELETE FROM things WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $loggedInUserId);

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            // Log out the user and destroy the session
            session_destroy();
            header('Location: andromaxlogin.php');
            exit; // Make sure to stop script execution after the redirection
        } else {
            echo "Delete failed: " . mysqli_error($conn);
        }
    } else {
        echo "Error: " . mysqli_error($conn);
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
    <title>Andromax</title>
    <link rel="stylesheet" type="text/css" href="andromax-light.css">
  <link rel="stylesheet" type="text/css" href="andromax-dark.css">
  <link rel="stylesheet" type="text/css" href="andromax-purple.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Dosis:wght@500&display=swap" rel="stylesheet">
</head>
<body>
<header>
    <nav class = "nav1">
      <div class="container">
      <h1 class="logo" style="font-family:'Montserrat';">ANDROMAX</h1>
      <h2><i>We've Got You Covered!</i></h2>
    </div>
    <a href="andromax.html">Home</a>
        <a href="pomodoro.html">Pomodoro Timer</a>
        <a href="profilepage.php">Profile</a>
    </div>
  </nav>
  </header>
  
    <main>

        <div class="profile-container">
            <?php if ($editing) : ?>
                <!-- Display the profile form for editing -->
                <form class="profile-form" action="" method="post">
                    <h1>Edit Profile</h1>
                    <div class="profile_up">
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="text" id="email" name="email" value="<?php echo $user['email']; ?>">
                        </div>
                        
                        <div class="form-group">
                            <button class="form__button" type="submit" name="save">Save</button>
                            <button class="form__button" type="submit" name="delete">Delete</button>
                        </div>
                    </div>
                </form>
            <?php else : ?>
                <!-- Display the profile information -->
                <div class="profile">
                    <h1>Profile Information</h1>
                    <p><strong>Name:</strong> <?php echo $user['name']; ?></p>
                    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                   
                    <!-- Add the edit button to allow users to edit their profile -->
                    <form action="" method="post">
                        <div class = "edit">
                        <button type="submit" name="edit" id="edit" <?php echo $editing ? 'disabled' : ''; ?>>Edit Profile</button>
                    </div>
                </form>
                </div>
            <?php endif; ?>
        </div>
    </main>
    <footer>
  <section id="about" class="about">
    <div class="container">
      <h2>About ANDROMAX</h2>
      <p>Learn more about our mission and how we can assist you in reaching your academic goals.</p>
      <br>
      <p>© Andromax 2023</p>
    </div>
  </section>
  </footer>
  <script src="theme.js">

  </script>
</body>
</html>
