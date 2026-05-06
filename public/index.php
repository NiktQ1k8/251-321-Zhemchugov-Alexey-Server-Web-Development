<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('/\.(css|js|svg|png|jpg|jpeg|gif|ico)$/', $uri)) {
  return false;
}

if (preg_match('#^/pages/[^/]+/.+\.php$#', $uri)) {
  return false;
}

$pages = [
  't1-2-1' => '1.2.1. Hello World',
  't1-4-1' => '1.4.1. Feedback Form',
  't2-1-2' => '2.1.2. Equation',
  't2-2-2' => '2.2.2. Calculator',
  't2-5-1' => '2.5.1. Notebook',
  't3-1-1' => '3.1.1. OOP',
  't3-2-1' => '3.2.1. Routing',
  't3-2-2' => '3.2.2. Page Presentation',
  't3-3-1' => '3.3.1. Query',
  't3-4-1' => '3.4.1. Editing',
  't3-6-cw' => '3.6. Comments - CW',
];

$page = array_values(array_filter(explode('/', $uri)))[0] ?? 't1-2-1';

if (!array_key_exists($page, $pages)) {
  $page = 't1-2-1';
}

$pageTitle = $pages[$page];
$pageCSS = "/pages/$page/$page.css";
$pageJS = "/pages/$page/$page.js";
$pageContentPath = __DIR__ . "/pages/$page/$page.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include $pageContentPath;
  exit();
}

include __DIR__ . '/includes/_layout-open.php';
include $pageContentPath;
include __DIR__ . '/includes/_layout-close.php';
?>
