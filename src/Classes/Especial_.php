<?php

declare(strict_types=1);

namespace Validators\Classes;

use Validators\Classes\String_;

class Especial_
{
  private String_ $objValString;

  public function __construct()
  {
    $this->objValString = String_::create();
  }

  public function require(mixed $field): array|bool
  {
    $verify = match ($field) {
      (strlen($field) == 0) => 'null',
      is_null($field) => 'null',
      empty($field)   => 'empty',

      default => false
    };

    if (is_string($verify)) {
      $error = ['error' => "Parâmetro inválido - $field", 'field' => ['expected' => $field, 'passed' => $verify]];
      throw new \Exception(json_encode($error), 500);
    }

    return true;
  }

  public function email(mixed $field, int $min = null, int $max = null)
  {
    $verify = $this->objValString->string($field);
    if (!is_bool($verify)) {
      $error = ['error' => 'Email não é do tipo string', 'field' => ['expected' => 'string', 'passed' => '']];
      throw new \Exception(json_encode($error), 500);
    }

    if (preg_match_all('/^(.*?)@/', $field, $matches)) {
      $email = end($matches)[0];

      if (isset($min) || isset($max)) {
        // $verify = $this->objValString->min($email, $min ?? );
      }
      // if (str_starts_with())
    }
    exit;
  }

  public function uuid(mixed $uuid)
  {
    $verify = $this->objValString->string($uuid);
    if (isset($verify))
      return $verify;

    $xUuid = explode('.', $uuid);
    echo '<pre>';
    print_r($uuid);
    echo '</pre>';
    exit;
  }
}
