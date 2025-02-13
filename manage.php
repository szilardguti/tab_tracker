<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: admin.php");
    exit;
}

$filename = 'tabs.json';
if (file_exists($filename)) {
    $jsonData = file_get_contents($filename);
    $donations = json_decode($jsonData, true);
} else {
    $donations = [];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["name"]) && isset($_POST["tabs"])) {
        $name = $_POST["name"];
        $tabs = intval($_POST["tabs"]);

        $donations[] = ["name" => $name, "tabs" => $tabs];
    } elseif (isset($_POST["update"])) {
        $index = intval($_POST["index"]);
        $change = intval($_POST["change"]);

        if (isset($donations[$index])) {
            $donations[$index]["tabs"] += $change;
            if ($donations[$index]["tabs"] < 0) {
                $donations[$index]["tabs"] = 0;
            }
        }
    }
    elseif (isset($_POST["delete"])) {
        $index = intval($_POST["index"]);
        if (isset($donations[$index])) {
            array_splice($donations, $index, 1);
        }
    }

    file_put_contents($filename, json_encode($donations, JSON_PRETTY_PRINT));
    header("Location: manage.php");
    exit;
}
else {
    usort($donations, function ($a, $b) {
        return $b['tabs'] - $a['tabs'];
    });
}

$overall = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>manager</title>
</head>
<body>
    <h1>Manage Donations</h1>
    <table >
        <tr>
            <th>Name</th>
            <th>Tabs Donated</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($donations as $index => $donor): ?>
            <?php 
                $tabs = $donor['tabs'];
                $overall += $tabs;
            ?>
            <tr>
                <td><?= htmlspecialchars($donor['name']) ?></td>
                <td><?= htmlspecialchars($donor['tabs']) ?></td>
                <td>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <input type="hidden" name="change" value="1">
                        <input type="hidden" name="update" value="true">
                        <button type="submit">+</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <input type="hidden" name="change" value="-1">
                        <input type="hidden" name="update" value="true">
                        <button type="submit">-</button>
                    </form>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <input type="hidden" name="delete" value="true">
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this entry?');">🗑️</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
    <h2>Overall tabs: <?=$overall?></h2>
    <br>

    <h2>Add a New Donation</h2>
    <form method="post">
        <label>Name: <input type="text" name="name" required></label><br>
        <label>Tabs Donated: <input type="number" name="tabs" required></label><br>
        <button type="submit">Add</button>
    </form>

    <br>
    <a href="logout.php">Logout</a>
</body>
</html>