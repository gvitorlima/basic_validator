<?php

declare(strict_types=1);

namespace Validators;

use Validators\Classes\Especial_;
use Validators\Classes\String_;

class Validator
{
  private static self $instance;

  private Especial_ $objValEspecial;

  private String_ $objValString;

  private array
    $dataForVerification,
    $mandatoryParams = [
      'string'
    ];

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

      foreach (self::$instance->dataForVerification as $data => $verificationParameters) {
      }

      foreach (self::$instance->dataForVerification as $data => $fields) {

        $error = self::$instance->verify($data, $fields);
        if (!is_bool($error))
          return $error;
      }

      return true;
    } catch (\Throwable $err) {
      echo '<pre>';
      print_r($err->getMessage());
      echo '</pre>';
      exit;
      return json_decode($err->getMessage(), true);
    }
  }

  private function setVerificationData(string|array $data): void
  {
    if (is_string($data)) {
      $data = json_decode($data, true);
      is_bool($data) ? throw new \Exception("Não foi possível decodificar a string. ", 400)
        : $this->dataForVerification = $data;
    }

    $this->dataForVerification = $data;
  }

  /**
   * Classe que mapeia o array pegando os parâmetros de verificação
   */
  private function organizeParamsVerify(): void
  {
    foreach ($this->dataForVerification as $fieldVerify => $params) {
      $params = $this->sliceFields($params);
      $this->dataForVerification[$fieldVerify] = $params;
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
        $organizeFields[$field] = null;
      }
    }

    return $organizeFields;
  }

  private function verify(mixed $data, array $fields): array|bool
  {
    $mandatoryParam = array_filter($this->mandatoryParams, function ($param) use ($fields) {
      return array_key_exists($param, $fields);
    })[0];

    if (!is_string($mandatoryParam)) {
      $error = ['error' => 'Declare o tipo do parâmetro a ser verificado', 'field' => ['expected' => 'string|integer|bool|null', 'passed' =>  null]];
      throw new \Exception(json_encode($error), 500);
    }

    $this->mandatoryParamVerify($data, $mandatoryParam);

    if (key_exists('require', $fields)) {
      $this->objValEspecial->require($data);
      unset($fields['require']);
    }

    match ($mandatoryParam) {
      'string' => $this->validatorString($data, $fields['string'])
    };

    unset($fields[$mandatoryParam]);
    echo '<pre>';
    print_r($fields);
    echo '</pre>';
    exit;
    // match ($fields)

    return true;
  }

  private function mandatoryParamVerify(mixed $field, string $param)
  {
    return match ($param) {
      'string' => $this->objValString->string($field),
    };
  }

  private function validatorString(mixed $data, array $fields): array|bool
  {
    foreach ($fields as $param => $value) {
      match ($param) {
        'length' => '',
        'min' => $this->objValString->min($data, (int)$value),
        'max' => $this->objValString->max($data, (int)$value),

        default =>  throw new \Exception("Verificação inexistente, param: $param", 500)
      };
    }

    return true;
  }
}
