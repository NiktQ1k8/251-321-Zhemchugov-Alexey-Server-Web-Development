<?php
$visitorName = 'Гость';
if (isset($_SERVER['REMOTE_ADDR'])) {
  $visitorName = 'Посетитель (' . $_SERVER['REMOTE_ADDR'] . ')';
}

date_default_timezone_set('Europe/Moscow');
$currentDateTime = date('d.m.Y H:i:s');

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('/\.(css|js|svg|png|jpg|jpeg|gif|ico)$/', $uri)) {
  return false;
}

$pages = [
  't1-2-1' => '2.1. Домашняя работа: Hello, World!',
  't1-4-1' => '4.1. Домашняя работа: Feedback Form.',
  't2-1-2' => '1.2. Домашняя работа: Solve the equation.',
  't2-2-2' => '2.2. Домашняя работа: Calculator.',
  't2-5-1' => '5.1. Домашняя работа: "Notebook".',
  't3-1-1' => '1.1. Домашняя работа.',
  't3-2-1' => '2.1. Домашняя работа с роутингом.',
  't3-2-2' => '2.2. Домашняя работа с представлением.',
  't3-3-1' => '3.1. Домашняя работа.',
  't3-4-1' => '4.1. Домашняя работа.',
  't3-6' => '6. Итоговая работа.',
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
    $pageContentPath = __DIR__ . "/pages/$page/index.php";
  }
} else {
  $pageContentPath = __DIR__ . "/pages/$page/index.php";
}

$pageCSSPath = "/pages/$page/style.css";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1">
  <meta name="description" content="251-321 Жемчугов Алексей Иванович" />
  <title>251-321 Жемчугов Алексей Иванович</title>
  <link rel="stylesheet" href="/css/common.css" />
  <link rel="stylesheet" href="<?php echo htmlspecialchars($pageCSSPath); ?>" />
</head>
<body>
  <?php include '_header.php'; ?>
  <?php include $pageContentPath; ?>
  <?php include '_footer.php'; ?>
</body>
</html>
