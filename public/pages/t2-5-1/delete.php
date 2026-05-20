<?php
defined('NB_INCLUDED') or die('Direct access not allowed');

$mysqli = nb_connect();
if (!$mysqli || mysqli_connect_errno()) {
  echo '<p class="error">Ошибка подключения к БД: ' . htmlspecialchars(mysqli_connect_error()) . '</p>';
  return;
}

$deletedSurname = null;

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  if ($id > 0) {
    $res = mysqli_query($mysqli, "SELECT surname FROM notebook WHERE id={$id} LIMIT 1");
    if ($res && ($drow = mysqli_fetch_assoc($res))) {
      $deletedSurname = $drow['surname'];
      mysqli_query($mysqli, "DELETE FROM notebook WHERE id={$id}");
    }
  }
}

$listRes = mysqli_query($mysqli, 'SELECT id, surname, name, lastname FROM notebook ORDER BY surname ASC, name ASC');
?>

<?php if ($deletedSurname !== null): ?>
    <p class="success">Запись с фамилией <?= htmlspecialchars($deletedSurname) ?> удалена</p>
<?php endif; ?>

<div class="nb-delete-list">
  <?php if ($listRes && mysqli_num_rows($listRes) > 0):
    while ($lrow = mysqli_fetch_assoc($listRes)):

      $initials = '';
      if (!empty($lrow['name'])) {
        $initials .= mb_strtoupper(mb_substr($lrow['name'], 0, 1)) . '.';
      }
      if (!empty($lrow['lastname'])) {
        $initials .= mb_strtoupper(mb_substr($lrow['lastname'], 0, 1)) . '.';
      }
      $display = htmlspecialchars($lrow['surname'] . ($initials ? ' ' . $initials : ''));
      ?>
      <a href="/t2-5-1?p=delete&id=<?= $lrow['id'] ?>" class="nb-delete-link"><?= $display ?></a>
  <?php
    endwhile;
  else:
     ?>
      <p>Записей пока нет</p>
  <?php
  endif; ?>
</div>

<?php mysqli_close($mysqli); ?>
