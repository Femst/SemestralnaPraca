<?php
session_start();

// if connected -> welcome.php
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: welcome.php");
    exit;
}

// config file
require_once "config.php";

$username = $password = "";
$username_err = $password_err = $login_err = "";

// form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // put variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);


            $param_username = $username;

            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);

                // if exist -> check passwd
                if(mysqli_stmt_num_rows($stmt) == 1){
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Passw correct
                            session_start();

                            // data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // welcome page
                            header("location: welcome.php");
                        } else{
                            // Passwd is not valid
                            $login_err = "Invalid username or password.";
                        }
                    }
                } else{
                    // Username doesn't exist
                    $login_err = "Invalid username or password.";
                }
            } else{
                echo "Something went wrong.";
            }
            mysqli_stmt_close($stmt);
        }
    }
    mysqli_close($link);
}
// https://www.youtube.com/watch?v=gCo6JqGMi30,https://www.youtube.com/watch?v=nb5BHPYbBBY&t=209s zdroj
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>

    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
<div>
    <h2>Login</h2>

    <?php
    if(!empty($login_err)){
        echo '<div>' . $login_err . '</div>';
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div>
            <label>Username</label>
            <label>
                <input type="text" name="username"<?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
            </label>
            <span ><?php echo $username_err; ?></span>
        </div>
        <div>
            <label>Password</label>
            <label>
                <input type="password" name="password"<?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
            </label>
            <span><?php echo $password_err; ?></span>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
        <p><a href="register.php">Register</a>.</p>
    </form>
</div>
</body>
</html>

