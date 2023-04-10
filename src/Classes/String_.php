<?php

declare(strict_types=1);

namespace Validators\Classes;

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

  public function string(mixed $userField): bool
  {
    if (empty($userField) || is_null($userField) || is_bool($userField))
      return true;

    if (!is_string($userField) || strlen($userField) == 0) {
      $error =  ['error' => 'Parâmetro inválido.', 'field' => ['expected' => 'string', 'passed' => '']];
      throw new \Exception(json_encode($error), 500);
    }

    return true;
  }

  public function min(mixed $userField, int $min): bool
  {
    if (empty($userField) || is_null($userField) || is_bool($userField))
      return true;

    if (strlen($userField) < $min) {
      $error = ['error' => "Parâmetro inválido - $userField", 'field' => ['expected' => 'min - ' . $min, 'passed' => strlen($userField)]];
      throw new \Exception(json_encode($error), 500);
    }

    return true;
  }

  public function max(mixed $userField, int $max): bool
  {
    if (empty($userField) || is_null($userField) || is_bool($userField))
      return true;

    if (strlen($userField) > $max) {
      $error = ['error' => "Parâmetro inválido - $userField", 'field' => ['expected' => 'max - ' . $max, 'passed' => strlen($userField)]];
      throw new \Exception(json_encode($error), 500);
    }

    return true;
  }
}
