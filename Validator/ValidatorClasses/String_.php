<?php

namespace BasicValidator\ValidatorClasses;

class String_
{
  public function validator(mixed $userField, array|string $params = null): array|bool
  {
    if (!is_string($userField))
      return ['userField' => $userField, 'fieldVerify' => 'string'];

    if (is_string($params))
      return true;

    foreach ($params as $param => $value) {
      $resultVerify = match ($param) {
        'min' => $this->min($userField, $value),
        'max' => $this->max($userField, $value),
      };

      if (!is_bool($resultVerify))
        return $resultVerify;
    }

    return true;
  }

  private function min(string $field, int $min): array|bool
  {
    if (strlen($field) < $min)
      return ['userField' => $field, 'fieldVerify' => "min: $min, passed: " . strlen($field)];

    return true;
  }

  private function max(string $field, int $max)
  {
    if (strlen($field) > $max)
      return ['userField' => $field, 'fieldVerify' => "max: $max, passed: " . strlen($field)];

    return true;
  }
}
