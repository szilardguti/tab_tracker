<?php
function logLogin($username) {
    $log_file = 'log.txt';
    
    $date = date('Y-m-d H:i:s');
    $ip = $_SERVER['REMOTE_ADDR'];
    $device = $_SERVER['HTTP_USER_AGENT'];
    
    $log = "[$date] user: $username - ip: $ip - device: $device" . PHP_EOL;
    file_put_contents($log_file, $log, FILE_APPEND | LOCK_EX);
}

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
        logLogin($input_user);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="mt-3 mb-3"><h1>Admin Login</h1></div>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <div class="mb-2"><label>Username:</label> <input type="text" name="username"></div>
        <div class="mb-2"><label>Password:</label> <input type="password" name="password"></div>
        <div><button class="btn" type="submit">Login</button></div>
    </form>
</body>
</html>
