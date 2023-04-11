<?php

function especialParams(): array
{
  return [
    'email',
    'uuid',
    'datetime',
    'instance',
  ];
}

function mandatoryParams(): array
{
  return [
    'string',
    'number',
    'array'
  ];
}
