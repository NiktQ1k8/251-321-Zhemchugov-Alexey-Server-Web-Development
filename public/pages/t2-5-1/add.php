<?php
defined('NB_INCLUDED') or die('Direct access not allowed');

if (isset($_POST['button'])) {
  $mysqli = nb_connect();
  if (!$mysqli || mysqli_connect_errno()) {
    header('Location: /t2-5-1?p=add&status=dberr');
    exit();
  }
  $textFields = ['surname', 'name', 'lastname', 'gender', 'phone', 'location', 'email', 'comment'];
  $cols = array_merge($textFields, ['date']);
  $vals = array_map(fn($f) => "'" . mysqli_real_escape_string($mysqli, $_POST[$f] ?? '') . "'", $textFields);
  $vals[] = !empty($_POST['date']) ? "'" . mysqli_real_escape_string($mysqli, $_POST['date']) . "'" : 'NULL';
  mysqli_query($mysqli, 'INSERT INTO notebook (' . implode(',', $cols) . ') VALUES (' . implode(',', $vals) . ')');
  $status = mysqli_errno($mysqli) ? 'err' : 'ok';
  mysqli_close($mysqli);
  header('Location: /t2-5-1?p=add&status=' . $status);
  exit();
}

$status = $_GET['status'] ?? null;
$row = [
  'surname' => '',
  'name' => '',
  'lastname' => '',
  'gender' => 'мужской',
  'date' => '',
  'phone' => '',
  'location' => '',
  'email' => '',
  'comment' => '',
];
$button = 'Добавить запись';
$formAction = '/t2-5-1?p=add';
?>

<?php if ($status === 'ok'): ?>
  <p class="success">Запись добавлена</p>
<?php elseif ($status === 'err'): ?>
  <p class="error">Ошибка: запись не добавлена</p>
<?php elseif ($status === 'dberr'): ?>
  <p class="error">Ошибка подключения к базе данных</p>
<?php endif; ?>

<?php include __DIR__ . '/form.php'; ?>
