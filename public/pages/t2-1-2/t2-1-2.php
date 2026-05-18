<?php

$equation = 'X * 9 = 56';

preg_match('/^(\S+)\s*([\+\-\*\/])\s*(\S+)\s*=\s*(\S+)$/', $equation, $m);
[, $left, $operator, $right, $result] = $m;
$result = (float) $result;

$xIsLeft = $left === 'X';
$known = (float) ($xIsLeft ? $right : $left);

$x = match ($operator) {
  '+' => $result - $known,
  '-' => $xIsLeft ? $result + $known : $known - $result,
  '*' => $result / $known,
  '/' => $xIsLeft ? $result * $known : $known / $result,
};

$xDec = rtrim(rtrim(number_format($x, 8, '.', ''), '0'), '.');
?>

<main class="main">
  <div class="equation">
    <strong>Уравнение:</strong>
    <span><?= htmlspecialchars($equation) ?></span>
  </div>

  <p class="answer"><strong>X = <?= htmlspecialchars($xDec) ?></strong></p>

  <h2>Блок-схема алгоритма</h2>
  <img src="/images/t2-1-2.png" alt="">
</main>
