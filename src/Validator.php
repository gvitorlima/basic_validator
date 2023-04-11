<?php

declare(strict_types=1);

namespace Validators;

use Validators\Classes\Especial_;
use Validators\Classes\String_;

include_once __DIR__ . '/Params.php';

class Validator
{
  private static self $instance;

  private Especial_ $objValEspecial;

  private String_ $objValString;

  private array
    $dataForVerification;

  /**
   * Array que serve para organizar os parâmetros por tipo.
   */
  private static array $especialParams, $mandatoryParams;

  private function __construct()
  {
    self::$especialParams = especialParams();
    self::$mandatoryParams = mandatoryParams();

    $this->objValEspecial = new Especial_;
    $this->objValString = String_::create();
  }

  private function __clone()
  {
  }

  public static function validate(string|array $dataValidate): bool
  {
    if (!isset(self::$instance)) {
      self::$instance = new self;
    }

    self::$instance->setVerificationData($dataValidate);
    self::$instance->organizeParams();

    try {

      foreach (self::$instance->dataForVerification as $data => $verificationParameters) {
        self::$instance->verify($data, $verificationParameters);
      }

      return true;
    } catch (\Throwable $err) {
      echo '<pre>';
      print_r($err->getMessage());
      echo '</pre>';
      exit;
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
   * * Métodos que mapeiam os dados e parâmetros de verificação
   */
  private function organizeParams(): void
  {
    foreach ($this->dataForVerification as $fieldVerify => $params) {
      $params = $this->sliceFields($params);
      $params = $this->especialParams($params);

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

  public function especialParams(array $params): array
  {
    $especialParams = [];
    foreach (self::$especialParams as $_ => $param) {
      if (key_exists($param, $params)) {
        $especialParams['especial'][$param] = $params[$param];
        unset($params[$param]);
      }
    }
    return array_merge($params, $especialParams);
  }

  /**
   * * Métodos 
   */
  private function verify(mixed $data, array $fields): bool
  {
    $this->verifyMandatoryData($fields);
    if (key_exists('require', $fields)) {
      $this->objValEspecial->require($data);
      unset($fields['require']);
    }

    match ($fields) {
      'string',
      'especial',
      'numeric'
    };
    return true;
  }

  private function verifyMandatoryData(array $fields): void
  {
    $verify = array_filter(self::$mandatoryParams, function ($param) use ($fields) {
      return array_key_exists($param, $fields);
    })[0];

    if (!is_string($verify)) {
      $error = ['error' => 'Declare o tipo do parâmetro a ser verificado', 'field' => ['expected' => 'string|integer|bool|null', 'passed' =>  null]];
      throw new \Exception(json_encode($error), 500);
    }
  }
}
