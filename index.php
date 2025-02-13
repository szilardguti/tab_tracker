<?php
$filename = 'tabs.json';

if (file_exists($filename)) {
    $jsonData = file_get_contents($filename);
    $donations = json_decode($jsonData, true);
} else {
    $donations = [];
}

usort($donations, function ($a, $b) {
    return $b['tabs'] - $a['tabs'];
});

$overall = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tab Tracker</title>
</head>
<body>
    <h1>Soda Tab Donations</h1>
    <table border="1">
        <tr>
            <th>Name</th>
            <th>Tabs Donated</th>
        </tr>
        <?php foreach ($donations as $donor): ?>
            <?php 
                $tabs = $donor['tabs'];
                $overall += $tabs;
            ?>
            <tr>
                <td><?= htmlspecialchars($donor['name']) ?></td>
                <td><?= $tabs ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <h2>Overall tabs: <?=$overall?></h2>
</body>
</html>
