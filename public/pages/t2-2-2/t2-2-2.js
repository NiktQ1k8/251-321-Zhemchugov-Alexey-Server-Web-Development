(function () {
  const display = document.getElementById('display');
  const form = document.getElementById('calcForm');
  const exprInput = document.getElementById('expression');

  let expr = '';
  let afterResult = false;

  const resultParam = window.__calc.resultParam;
  const exprParam = window.__calc.exprParam;

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
    if (afterResult) {
      clear();
      return;
    }
    const multi = ['sqrt(', 'log(', 'ln(', 'pi'];
    for (const tok of multi) {
      if (expr.endsWith(tok)) {
        expr = expr.slice(0, -tok.length);
        updateDisplay();
        return;
      }
    }
    expr = expr.slice(0, -1);
    updateDisplay();
  }

  function submit() {
    exprInput.value = expr;
    form.submit();
  }

  document.querySelectorAll('.btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      const ins = btn.dataset.insert;
      const op = btn.dataset.op;
      if (ins !== undefined) append(ins);
      else if (op === 'clear') clear();
      else if (op === 'back') backspace();
    });
  });

  form.addEventListener('submit', () => {
    exprInput.value = expr;
  });

  const keyMap = {
    0: '0',
    1: '1',
    2: '2',
    3: '3',
    4: '4',
    5: '5',
    6: '6',
    7: '7',
    8: '8',
    9: '9',
    '.': '.',
    '+': '+',
    '-': '-',
    '*': '*',
    '^': '^',
    '(': '(',
    ')': ')',
    '!': '!',
    s: 'sqrt(',
    l: 'ln(',
    g: 'log(',
    p: 'pi',
    e: 'e',
  };

  document.addEventListener('keydown', (e) => {
    if (e.ctrlKey || e.altKey || e.metaKey) return;
    if (e.key === '/') e.preventDefault();
    if (e.key in keyMap) {
      append(keyMap[e.key]);
    } else if (e.key === 'Enter') {
      submit();
    } else if (e.key === 'Escape' || e.key === 'Delete') {
      clear();
    } else if (e.key === 'Backspace') {
      backspace();
    }
  });
})();
