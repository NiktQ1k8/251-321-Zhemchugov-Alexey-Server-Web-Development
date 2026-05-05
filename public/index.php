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

$segments = array_values(array_filter(explode('/', $uri)));
$page = $segments[0] ?? 't1-2-1';
$subpage = $segments[1] ?? null;

if (!array_key_exists($page, $pages)) {
  $page = 't1-2-1';
  $subpage = null;
}

if ($subpage !== null && !preg_match('/^[a-z0-9-]+$/', $subpage)) {
  $subpage = null;
}

$pageTitle = $pages[$page];

if ($subpage !== null) {
  $pageContentPath = __DIR__ . "/pages/$page/$subpage.php";
  if (!file_exists($pageContentPath)) {
    $pageContentPath = __DIR__ . "/pages/$page/$page.php";
  }
} else {
  $pageContentPath = __DIR__ . "/pages/$page/$page.php";
}

$pageCSSPath = "/pages/$page/$page.css";
?>
<!DOCTYPE html>
<html lang="ru">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <meta name="description" content="251-321 Жемчугов Алексей Иванович" />
  <title>251-321 Жемчугов Алексей Иванович</title>
  <link rel="stylesheet" href="/style.css" />
  <link rel="stylesheet" href="<?php echo htmlspecialchars($pageCSSPath); ?>" />
</head>

<body>
  <?php include '_header.php'; ?>
  <?php include $pageContentPath; ?>
  <?php include '_footer.php'; ?>
</body>

</html>