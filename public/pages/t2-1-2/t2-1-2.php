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

$operatorName = ['+' => 'сложение', '-' => 'вычитание', '*' => 'умножение', '/' => 'деление'][$operator];
$xPosition = $xIsLeft ? 'слева' : 'справа';

$a = (int) $result;
$b = (int) $known;
$p = $a;
$q = $b;
while ($q) {
  [$p, $q] = [$q, $p % $q];
}
$g = abs($p);
$fracNum = $a / $g;
$fracDen = $b / $g;
$xFrac = $fracDen === 1 ? (string) $fracNum : "$fracNum/$fracDen";
$xDec = rtrim(rtrim(number_format($x, 8, '.', ''), '0'), '.');
$xDisplay = $fracDen === 1 ? $xFrac : "$xFrac ≈ $xDec";
?>

<main class="main">
  <h1>Решение уравнения</h1>

  <div class="equation">
    <span class="equation__label">Уравнение:</span>
    <code class="equation__expr"><?= htmlspecialchars($equation) ?></code>
  </div>

  <table class="info">
    <tr>
      <th>Оператор</th>
      <td><code><?= htmlspecialchars($operator) ?></code> (<?= htmlspecialchars($operatorName) ?>)</td>
    </tr>
    <tr>
      <th>Позиция X</th>
      <td><?= $xPosition ?></td>
    </tr>
    <tr>
      <th>Ответ</th>
      <td><strong>X = <?= htmlspecialchars($xDisplay) ?></strong></td>
    </tr>
  </table>

  <h2 style="text-align: center;">Блок-схема алгоритма</h2>
  <div class="flowchart">
    <svg class="flowchart__svg"  style="height: 608px; width: 480px;" viewBox="0 0 460 608" xmlns="http://www.w3.org/2000/svg">
      <defs>
        <marker id="arr" markerWidth="8" markerHeight="8" refX="7" refY="3" orient="auto">
          <path d="M0,0 L0,6 L8,3 z" fill="#555"/>
        </marker>
      </defs>

      <!-- 1. Начало -->
      <ellipse cx="232" cy="35" rx="70" ry="22" fill="#d4edda" stroke="#555" stroke-width="1.5"/>
      <text x="232" y="40" text-anchor="middle" font-size="13" font-family="sans-serif">Начало</text>

      <line x1="232" y1="57" x2="232" y2="77" stroke="#555" stroke-width="1.5" marker-end="url(#arr)"/>

      <!-- 2. Ввод уравнения (параллелограмм) -->
      <polygon points="120,77 345,77 358,117 107,117" fill="#cce5ff" stroke="#555" stroke-width="1.5"/>
      <text x="232" y="101" text-anchor="middle" font-size="12" font-family="sans-serif">Ввод: «X * 9 = 56»</text>

      <line x1="232" y1="117" x2="232" y2="137" stroke="#555" stroke-width="1.5" marker-end="url(#arr)"/>

      <!-- 3. Разобрать уравнение -->
      <rect x="122" y="137" width="220" height="40" fill="#fff9c4" stroke="#555" stroke-width="1.5" rx="2"/>
      <text x="232" y="161" text-anchor="middle" font-size="12" font-family="sans-serif">Разобрать уравнение</text>

      <line x1="232" y1="177" x2="232" y2="197" stroke="#555" stroke-width="1.5" marker-end="url(#arr)"/>

      <!-- 4. Определить оператор -->
      <rect x="112" y="197" width="240" height="40" fill="#fff9c4" stroke="#555" stroke-width="1.5" rx="2"/>
      <text x="232" y="221" text-anchor="middle" font-size="12" font-family="sans-serif">Определить оператор</text>

      <line x1="232" y1="237" x2="232" y2="257" stroke="#555" stroke-width="1.5" marker-end="url(#arr)"/>

      <!-- 5. Определить позицию X -->
      <rect x="112" y="257" width="240" height="40" fill="#fff9c4" stroke="#555" stroke-width="1.5" rx="2"/>
      <text x="232" y="281" text-anchor="middle" font-size="12" font-family="sans-serif">Определить позицию X</text>

      <line x1="232" y1="297" x2="232" y2="317" stroke="#555" stroke-width="1.5" marker-end="url(#arr)"/>

      <!-- 6. X слева? (ромб) -->
      <polygon points="232,317 322,352 232,387 142,352" fill="#f8d7da" stroke="#555" stroke-width="1.5"/>
      <text x="232" y="356" text-anchor="middle" font-size="12" font-family="sans-serif">X слева?</text>

      <!-- Ветка "Да" вниз -->
      <line x1="232" y1="387" x2="232" y2="412" stroke="#555" stroke-width="1.5" marker-end="url(#arr)"/>
      <text x="240" y="403" font-size="11" font-family="sans-serif" fill="#555">Да</text>

      <!-- 7a. Вычислить X (левая позиция) -->
      <rect x="137" y="412" width="190" height="45" fill="#fff9c4" stroke="#555" stroke-width="1.5" rx="2"/>
      <text x="232" y="431" text-anchor="middle" font-size="11" font-family="sans-serif">X = результат</text>
      <text x="232" y="447" text-anchor="middle" font-size="11" font-family="sans-serif">÷ правый операнд</text>

      <!-- Ветка "Нет" влево -->
      <line x1="142" y1="352" x2="60" y2="352" stroke="#555" stroke-width="1.5"/>
      <line x1="60" y1="352" x2="60" y2="412" stroke="#555" stroke-width="1.5" marker-end="url(#arr)"/>
      <text x="95" y="346" font-size="11" font-family="sans-serif" fill="#555">Нет</text>

      <!-- 7b. Вычислить X (правая позиция) -->
      <rect x="0" y="412" width="120" height="45" fill="#fff9c4" stroke="#555" stroke-width="1.5" rx="2"/>
      <text x="60" y="431" text-anchor="middle" font-size="11" font-family="sans-serif">X = результат</text>
      <text x="60" y="447" text-anchor="middle" font-size="11" font-family="sans-serif">÷ левый операнд</text>

      <!-- Слияние веток -->
      <line x1="232" y1="457" x2="232" y2="477" stroke="#555" stroke-width="1.5"/>
      <line x1="75" y1="457" x2="75" y2="477" stroke="#555" stroke-width="1.5"/>
      <line x1="75" y1="477" x2="232" y2="477" stroke="#555" stroke-width="1.5"/>
      <line x1="232" y1="477" x2="232" y2="497" stroke="#555" stroke-width="1.5" marker-end="url(#arr)"/>

      <!-- 8. Вывод X (параллелограмм) -->
      <polygon points="107,497 355,497 368,537 94,537" fill="#cce5ff" stroke="#555" stroke-width="1.5"/>
      <text x="237" y="521" text-anchor="middle" font-size="12" font-family="sans-serif">Вывод: X = значение</text>

      <line x1="237" y1="537" x2="237" y2="557" stroke="#555" stroke-width="1.5" marker-end="url(#arr)"/>

      <!-- 9. Конец -->
      <ellipse cx="237" cy="577" rx="70" ry="22" fill="#d4edda" stroke="#555" stroke-width="1.5"/>
      <text x="237" y="582" text-anchor="middle" font-size="13" font-family="sans-serif">Конец</text>
    </svg>
  </div>
</main>
