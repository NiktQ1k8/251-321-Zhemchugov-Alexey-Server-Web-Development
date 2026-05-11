<?php

class Cat
{
  private string $name;
  private string $color;

  public function __construct(string $name, string $color)
  {
    $this->name = $name;
    $this->color = $color;
  }

  public function getName(): string
  {
    return $this->name;
  }

  public function getColor(): string
  {
    return $this->color;
  }

  public function sayHello(): string
  {
    return 'Привет! Меня зовут ' . $this->getName() . '. Я ' . $this->getColor() . ' кошка.';
  }
}

$cat1 = new Cat('Мурка', 'рыжая');
$cat2 = new Cat('Снежка', 'белая');
$cat3 = new Cat('Чернушка', 'чёрная');

echo $cat1->sayHello() . PHP_EOL;
echo $cat2->sayHello() . PHP_EOL;
echo $cat3->sayHello() . PHP_EOL;
?>
