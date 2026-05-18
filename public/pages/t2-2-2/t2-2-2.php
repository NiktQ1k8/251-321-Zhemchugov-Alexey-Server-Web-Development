<?php

require_once __DIR__ . '/_trig.php';

$fileExprResult = null;
$fileExprSource = null;
$exprFile = __DIR__ . '/expression.txt';
if (file_exists($exprFile)) {
  $fileExprSource = trim(file_get_contents($exprFile));
  $fileExprResult = evaluateExpression($fileExprSource);
}

function calcAdd(float $a, float $b): float
{
  return $a + $b;
}

function calcSubtract(float $a, float $b): float
{
  return $a - $b;
}

function calcMultiply(float $a, float $b): float
{
  return $a * $b;
}

function calcDivide(float $a, float $b): float
{
  if ($b == 0) {
    throw new Exception('Деление на ноль');
  }
  return $a / $b;
}

function calcPower(float $base, float $exp): float
{
  if ($base < 0 && $exp != floor($exp)) {
    throw new Exception('Отрицательное основание с дробным показателем');
  }
  return pow($base, $exp);
}

function calcSqrt(float $n): float
{
  if ($n < 0) {
    throw new Exception('Корень из отрицательного числа');
  }
  return sqrt($n);
}

function calcSin(float $n): float
{
  return sin($n);
}

function calcCos(float $n): float
{
  return cos($n);
}

function calcTan(float $n): float
{
  return tan($n);
}

function calcCot(float $n): float
{
  $s = sin($n);
  if ($s == 0.0) {
    throw new Exception('Котангенс не определён');
  }
  return cos($n) / $s;
}

function calcLn(float $n): float
{
  if ($n <= 0) {
    throw new Exception('ln не определён для числа ≤ 0');
  }
  return log($n);
}

function calcLog(float $n): float
{
  if ($n <= 0) {
    throw new Exception('log не определён для числа ≤ 0');
  }
  return log10($n);
}

function calcFactorial(int $n): float
{
  if ($n < 0) {
    throw new Exception('Факториал отрицательного числа не определён');
  }
  if ($n > 170) {
    throw new Exception('Слишком большое число для факториала');
  }
  if ($n <= 1) {
    return 1.0;
  }
  return calcMultiply((float) $n, calcFactorial($n - 1));
}

function tokenize(string $expr): array
{
  $tokens = [];
  $i = 0;
  $len = strlen($expr);
  while ($i < $len) {
    $c = $expr[$i];
    if ($c === ' ') {
      $i++;
      continue;
    }
    if (ctype_digit($c) || $c === '.') {
      $num = '';
      while ($i < $len && (ctype_digit($expr[$i]) || $expr[$i] === '.')) {
        $num .= $expr[$i++];
      }
      if (substr_count($num, '.') > 1) {
        throw new Exception("Некорректное число: $num");
      }
      $tokens[] = ['type' => 'num', 'val' => (float) $num];
    } elseif (ctype_alpha($c)) {
      $word = '';
      while ($i < $len && ctype_alpha($expr[$i])) {
        $word .= $expr[$i++];
      }
      $tokens[] = ['type' => 'name', 'val' => strtolower($word)];
    } elseif (in_array($c, ['+', '-', '*', '/', '^', '(', ')', '!'])) {
      $tokens[] = ['type' => 'op', 'val' => $c];
      $i++;
    } else {
      throw new Exception("Недопустимый символ: '$c'");
    }
  }
  return $tokens;
}

function parseExpr(array &$tokens, int &$pos): float
{
  return parseAddSub($tokens, $pos);
}

function parseAddSub(array &$tokens, int &$pos): float
{
  $left = parseMulDiv($tokens, $pos);
  while ($pos < count($tokens) && $tokens[$pos]['type'] === 'op' && in_array($tokens[$pos]['val'], ['+', '-'])) {
    $op = $tokens[$pos++]['val'];
    $right = parseMulDiv($tokens, $pos);
    $left = $op === '+' ? calcAdd($left, $right) : calcSubtract($left, $right);
  }
  return $left;
}

function parseMulDiv(array &$tokens, int &$pos): float
{
  $left = parsePower($tokens, $pos);
  while ($pos < count($tokens) && $tokens[$pos]['type'] === 'op' && in_array($tokens[$pos]['val'], ['*', '/'])) {
    $op = $tokens[$pos++]['val'];
    $right = parsePower($tokens, $pos);
    $left = $op === '*' ? calcMultiply($left, $right) : calcDivide($left, $right);
  }
  return $left;
}

function parsePower(array &$tokens, int &$pos): float
{
  $base = parsePostfix($tokens, $pos);
  if ($pos < count($tokens) && $tokens[$pos]['type'] === 'op' && $tokens[$pos]['val'] === '^') {
    $pos++;
    $exp = parseUnary($tokens, $pos);
    return calcPower($base, $exp);
  }
  return $base;
}

function parsePostfix(array &$tokens, int &$pos): float
{
  $val = parseUnary($tokens, $pos);
  while ($pos < count($tokens) && $tokens[$pos]['type'] === 'op' && $tokens[$pos]['val'] === '!') {
    $pos++;
    if ($val < 0 || $val != floor($val)) {
      throw new Exception('Факториал определён только для неотрицательных целых чисел');
    }
    $val = calcFactorial((int) $val);
  }
  return $val;
}

function parseUnary(array &$tokens, int &$pos): float
{
  if ($pos < count($tokens) && $tokens[$pos]['type'] === 'op' && $tokens[$pos]['val'] === '-') {
    $pos++;
    return calcSubtract(0.0, parseUnary($tokens, $pos));
  }
  return parsePrimary($tokens, $pos);
}

function expectToken(array &$tokens, int &$pos, string $val, string $msg): void
{
  if ($pos >= count($tokens) || $tokens[$pos]['val'] !== $val) {
    throw new Exception($msg);
  }
  $pos++;
}

function parsePrimary(array &$tokens, int &$pos): float
{
  if ($pos >= count($tokens)) {
    throw new Exception('Неожиданный конец выражения');
  }
  $tok = $tokens[$pos];

  if ($tok['type'] === 'num') {
    $pos++;
    return $tok['val'];
  }

  if ($tok['type'] === 'name') {
    $name = $tok['val'];
    $pos++;
    if ($name === 'pi') {
      return M_PI;
    }
    if ($name === 'e') {
      return M_E;
    }
    if (in_array($name, ['sqrt', 'ln', 'log', 'sin', 'cos', 'tg', 'ctg', 'tan', 'cot'])) {
      expectToken($tokens, $pos, '(', "Ожидается '(' после $name");
      $arg = parseExpr($tokens, $pos);
      expectToken($tokens, $pos, ')', "Ожидается ')' после аргумента $name");
      return match ($name) {
        'sqrt' => calcSqrt($arg),
        'ln' => calcLn($arg),
        'log' => calcLog($arg),
        default => calcTrig($name, $arg),
      };
    }
    throw new Exception("Неизвестная функция или константа: '$name'");
  }

  if ($tok['type'] === 'op' && $tok['val'] === '(') {
    $pos++;
    $val = parseExpr($tokens, $pos);
    expectToken($tokens, $pos, ')', "Ожидается ')'");
    return $val;
  }

  throw new Exception("Неожиданный токен: '{$tok['val']}'");
}

function evaluateExpression(string $expr): string
{
  $expr = trim($expr);
  if ($expr === '') {
    return 'Ошибка: пустое выражение';
  }
  if (!preg_match('/^[0-9a-zA-Z\s\+\-\*\/\^\(\)\.!]+$/', $expr)) {
    return 'Ошибка: недопустимые символы';
  }
  try {
    $tokens = tokenize($expr);
    if (empty($tokens)) {
      return 'Ошибка: пустое выражение';
    }
    $pos = 0;
    $result = parseExpr($tokens, $pos);
    if ($pos < count($tokens)) {
      throw new Exception("Неожиданный символ: '{$tokens[$pos]['val']}'");
    }
    if (is_nan($result)) {
      return 'Ошибка: результат не определён';
    }
    if (is_infinite($result)) {
      return 'Ошибка: переполнение';
    }
    if (abs($result) > 1e15 || (abs($result) < 1e-9 && $result != 0.0)) {
      return sprintf('%.6e', $result);
    }
    return rtrim(rtrim(number_format($result, 10, '.', ''), '0'), '.');
  } catch (Exception $e) {
    return 'Ошибка: ' . $e->getMessage();
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $expr = trim($_POST['expression'] ?? '');
  $result = evaluateExpression($expr);
  header('Location: /t2-2-2?result=' . urlencode($result) . '&expr=' . urlencode($expr));
  exit();
}

$resultParam = $_GET['result'] ?? null;
$exprParam = $_GET['expr'] ?? '';
?>

<main class="main">
  <?php if ($fileExprResult !== null): ?>
  <div class="file-expr">
    <span class="file-expr__label">Выражение из файла expression.txt:</span>
    <span class="file-expr__value"><?= htmlspecialchars($fileExprSource) ?> = <?= htmlspecialchars(
   $fileExprResult,
 ) ?></span>
  </div>
  <?php endif; ?>
  <div class="calculator">
    <div class="calculator__display" id="display">0</div>

    <form id="calcForm" method="post" action="/t2-2-2">
      <input type="hidden" id="expression" name="expression">

      <div class="calculator__buttons">
        <button type="button" class="calculator__btn" data-op="clear">C</button>
        <button type="button" class="calculator__btn" data-op="back">⌫</button>
        <button type="button" class="calculator__btn" data-insert="(">(</button>
        <button type="button" class="calculator__btn" data-insert=")">)</button>

        <button type="button" class="calculator__btn" data-insert="sin(">sin</button>
        <button type="button" class="calculator__btn" data-insert="cos(">cos</button>
        <button type="button" class="calculator__btn" data-insert="tg(">tg</button>
        <button type="button" class="calculator__btn" data-insert="ctg(">ctg</button>

        <button type="button" class="calculator__btn" data-insert="sqrt(">√</button>
        <button type="button" class="calculator__btn" data-insert="ln(">ln</button>
        <button type="button" class="calculator__btn" data-insert="log(">log</button>
        <button type="button" class="calculator__btn" data-insert="!">!</button>

        <button type="button" class="calculator__btn" data-insert="pi">π</button>
        <button type="button" class="calculator__btn" data-insert="e">e</button>
        <button type="button" class="calculator__btn" data-insert="^">^</button>
        <button type="button" class="calculator__btn" data-insert="/">/</button>

        <button type="button" class="calculator__btn" data-insert="7">7</button>
        <button type="button" class="calculator__btn" data-insert="8">8</button>
        <button type="button" class="calculator__btn" data-insert="9">9</button>
        <button type="button" class="calculator__btn" data-insert="*">*</button>

        <button type="button" class="calculator__btn" data-insert="4">4</button>
        <button type="button" class="calculator__btn" data-insert="5">5</button>
        <button type="button" class="calculator__btn" data-insert="6">6</button>
        <button type="button" class="calculator__btn" data-insert="-">-</button>

        <button type="button" class="calculator__btn" data-insert="1">1</button>
        <button type="button" class="calculator__btn" data-insert="2">2</button>
        <button type="button" class="calculator__btn" data-insert="3">3</button>
        <button type="button" class="calculator__btn" data-insert="+">+</button>

        <button type="button" class="calculator__btn calculator__btn--zero" data-insert="0">0</button>
        <button type="button" class="calculator__btn" data-insert=".">.</button>
        <button type="submit" class="calculator__btn calculator__btn--eq">=</button>
      </div>
    </form>
  </div>
</main>

<script>
window.__calc = {
  resultParam: <?= json_encode($resultParam) ?>,
  exprParam: <?= json_encode($exprParam) ?>,
};
</script>
