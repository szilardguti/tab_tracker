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
$goal = 1000;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tab Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container d-flex flex-column justify-content-center align-center w-80">
        <div class="mb-3 mt-4 text-center"><h1 class="title">Tab Tracker</h1></div>
        <div class="mb-3"><h2>Hall of Fame</h2></div>
        <div class="podium">
            <?php if (isset($tops[1])): ?>
                <div class="second">
                    <div class="medal p-0">🥈</div>
                    <?= htmlspecialchars($tops[1]['name']) ?><br>
                    <?= $tops[1]['tabs'] ?> tabs
                </div>
            <?php endif; ?>

            <?php if (isset($tops[0])): ?>
                <div class="first">
                    <div class="medal p-0">🥇</div>
                    <?= htmlspecialchars($tops[0]['name']) ?><br>
                    <?= $tops[0]['tabs'] ?> tabs
                </div>
            <?php endif; ?>

            <?php if (isset($tops[2])): ?>
                <div class="third">
                    <div class="medal p-0">🥉</div>
                    <?= htmlspecialchars($tops[2]['name']) ?><br>
                    <?= $tops[2]['tabs'] ?> tabs
                </div>
            <?php endif; ?>
        </div>

        <div class="mt-3 mb-3"><h2>Donations</h2></div>
        <table class="table table-dark table-hover">
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
        <div class="mt-3"><h2>Progress</h2></div>
        <div class="w-80 progress-container">
            <div class="progress-bar" style="height: 8%;" id="progress-bar"></div>
            <img src="knight_sil.png" class="progress-outline">
        </div>
        <div class="mt-3"><h2>Overall tabs: <?=$overall?></h2></div>
    </div>
</body>
</html>
