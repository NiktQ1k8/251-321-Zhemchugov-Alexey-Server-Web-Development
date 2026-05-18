<?php

function calcTrig(string $funcName, float $arg): float
{
  $map = [
    'sin' => 'calcSin',
    'cos' => 'calcCos',
    'tg' => 'calcTan',
    'tan' => 'calcTan',
    'ctg' => 'calcCot',
    'cot' => 'calcCot',
  ];

  $key = strtolower($funcName);
  if (!isset($map[$key])) {
    throw new Exception("Неизвестная тригонометрическая функция: '$funcName'");
  }

  $fn = $map[$key];
  return $fn($arg);
}
