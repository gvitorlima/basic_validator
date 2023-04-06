<?php

namespace BasicValidator\ValidatorClasses;

class Especial_
{
  public function require(mixed $userField = null): array|bool
  {
    if (is_null($userField) || empty($userField) || strlen($userField) == 0) {
      return ['userField' => $userField, 'fieldVerify' => 'require'];
    }

    return true;
  }
}
