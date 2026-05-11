<?php

interface CalculateSquare
{
  public function calculateSquare(): float;
}

class Circle implements CalculateSquare
{
  public function __construct(private float $radius) {}

  public function calculateSquare(): float
  {
    return M_PI * $this->radius ** 2;
  }
}

class Rectangle implements CalculateSquare
{
  public function __construct(private float $width, private float $height) {}

  public function calculateSquare(): float
  {
    return $this->width * $this->height;
  }
}

class Triangle implements CalculateSquare
{
  public function __construct(private float $base, private float $height) {}

  public function calculateSquare(): float
  {
    return 0.5 * $this->base * $this->height;
  }
}

class Car
{
  public function __construct(private string $brand) {}
}

class Dog
{
  public function __construct(private string $name) {}
}

function printSquareInfo(object $object): void
{
  $className = get_class($object);

  if ($object instanceof CalculateSquare) {
    $square = $object->calculateSquare();
    echo "Объект класса {$className}: площадь = " . round($square, 2) . PHP_EOL;
  } else {
    echo "Объект класса {$className} не реализует интерфейс CalculateSquare." . PHP_EOL;
  }
}

$objects = [new Circle(5), new Rectangle(4, 6), new Triangle(3, 8), new Car('Toyota'), new Dog('Шарик')];

foreach ($objects as $object) {
  printSquareInfo($object);
}
?>
