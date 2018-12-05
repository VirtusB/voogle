<?php
require_once 'config.php';
require_once 'helpers.php';
require_once 'classes/SiteResultsProvider.php';


setupSearchType();
setupPageNumber();

$resultsProvider = new SiteResultsProvider($conn);
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
                        <input value="<?= getSearchTerm() ?>" autocomplete="off" type="text" class="search-box" name="term">
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
                    <a href="<?= 'search.php?type=sites&term=' . getSearchTerm() ?>">Sites</a>
                </li>
                <li class="<?= isActiveType('images') ?>">
                    <a href="<?= 'search.php?type=images&term=' . getSearchTerm() ?>">Images</a>
                </li>
            </ul>
        </div>

    </div>

    <div class="main-results-section">
        <p class="results-count"><?= $resultsProvider->getNumResults(getSearchTerm()); ?> results found</p>

        <?= $resultsProvider->getResultsHTML(getPageNumber(), PAGE_SIZE, getSearchTerm()) ?>
    </div>

    <div class="pagination-container">
        <div class="page-buttons">
            <div class="page-number-container">
                <img src="assets/images/pageStart.png">
            </div>
            <?php
            $countPages = $resultsProvider->getNumResults(getSearchTerm());

            $term = getSearchTerm();
            $type = getSearchType();

            $pagesToShow = 10;
            $numPages = ceil($countPages / PAGE_SIZE);

            $pagesLeft = min($pagesToShow, $numPages);

            $currentPage = getPageNumber() - floor($pagesToShow / 2);


            if ($currentPage < 1) {
                $currentPage = 1;
            }

            while (intval($pagesLeft) !== 0) {
                if ($currentPage === getPageNumber()) {
                    echo "<div class='page-number-container'>
                        <img src='assets/images/pageSelected.png'>
                        <span class='page-number'>$currentPage</span>
                      </div>";
                } else {
                    echo "<div class='page-number-container'>
                            <a href='search.php?term=$term&type=$type&page=$currentPage'>
                                <img src='assets/images/page.png'>
                                <span class='page-number'>$currentPage</span>
                            </a>
                          </div>";
                }

                $currentPage++;
                $pagesLeft--;
            }


            ?>
            <div class="page-number-container">
                <img src="assets/images/pageEnd.png">
            </div>
        </div>
    </div>

</div>
</body>
</html>