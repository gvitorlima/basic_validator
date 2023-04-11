Projeto pausado para fins de estudo.

---

## BASIC_VALIDATOR

Um validator básico criado apenas para fins de estudo. Incompleto, porém vai abaixo a implementação.

## Validator - Como usar

O validator é instanciado de forma estática através do método `Validator::validate`. O mesmo recebe um `array` como parâmetro e retorna apenas `true`. Qualquer erro de validação é retornada uma `Exception`.
> Obs: Por enquanto a mensagem de erro está fixa para cada validação.

> TODO - Retornar apenas o campo em que a validação falhou, tal como o possível parâmetro de verificação que causou o erro.

## Parâmetros de validação e usabilidade

A validação é realizada com base nas chaves do array, e seus valores são os parâmetros de validação.

Por recomendação, coloque primeiro os parâmetros mais simples, e posteriormente os mais específicos.

```php
use BasicValidator\Validator;

Validator::validate([
  'email.teste@teste.com' => 'require:string[min-12,max-32]:email',
]);
```

Os diferentes tipos de verificação são divididas através de dois pontos `:`, todo e qualquer parâmetro que os mesmos possuam é colocado afrente do tipo de verificação dentro de colchetes `[]`.

> Obs: Todos os parâmetros são independentes de qualquer outro, logo, caso vc informe um valor/tamanho mínimo, não será necessário informar um tamanho máximo para o mesmo.

> TODO -Listar todas as verificações possíveis tal como implementar oque falta.

### TODO - Implementações

__ESPECIAL__

require,
date time,
uuid,
null,
boolean,
instance

__STRING__

length,
email,
min,
max

__INTEGER__

length,
positive,
negative
