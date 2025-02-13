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

$tops = array_slice($donations, 0, 3);
$overall = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tab Tracker</title>
    <style>
        .podium {
            display: flex;
            justify-content: center;
            align-items: flex-end;
            height: 200px;
            margin-top: 20px;
        }
        .podium div {
            width: 100px;
            text-align: center;
            padding: 10px;
            font-weight: bold;
            color: white;
        }
        .first { background: gold; height: 150px; }
        .second { background: silver; height: 120px; }
        .third { background: #cd7f32; height: 100px; }
    </style>
</head>
<body>
    <h1>Soda Tab Donations</h1>
    <h2>Hall of Fame</h2>
    <div class="podium">
        <?php if (isset($tops[1])): ?>
            <div class="second">
                🥈 <?= htmlspecialchars($tops[1]['name']) ?><br>
                <?= $tops[1]['tabs'] ?> tabs
            </div>
        <?php endif; ?>

        <?php if (isset($tops[0])): ?>
            <div class="first">
                🥇 <?= htmlspecialchars($tops[0]['name']) ?><br>
                <?= $tops[0]['tabs'] ?> tabs
            </div>
        <?php endif; ?>

        <?php if (isset($tops[2])): ?>
            <div class="third">
                🥉 <?= htmlspecialchars($tops[2]['name']) ?><br>
                <?= $tops[2]['tabs'] ?> tabs
            </div>
        <?php endif; ?>
    </div>

    <h2>Donations</h2>
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
