<?php

declare(strict_types=1);

namespace BasicValidator\ValidatorClasses;

class Especial_
{
  public function require(mixed $field): array|bool
  {
    if (is_null($field) || empty($field) || strlen($field) == 0)
      return [
        'error' => "Parâmetro inválido - $field",
        'field' => [
          'expected' => $field,
          'passed' => ''
        ]
      ];

    return true;
  }
}
