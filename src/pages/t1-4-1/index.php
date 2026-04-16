<main class="main">
  <h1>Обратная связь</h1>
  <form action="https://httpbin.org/post" method="post">
    <label>Имя пользователя:</label>
    <input type="text" name="username" required>

    <label>E-mail пользователя:</label>
    <input type="email" name="email" required>

    <label>Тип обращения:</label>
    <select name="type" required>
      <option value="complaint">Жалоба</option>
      <option value="suggestion">Предложение</option>
      <option value="gratitude">Благодарность</option>
    </select>

    <label>Текст обращения:</label>
    <textarea name="message" rows="5" required></textarea>

    <label>Вариант ответа:</label>
    <div class="checkbox-group">
      <label><input type="checkbox" name="response[]" value="sms">СМС</label>
      <label><input type="checkbox" name="response[]" value="email">E-mail</label>
    </div>

    <button type="submit">Отправить</button>
  </form>

  <p>
    <a class="link-btn" href="/t1-4-1/page2">Перейти на 2 страницу ==></a>
  </p>
</main>
