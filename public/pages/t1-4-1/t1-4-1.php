<main class="main">
  <h1>Обратная связь</h1>
  <form action="https://httpbin.org/post" method="post">
    <label class="form__label">
      <p>Имя пользователя:</p>
      <input class="form__input" type="text" name="username" required>
    </label>

    <label class="form__label">
      <p>E-mail пользователя:</p>
      <input class="form__input" type="email" name="email" required>
    </label>

    <p>Тип обращения:</p>
    <select class="form__input" name="type" required>
      <option value="complaint">Жалоба</option>
      <option value="suggestion">Предложение</option>
      <option value="gratitude">Благодарность</option>
    </select>

    <p>Текст обращения:</p>
    <textarea class="form__textarea" name="message" rows="5" required></textarea>

    <p>Вариант ответа:</p>
    <label class="form__label">
      <input type="checkbox" name="response[]" value="sms">
      <span>СМС</span>
    </label>
    <label class="form__label">
      <input type="checkbox" name="response[]" value="email">
      <span>E-mail</span>
    </label>

    <button class="form__button" type="submit">Отправить</button>
  </form>

  <p>
    <a class="link-btn" href="/pages/t1-4-1/t1-4-1-page2.php">Перейти на 2 страницу ==></a>
  </p>
</main>