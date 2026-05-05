<?php

$pageTitle = '1.4.1. Feedback Form';
$pageCSS = '/pages/t1-4-1/t1-4-1.css';
$url = 'https://httpbin.org/get';
$headers = get_headers($url, true);
$headersText = print_r($headers, true);
include '../../includes/_layout-open.php';
?>

<main class="main">
  <h1>Заголовки ответа от <?php echo htmlspecialchars($url); ?></h1>
  <textarea class="textarea" readonly rows="15"><?php echo htmlspecialchars($headersText); ?></textarea>
  <a class="link-btn" href="/t1-4-1">← Вернуться к форме</a>
</main>

<?php include '../../includes/_layout-close.php'; ?>
