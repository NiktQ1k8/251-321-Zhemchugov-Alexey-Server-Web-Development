(function () {
  const display = document.getElementById('display');
  const form = document.getElementById('calcForm');
  const exprInput = document.getElementById('expression');

  if (!display || !form || !exprInput) return;

  let expr = '';
  let afterResult = false;

  const { resultParam = null, exprParam = '' } = window.__calc ?? {};

  if (resultParam !== null) {
    expr = exprParam;
    afterResult = true;
    display.textContent = resultParam;
    display.classList.toggle('calculator__display--error', resultParam.startsWith('Ошибка'));
  }

  function updateDisplay() {
    display.textContent = expr || '0';
  }

  function append(str) {
    if (afterResult) {
      expr = '';
      afterResult = false;
      display.classList.remove('calculator__display--error');
    }
    expr += str;
    updateDisplay();
  }

  function clear() {
    expr = '';
    afterResult = false;
    display.classList.remove('calculator__display--error');
    updateDisplay();
  }

  function backspace() {
    if (afterResult) {
      clear();
      return;
    }
    const tokens = ['ctg(', 'sqrt(', 'sin(', 'cos(', 'log(', 'tg(', 'ln(', 'cot(', 'tan(', 'pi'];
    for (const tok of tokens) {
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
    if (!expr) {
      display.textContent = 'Ошибка: пустое выражение';
      display.classList.add('calculator__display--error');
      return;
    }
    exprInput.value = expr;
    form.submit();
  }

  document.querySelectorAll('.calculator__btn').forEach((btn) => {
    btn.addEventListener('click', () => {
      const ins = btn.dataset.insert;
      const op = btn.dataset.op;
      if (ins !== undefined) append(ins);
      else if (op === 'clear') clear();
      else if (op === 'back') backspace();
    });
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();
    submit();
  });

  document.addEventListener('keydown', (e) => {
    if (e.ctrlKey || e.altKey || e.metaKey) return;
    if (e.key === 'Enter') {
      e.preventDefault();
      submit();
      return;
    }
    if (e.key === 'Escape' || e.key === 'Delete') {
      clear();
      return;
    }
    if (e.key === 'Backspace') {
      backspace();
      return;
    }
    if (e.key === '/') e.preventDefault();
    if (e.key.length === 1) append(e.key);
  });
})();
