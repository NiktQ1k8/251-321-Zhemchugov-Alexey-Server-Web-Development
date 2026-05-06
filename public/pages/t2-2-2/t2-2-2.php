<?php

// ── Math operation functions ──────────────────────────────────────────────────

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

// ── Tokenizer ─────────────────────────────────────────────────────────────────

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

// ── Recursive descent parser ──────────────────────────────────────────────────

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
    if (in_array($name, ['sqrt', 'ln', 'log'])) {
      expectToken($tokens, $pos, '(', "Ожидается '(' после $name");
      $arg = parseExpr($tokens, $pos);
      expectToken($tokens, $pos, ')', "Ожидается ')' после аргумента $name");
      return match ($name) {
        'sqrt' => calcSqrt($arg),
        'ln' => calcLn($arg),
        'log' => calcLog($arg),
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

// ── Expression evaluator ──────────────────────────────────────────────────────

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

// ── POST handler (PRG pattern) ────────────────────────────────────────────────

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
  <div class="calculator">
    <div class="calc-display" id="display">0</div>

    <form id="calcForm" method="post" action="/t2-2-2">
      <input type="hidden" id="expression" name="expression">

      <div class="calc-buttons">
        <button type="button" class="btn btn-fn" data-op="clear">C</button>
        <button type="button" class="btn btn-fn" data-op="back">⌫</button>
        <button type="button" class="btn" data-insert="(">(</button>
        <button type="button" class="btn" data-insert=")">)</button>

        <button type="button" class="btn btn-fn" data-insert="sqrt(">sqrt</button>
        <button type="button" class="btn btn-fn" data-insert="ln(">ln</button>
        <button type="button" class="btn btn-fn" data-insert="log(">log</button>
        <button type="button" class="btn btn-fn" data-insert="!">!</button>

        <button type="button" class="btn btn-fn" data-insert="pi">pi</button>
        <button type="button" class="btn btn-fn" data-insert="e">e</button>
        <button type="button" class="btn btn-fn" data-insert="^">^</button>
        <button type="button" class="btn btn-op" data-insert="/">/</button>

        <button type="button" class="btn btn-num" data-insert="7">7</button>
        <button type="button" class="btn btn-num" data-insert="8">8</button>
        <button type="button" class="btn btn-num" data-insert="9">9</button>
        <button type="button" class="btn btn-op" data-insert="*">x</button>

        <button type="button" class="btn btn-num" data-insert="4">4</button>
        <button type="button" class="btn btn-num" data-insert="5">5</button>
        <button type="button" class="btn btn-num" data-insert="6">6</button>
        <button type="button" class="btn btn-op" data-insert="-">-</button>

        <button type="button" class="btn btn-num" data-insert="1">1</button>
        <button type="button" class="btn btn-num" data-insert="2">2</button>
        <button type="button" class="btn btn-num" data-insert="3">3</button>
        <button type="button" class="btn btn-op" data-insert="+">+</button>

        <button type="button" class="btn btn-num btn-zero" data-insert="0">0</button>
        <button type="button" class="btn btn-num" data-insert=".">.</button>
        <button type="submit" class="btn btn-eq">=</button>
      </div>
    </form>
  </div>

  <div class="calc-hint">
    Клавиатура: цифры, <code>+-*/^()!.</code>,
    <code>s</code>=√, <code>l</code>=ln, <code>g</code>=log,
    <code>p</code>=π, <code>e</code>=e,
    Enter=вычислить, Esc=очистить
  </div>
</main>

<script>
(function () {
  const display  = document.getElementById('display');
  const form     = document.getElementById('calcForm');
  const exprInput = document.getElementById('expression');

  let expr = '';
  let afterResult = false;

  const resultParam = <?= json_encode($resultParam) ?>;
  const exprParam   = <?= json_encode($exprParam) ?>;

  if (resultParam !== null) {
    expr = exprParam;
    afterResult = true;
    const isError = resultParam.startsWith('Ошибка');
    display.textContent = resultParam;
    display.classList.toggle('calc-display--error', isError);
  }

  function updateDisplay() {
    display.textContent = expr || '0';
  }

  function append(str) {
    if (afterResult) {
      expr = '';
      afterResult = false;
      display.classList.remove('calc-display--error');
    }
    expr += str;
    updateDisplay();
  }

  function clear() {
    expr = '';
    afterResult = false;
    display.classList.remove('calc-display--error');
    updateDisplay();
  }

  function backspace() {
    if (afterResult) { clear(); return; }
    const multi = ['sqrt(', 'log(', 'ln(', 'pi'];
    for (const tok of multi) {
      if (expr.endsWith(tok)) { expr = expr.slice(0, -tok.length); updateDisplay(); return; }
    }
    expr = expr.slice(0, -1);
    updateDisplay();
  }

  function submit() {
    exprInput.value = expr;
    form.submit();
  }

  document.querySelectorAll('.btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const ins = btn.dataset.insert;
      const op  = btn.dataset.op;
      if (ins !== undefined) append(ins);
      else if (op === 'clear') clear();
      else if (op === 'back')  backspace();
    });
  });

  form.addEventListener('submit', () => { exprInput.value = expr; });

  const keyMap = {
    '0':'0',
    '1':'1',
    '2':'2',
    '3':'3',
    '4':'4',
    '5':'5',
    '6':'6',
    '7':'7',
    '8':'8',
    '9':'9',
    '.':'.',
    '+':'+',
    '-':'-',
    '*':'*',
    '^':'^',
    '(':  '(',
    ')':')',
    '!':'!',
    's':'sqrt(',
    'l':'ln(',
    'g':'log(',
    'p':'pi',
    'e':'e',
  };

  document.addEventListener('keydown', e => {
    if (e.ctrlKey || e.altKey || e.metaKey) return;
    if (e.key === '/') e.preventDefault();
    if (e.key in keyMap)         { append(keyMap[e.key]); }
    else if (e.key === 'Enter')   { submit(); }
    else if (e.key === 'Escape' || e.key === 'Delete') { clear(); }
    else if (e.key === 'Backspace') { backspace(); }
  });
})();
</script>
