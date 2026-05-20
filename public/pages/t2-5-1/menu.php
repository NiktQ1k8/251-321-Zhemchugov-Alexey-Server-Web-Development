<?php
defined('NB_INCLUDED') or die('Direct access not allowed');

function getMenu($p)
{
  $base = '/t2-5-1';
  $items = [
    'viewer' => 'Просмотр',
    'add' => 'Добавление записи',
    'edit' => 'Редактирование записи',
    'delete' => 'Удаление записи',
  ];

  $html = '<nav class="nb-nav">';
  foreach ($items as $key => $label) {
    $cls = $p === $key ? ' nb-active' : '';
    $html .= "<a href=\"{$base}?p={$key}\" class=\"nb-nav__item{$cls}\">{$label}</a>";
  }
  $html .= '</nav>';

  if ($p === 'viewer') {
    $sort = $_GET['sort'] ?? 'byid';
    $sortItems = [
      'byid' => 'По умолчанию',
      'fam' => 'По фамилии',
      'birth' => 'По дате рождения',
    ];
    $html .= '<div class="submenu">';
    foreach ($sortItems as $key => $label) {
      $cls = $sort === $key ? ' class="select"' : '';
      $html .= "<a href=\"{$base}?p=viewer&sort={$key}\"{$cls}>{$label}</a>";
    }
    $html .= '</div>';
  }

  return $html;
}
