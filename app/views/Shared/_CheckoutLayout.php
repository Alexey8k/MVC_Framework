<?php
/**
 * @var array $viewBag
 * @var string $renderBody
 */
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $viewBag['title'] ?></title>
    <link href="/css/jquery-ui.css" rel="stylesheet">
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/Site.css" rel="stylesheet">
</head>
<body>
<div style="width: 1024px; margin: auto;">
    <div id="header">
        <div class="title">AUTO Store</div>
    </div>
    <div id="content">
<!--        <div class="wrapper">-->
            <?= $renderBody ?>
<!--        </div>-->
    </div>
</div>
</body>
</html>