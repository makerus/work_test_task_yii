<?php


namespace backend\exceptions;


use Throwable;

class InvalidRequestJsonStatus extends \InvalidArgumentException
{
    public function __construct($code = 0, Throwable $previous = null)
    {
        parent::__construct('Неверный формат данных или не были переданы url.', $code, $previous);
    }

}