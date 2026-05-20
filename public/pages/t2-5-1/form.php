<?php
$genders = ['мужской', 'женский']; ?>
<form name="form_add" method="post" action="<?= htmlspecialchars($formAction) ?>">
  <div class="column">
    <div class="add">
      <label>Фамилия</label>
      <input type="text" name="surname" placeholder="Фамилия" value="<?= htmlspecialchars($row['surname'] ?? '') ?>" required>
    </div>
    <div class="add">
      <label>Имя</label>
      <input type="text" name="name" placeholder="Имя" value="<?= htmlspecialchars($row['name'] ?? '') ?>">
    </div>
    <div class="add">
      <label>Отчество</label>
      <input type="text" name="lastname" placeholder="Отчество" value="<?= htmlspecialchars($row['lastname'] ?? '') ?>">
    </div>
    <div class="add">
      <label>Пол</label>
      <select name="gender">
        <?php foreach ($genders as $g): ?>
          <option value="<?= $g ?>"<?= ($row['gender'] ?? '') === $g ? ' selected' : '' ?>><?= $g ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="add">
      <label>Дата рождения</label>
      <input type="date" name="date" value="<?= htmlspecialchars($row['date'] ?? '') ?>">
    </div>
    <div class="add">
      <label>Телефон</label>
      <input type="text" name="phone" placeholder="Телефон" value="<?= htmlspecialchars($row['phone'] ?? '') ?>">
    </div>
    <div class="add">
      <label>Адрес</label>
      <input type="text" name="location" placeholder="Адрес" value="<?= htmlspecialchars($row['location'] ?? '') ?>">
    </div>
    <div class="add">
      <label>Email</label>
      <input type="email" name="email" placeholder="Email" value="<?= htmlspecialchars($row['email'] ?? '') ?>">
    </div>
    <div class="add">
      <label>Комментарий</label>
      <textarea name="comment" placeholder="Краткий комментарий"><?= htmlspecialchars($row['comment'] ?? '') ?></textarea>
    </div>
    <button type="submit" name="button" value="<?= htmlspecialchars($button) ?>" class="form-btn">
      <?= htmlspecialchars($button) ?>
    </button>
  </div>
</form>
