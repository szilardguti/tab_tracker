<?php
session_start();

$config_file = 'config.json';

if (!file_exists($config_file)) {
    die("Configuration file missing!");
}

$config = json_decode(file_get_contents($config_file), true);
$admin_user = $config["admin_user"];
$admin_pass_hash = $config["admin_pass"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_user = $_POST["username"];
    $input_pass = $_POST["password"];

    if ($input_user === $admin_user && password_verify($input_pass, $admin_pass_hash)) {
        $_SESSION["loggedin"] = true;
        header("Location: manage.php");
        exit;
    } else {
        $error = "Invalid login!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body>
    <h1>Admin Login</h1>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <label>Username: <input type="text" name="username"></label><br>
        <label>Password: <input type="password" name="password"></label><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
