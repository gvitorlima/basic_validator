<?php

declare(strict_types=1);

namespace BasicValidator\ValidatorClasses;

class String_
{
  private static self $instance;

  private array $params;

  private function __construct()
  {
  }

  public static function create()
  {
    if (!isset(self::$instance))
      self::$instance = new self;

    return self::$instance;
  }

  public function string(mixed $userField): array|bool
  {
    if (!is_string($userField) || strlen($userField) == 0)
      return [
        'error' => 'Parâmetro inválido.',
        'field' => [
          'expected' => 'string',
          'passed' => ''
        ]
      ];

    if (is_string($userField))
      return true;

    return [];
  }

  public function min(mixed $userField, int $min): array|bool
  {
    $this->string($userField);
    if (strlen($userField) < $min)
      return [
        'error' => "Parâmetro inválido - $userField",
        'field' => [
          'expected' => $userField . ' - ' . $min,
          'passed' => strlen($userField)
        ]
      ];

    return true;
  }

  public function max(mixed $userField, int $max)
  {
    $this->string($userField);
    if (strlen($userField) > $max)
      return [
        'error' => "Parâmetro inválido - $userField",
        'field' => [
          'expected' => $userField . ' - ' . $max,
          'passed' => strlen($userField)
        ]
      ];

    return true;
  }
}
