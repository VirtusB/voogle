<?php
require_once 'helpers.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Voogle - Google Clone</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="wrapper">
    <div class="header">
        <div class="header-content">

            <div class="logo-container">
                <a href="index.php">
                    <img src="assets/images/logo.png" alt="Voogle logo">
                </a>
            </div>

            <div class="search-container">
                <form action="search.php" method="GET">
                    <div class="search-bar-container">
                        <input autocomplete="off" type="text" class="search-box" name="term">
                        <button class="search-button">
                            <img src="assets/images/glass.png" alt="Search">
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <div class="tabs-container">
            <ul class="tab-list">
                <li class="<?= isActiveType('sites') ?>">
                    <a href="<?= 'search.php?type=sites&term=' . getSearchTerm(); ?>">Sites</a>
                </li>
                <li class="<?= isActiveType('images') ?>">
                    <a href="<?= 'search.php?type=images&term=' . getSearchTerm(); ?>">Images</a>
                </li>
            </ul>
        </div>
    </div>
</div>
</body>
</html>