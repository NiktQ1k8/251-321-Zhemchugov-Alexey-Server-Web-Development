<?php
define('NB_INCLUDED', true);

$dir = __DIR__;
require_once $dir . '/menu.php';
require_once $dir . '/viewer.php';

$p = $_GET['p'] ?? 'viewer';
$allowed = ['viewer', 'add', 'edit', 'delete'];
if (!in_array($p, $allowed)) {
  $p = 'viewer';
}
?>

<main>
  <?= getMenu($p) ?>

  <div class="nb-content">
    <?php if ($p === 'viewer') {
      $sort = $_GET['sort'] ?? 'byid';
      if (!in_array($sort, ['byid', 'fam', 'birth'])) {
        $sort = 'byid';
      }
      $pg = max(0, intval($_GET['pg'] ?? 0));
      echo getFriendsList($sort, $pg);
    } else {
      include $dir . '/' . $p . '.php';
    } ?>
  </div>
</main>
