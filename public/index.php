<?php

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('/\.(css|js|svg|png|jpg|jpeg|gif|ico)$/', $uri)) {
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

$parts = array_values(array_filter(explode('/', $uri)));
$page = $parts[0] ?? 't1-2-1';
$subpage = $parts[1] ?? null;

if (!array_key_exists($page, $pages)) {
  $page = 't1-2-1';
  $subpage = null;
}

$pageTitle = $pages[$page];
$pageCSS = $subpage !== null ? "/pages/$page/$page-$subpage.css" : "/pages/$page/$page.css";
$pageJS = "/pages/$page/$page.js";
$pageContentPath = $subpage !== null ? __DIR__ . "/pages/$page/$page-$subpage.php" : __DIR__ . "/pages/$page/$page.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  include $pageContentPath;
  exit();
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <meta name="description" content="251-321 Жемчугов Алексей Иванович" />
  <title>251-321 Жемчугов Алексей Иванович</title>
  <link rel="stylesheet" href="/includes/style.css" />
  <link rel="stylesheet" href="/includes/_header.css" />
  <link rel="stylesheet" href="/includes/_footer.css" />
  <link rel="stylesheet" href="<?= htmlspecialchars($pageCSS) ?>" />
  <script src="<?= htmlspecialchars($pageJS) ?>" defer></script>
</head>

<body>

  <?php include __DIR__ . '/includes/_header.php'; ?>

  <?php include $pageContentPath; ?>

  <?php include __DIR__ . '/includes/_footer.php'; ?>

</body>

</html>
