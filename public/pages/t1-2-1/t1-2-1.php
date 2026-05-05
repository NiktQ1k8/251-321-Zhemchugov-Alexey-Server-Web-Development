<?php

$visitorName = 'Гость';
if (isset($_SERVER['REMOTE_ADDR'])) {
  $visitorName = 'Посетитель (' . $_SERVER['REMOTE_ADDR'] . ')';
}

date_default_timezone_set('Europe/Moscow');
$currentDateTime = date('d.m.Y H:i:s');
?>

<main class="main">
  <h1>Hello, World!</h1>
  <p>Добро пожаловать, <strong><?php echo htmlspecialchars($visitorName); ?></strong></p>
  <p>Время захода: <strong><?php echo $currentDateTime; ?></strong></p>
  <p>Страница сгенерирована на сервере <strong><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'PHP'; ?></strong></p>
</main>