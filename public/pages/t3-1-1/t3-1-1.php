<?php
$sections = [
  [
    'title' => 'Инкапсуляция',
    'file' => __DIR__ . '/encapsulation.php',
    'description' => 'Класс Cat с приватными полями name и color, геттерами и методом sayHello().',
  ],
  [
    'title' => 'Наследование',
    'file' => __DIR__ . '/inheritance.php',
    'description' => 'PaidLesson наследует Lesson и добавляет поле price с геттером и сеттером.',
  ],
  [
    'title' => 'Абстрактные классы',
    'file' => __DIR__ . '/abstract_classes.php',
    'description' => 'Абстрактный HumanAbstract с наследниками RussianHuman и EnglishHuman.',
  ],
  [
    'title' => 'Интерфейсы',
    'file' => __DIR__ . '/interfaces.php',
    'description' => 'Интерфейс CalculateSquare реализован в Circle, Rectangle и Triangle.',
  ],
];

function captureOutput(string $file): string
{
  ob_start();
  include $file;
  return ob_get_clean();
}
?>

<main class="main">
  <h1 class="page-title">3.1.1. ООП</h1>

  <div class="sections">
    <?php foreach ($sections as $i => $section): ?>
      <section class="section">
        <div class="section-header">
          <span class="section-number"><?= $i + 1 ?></span>
          <div>
            <h2 class="section-title"><?= htmlspecialchars($section['title']) ?></h2>
            <p class="section-desc"><?= htmlspecialchars($section['description']) ?></p>
          </div>
        </div>
        <pre class="section-output"><?= htmlspecialchars(captureOutput($section['file'])) ?></pre>
      </section>
    <?php endforeach; ?>
  </div>
</main>
