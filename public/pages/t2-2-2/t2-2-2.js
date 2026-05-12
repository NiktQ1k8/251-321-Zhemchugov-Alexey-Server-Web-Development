(function () {
  const display = document.getElementById('display');
  const form = document.getElementById('calcForm');
  const exprInput = document.getElementById('expression');

  let expr = '';
  let afterResult = false;

  const { resultParam, exprParam } = window.__calc;

  if (resultParam !== null) {
    expr = exprParam;
    afterResult = true;
    display.textContent = resultParam;
    display.classList.toggle('calc-display--error', resultParam.startsWith('Ошибка'));
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
    const tokens = ['ctg(', 'sqrt(', 'sin(', 'cos(', 'log(', 'tg(', 'ln(', 'pi'];
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

  document.addEventListener('keydown', (e) => {
    if (e.ctrlKey || e.altKey || e.metaKey) return;
    if (e.key === 'Enter') { e.preventDefault(); exprInput.value = expr; form.submit(); return; }
    if (e.key === 'Escape' || e.key === 'Delete') { clear(); return; }
    if (e.key === 'Backspace') { backspace(); return; }
    if (e.key === '/') e.preventDefault();
    if (e.key.length === 1) append(e.key);
  });
})();
