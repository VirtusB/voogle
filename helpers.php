<?php

function getSearchTerm() {
    return $_GET['term'] ?? 'Missing search term';
}

function getSearchType() {
    return $_GET['type'] ?? 'sites';
}

function isActiveType($type) {
    return $_GET['type'] === $type ? 'active' : '';
}