<main class="main">
  <h1>Hello, World!</h1>
  <p>Добро пожаловать, <strong><?php echo htmlspecialchars($visitorName); ?></strong></p>
  <p>Время захода: <strong><?php echo $currentDateTime; ?></strong></p>
  <p>Страница сгенерирована на сервере <strong><?php echo $_SERVER['SERVER_SOFTWARE'] ?? 'PHP'; ?></strong></p>
</main>
