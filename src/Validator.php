<?php

declare(strict_types=1);

namespace Validators;

use Validators\Classes\Especial_;
use Validators\Classes\String_;

class Validator
{
  private static self $instance;

  private string $returnType;

  private Especial_
    $objValEspecial;
  private String_
    $objValString;

  private array
    $error,
    $verificationData;

  private function __construct()
  {
    $this->objValEspecial = new Especial_;
    $this->objValString = String_::create();
  }

  private function __clone()
  {
  }

  public static function validate(string|array $dataValidate): bool|string|array
  {
    try {

      if (!isset(self::$instance))
        self::$instance = new self;

      self::$instance->setVerificationData($dataValidate);
      self::$instance->organizeParamsVerify();

      foreach (self::$instance->verificationData as $data => $fields) {
        $error = self::$instance->verify($data, $fields);
        if (!is_bool($error))
          return $error;
      }

      return true;
    } catch (\Throwable $err) {
      if (self::$returnType == 'json') {
        $return = [
          'error' => $err->getMessage(),
          'field' => self::$instance->error ?? null,
          'code' => $err->getCode()
        ];

        return json_encode($return);
      }

      return [
        'error' => $err->getMessage(),
        'field' => self::$instance->error ?? null,
        'code' => $err->getCode()
      ];
    }
  }

  private function setVerificationData(string|array $data): void
  {
    if (is_string($data)) {
      self::$returnType = 'json';
      $verify = json_decode($data, true);
      is_bool($verify) ? throw new \Exception("Não foi possível decodificar a string. ", 400)
        : $this->verificationData = $verify;
    }

    $this->verificationData = $data;
  }

  /**
   * Classe que mapeia o array pegando os parâmetros de verificação
   */
  private function organizeParamsVerify(): void
  {
    foreach ($this->verificationData as $fieldVerify => $params) {
      $params = $this->sliceFields($params);
      $this->verificationData[$fieldVerify] = $params;
    }
  }

  private function sliceFields(string $verifyFields): array
  {
    $xFields = explode(':', $verifyFields);
    $organizeFields = [];
    foreach ($xFields as $_ => $field) {
      if (preg_match_all('/\[(.*?)\]/', $field, $secondaryFields)) {
        $fields = explode(',', end($secondaryFields)[0]);
        $fieldVerify = explode('[', $field)[0];
        foreach ($fields as $_ => $value) {
          $organizeFields[$fieldVerify][explode('-', $value)[0]] = explode('-', $value)[1];
        }
      } else {
        $organizeFields[$field] = $field;
      }
    }

    return $organizeFields;
  }

  private function verify(string $verify, array $fields): array|bool
  {
    foreach ($fields as $field => $params) {
      $verifyReturn = match ($field) {
        'require' => $this->objValEspecial->require($field),
        'date_time' => '',
        'uuid' => '',
        'boolean' => '',
        'instance' => '',
        'email' => '',

        'string' => $this->validatorString($verify, $fields[$field]),

        'int_length' => '',
        'positive' => '',
        'negative' => '',

        default => ''
      };

      if (!is_bool($verifyReturn)) {
        return [
          'error' => $verifyReturn['error'],
          'field' => $verifyReturn['field']
        ];
      }
      continue;
    }

    return true;
  }

  private function validatorString(mixed $field, array $params = null): array|bool
  {
    if (isset($params)) {
      foreach ($params as $param => $value) {
        $verifyReturn = match ($param) {
          'str_length' => '',
          'min' => $this->objValString->min($field, (int)$value),
          'max' => $this->objValString->max($field, (int)$value),
        };

        if (!is_bool($verifyReturn))
          return [
            'error' => $verifyReturn['error'],
            'field' => $verifyReturn['field']
          ];
      }
    } else {
      $verifyReturn = $this->objValString->string($field);
      if (!is_bool($verifyReturn))
        return [
          'error' => $verifyReturn['error'],
          'field' => $verifyReturn['field']
        ];
    }

    return true;
  }
}
