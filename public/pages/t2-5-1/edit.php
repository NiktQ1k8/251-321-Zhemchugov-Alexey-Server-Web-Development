<?php
defined('NB_INCLUDED') or die('Direct access not allowed');

if (isset($_POST['button'])) {
  $id = intval($_GET['id'] ?? 0);
  $mysqli = nb_connect();
  if (!$mysqli || $id <= 0) {
    header('Location: /t2-5-1?p=edit&id=' . $id . '&status=err');
    exit();
  }
  $sets = [];
  foreach (['surname', 'name', 'lastname', 'gender', 'phone', 'location', 'email', 'comment'] as $f) {
    $sets[] = "{$f}='" . mysqli_real_escape_string($mysqli, $_POST[$f] ?? '') . "'";
  }
  $sets[] = 'date=' . (!empty($_POST['date']) ? "'" . mysqli_real_escape_string($mysqli, $_POST['date']) . "'" : 'NULL');
  mysqli_query($mysqli, 'UPDATE notebook SET ' . implode(',', $sets) . " WHERE id={$id}");
  $status = mysqli_errno($mysqli) ? 'err' : 'ok';
  mysqli_close($mysqli);
  header('Location: /t2-5-1?p=edit&id=' . $id . '&status=' . $status);
  exit();
}

$mysqli = nb_connect();
if (!$mysqli) {
  echo '<p class="error">Ошибка подключения к БД: ' . htmlspecialchars(mysqli_connect_error()) . '</p>';
  return;
}

$status = $_GET['status'] ?? null;
$id = intval($_GET['id'] ?? 0);

$currentRow = [];
if ($id > 0) {
  $res = mysqli_query($mysqli, "SELECT * FROM notebook WHERE id={$id} LIMIT 1");
  if ($res) $currentRow = mysqli_fetch_assoc($res) ?: [];
}
if (!$currentRow) {
  $res = mysqli_query($mysqli, 'SELECT * FROM notebook ORDER BY id ASC LIMIT 1');
  if ($res) {
    $currentRow = mysqli_fetch_assoc($res) ?: [];
    if ($currentRow) $id = $currentRow['id'];
  }
}

$listRes = mysqli_query($mysqli, 'SELECT id, surname, name FROM notebook ORDER BY surname ASC, name ASC');
?>

<?php if ($status === 'ok'): ?>
  <p class="success">Данные изменены</p>
<?php elseif ($status === 'err'): ?>
  <p class="error">Ошибка: данные не изменены</p>
<?php endif; ?>

<div class="nb-edit-layout">
  <div class="div-edit">
    <?php if ($listRes && mysqli_num_rows($listRes) > 0):
      while ($lrow = mysqli_fetch_assoc($listRes)): ?>
        <?php if ($lrow['id'] == $id): ?>
          <div class="currentRow"><?= htmlspecialchars($lrow['surname'] . ' ' . $lrow['name']) ?></div>
        <?php else: ?>
          <a href="/t2-5-1?p=edit&id=<?= $lrow['id'] ?>"><?= htmlspecialchars($lrow['surname'] . ' ' . $lrow['name']) ?></a>
        <?php endif; ?>
      <?php endwhile; ?>
    <?php else: ?>
      <p>Записей нет</p>
    <?php endif; ?>
  </div>

  <?php if ($currentRow):
    $row = $currentRow;
    $button = 'Изменить запись';
    $formAction = '/t2-5-1?p=edit&id=' . $currentRow['id'];
    include __DIR__ . '/form.php';
  else: ?>
    <p>Записей пока нет</p>
  <?php endif; ?>
</div>

<?php mysqli_close($mysqli); ?>
