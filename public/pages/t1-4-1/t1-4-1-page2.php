<?php
$url = 'https://httpbin.org/get';
$headers = get_headers($url, true);
$headersText = print_r($headers, true);
?>

<main class="main">
  <h1>Заголовки ответа от <?= htmlspecialchars($url) ?></h1>
  <textarea class="textarea" readonly rows="15"><?= htmlspecialchars($headersText) ?></textarea>
  <a class="link-btn" href="/t1-4-1">← Вернуться к форме</a>
</main>
