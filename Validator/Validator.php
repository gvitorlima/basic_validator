<?php

namespace BasicValidator;

use BasicValidator\ValidatorClasses\Especial_;
use BasicValidator\ValidatorClasses\String_;

class Validator
{
  private static self $instance;

  private string $returnType;

  private Especial_ $validatorEspecial;
  private String_ $validatorString;

  private function __construct()
  {
    $this->validatorEspecial = new Especial_;
    $this->validatorString = new String_;
  }

  private function __clone()
  {
  }

  public static function validate(string|array $dataValidate): bool
  {
    if (!isset(self::$instance))
      self::$instance = new self;

    $dataValidate = self::$instance->getArrayData($dataValidate);
    $dataValidate = self::$instance->sliceParams($dataValidate);

    self::$instance->matchType($dataValidate);

    return true;
  }

  private function matchType(array $data): bool
  {
    $verify = [];

    foreach ($data as $userField => $verifyFields) {

      foreach ($verifyFields as $field => $fieldParams) {
        $field = strtolower($field);
        $resultVerify = match ($field) {

          'require' => $this->validatorEspecial->require($userField),
          'string' => $this->validatorString->validator($userField, $fieldParams),
        };

        if (is_array($resultVerify))
          throw new \Exception('Dado inválido: Field: ' . $resultVerify['userField'] . ' - ' . $resultVerify['fieldVerify'], 400);
      }
    }

    return true;
  }

  private function getArrayData(string|array $data): array
  {
    if (is_string($data)) {
      $this->returnType = 'json';

      return is_null(json_decode($data)) ? throw new \Exception("String não pode ser decodada, expected type: $this->returnType.", 400)
        : json_decode($data);
    }

    $this->returnType = 'default';

    return $data;
  }

  /**
   * Classe que mapeia o array pegando os parâmetros de verificação
   */
  private function sliceParams(array $dataAndFields): array
  {
    $sliceParams = [];

    foreach ($dataAndFields as $fieldVerify => $fields) {
      $params = explode(':', $fields);

      foreach ($params as $param) {
        if (preg_match_all('/\[(.*?)\]/', $param, $matches)) {
          $matches = explode(',', $matches[1][0]);
          $param = explode('[', $param)[0];

          foreach ($matches as $match) {
            $match = explode('-', $match);
            $matchField[$match[0]] = $match[1];
          }

          $sliceParams[$fieldVerify][$param] = $matchField;
        } else {
          $sliceParams[$fieldVerify][$param] = $param;
        }
      }
    }

    return $sliceParams;
  }
}
