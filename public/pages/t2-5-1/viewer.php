<?php
defined('NB_INCLUDED') or die('Direct access not allowed');

function nb_connect()
{
  mysqli_report(MYSQLI_REPORT_OFF);
  $host = getenv('DB_HOST') ?: 'localhost';
  $mysqli = mysqli_connect($host, 'cy70660_serverwebdevelopment', 't1ip4bUQ', 'cy70660_serverwebdevelopment');
  if (!$mysqli) {
    return null;
  }
  mysqli_set_charset($mysqli, 'utf8mb4');
  mysqli_query(
    $mysqli,
    "CREATE TABLE IF NOT EXISTS notebook (
      id       INT AUTO_INCREMENT PRIMARY KEY,
      surname  VARCHAR(100) NOT NULL DEFAULT '',
      name     VARCHAR(100) NOT NULL DEFAULT '',
      lastname VARCHAR(100) DEFAULT '',
      gender   VARCHAR(10)  DEFAULT '',
      date     DATE,
      phone    VARCHAR(20)  DEFAULT '',
      location VARCHAR(200) DEFAULT '',
      email    VARCHAR(100) DEFAULT '',
      comment  TEXT
  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
  );
  return $mysqli;
}

function getFriendsList($type, $page)
{
  $mysqli = nb_connect();
  if (!$mysqli) {
    return '<p class="error">Ошибка подключения к БД: ' . htmlspecialchars(mysqli_connect_error()) . '</p>';
  }

  $res = mysqli_query($mysqli, 'SELECT COUNT(*) FROM notebook');
  if (!$res) {
    return '<p class="error">Ошибка базы данных</p>';
  }

  $row = mysqli_fetch_row($res);
  $total = intval($row[0]);

  if ($total === 0) {
    mysqli_close($mysqli);
    return '<p>Записей нет</p>';
  }

  $perPage = 10;
  $pages = (int) ceil($total / $perPage);
  if ($page >= $pages) {
    $page = $pages - 1;
  }

  $orderBy = match ($type) {
    'fam' => 'surname ASC, name ASC',
    'birth' => 'date ASC',
    default => 'id ASC',
  };

  $offset = $page * $perPage;
  $res = mysqli_query($mysqli, "SELECT * FROM notebook ORDER BY {$orderBy} LIMIT {$offset}, {$perPage}");

  $ret = '<table class="nb-table">';
  $ret .= '<tr><th>#</th><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Пол</th>' . '<th>Дата рождения</th><th>Телефон</th><th>Адрес</th><th>Email</th><th>Комментарий</th></tr>';

  $i = $offset + 1;
  while ($row = mysqli_fetch_assoc($res)) {
    $ret .= '<tr><td>' . $i++ . '</td>';
    foreach (['surname', 'name', 'lastname', 'gender', 'date', 'phone', 'location', 'email', 'comment'] as $f) {
      $ret .= '<td>' . htmlspecialchars($row[$f] ?? '') . '</td>';
    }
    $ret .= '</tr>';
  }
  $ret .= '</table>';

  if ($pages > 1) {
    $sort = htmlspecialchars($_GET['sort'] ?? 'byid');
    $ret .= '<div class="nb-pages">';
    for ($i = 0; $i < $pages; $i++) {
      if ($i === $page) {
        $ret .= '<span>' . ($i + 1) . '</span>';
      } else {
        $ret .= "<a href=\"/t2-5-1?p=viewer&sort={$sort}&pg={$i}\">" . ($i + 1) . '</a>';
      }
    }
    $ret .= '</div>';
  }

  mysqli_close($mysqli);
  return $ret;
}
