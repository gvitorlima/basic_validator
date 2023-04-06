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
