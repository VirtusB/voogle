<?php

function getSearchTerm() {
    return $_GET['term'] ?? 'Missing search term';
}

function setupSearchType() {
    if (!isset($_GET['type'])) {
        $_GET['type'] = 'sites';
    }

    if (isset($_GET['type']) && ($_GET['type'] !== 'sites' && $_GET['type'] !== 'images')) {
        $_GET['type'] = 'sites';
    }
}

function getSearchType() {
    return $_GET['type'] ?? 'sites';
}

function setupPageNumber() {
    if (!isset($_GET['page'])) {
        $_GET['page'] = 1;
    }

    if (isset($_GET['page']) && $_GET['page'] <= 0) {
        $_GET['page'] = 1;
    }
}

function getPageNumber() {
    return intval($_GET['page']) ?? 1;
}

function isActiveType($type) {
    return $_GET['type'] === $type ? 'active' : '';
}