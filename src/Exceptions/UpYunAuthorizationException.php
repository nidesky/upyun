<?php

namespace Nidesky\Upyun\Exceptions;


use Exception;

class UpYunAuthorizationException extends UpYunException {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, 401, $previous);
    }
}