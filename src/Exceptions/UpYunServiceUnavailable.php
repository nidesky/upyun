<?php

namespace Nidesky\Upyun\Exceptions;

use Exception;

class UpYunServiceUnavailable extends UpYunException {
    public function __construct($message, $code = 0, Exception $previous = null) {
        parent::__construct($message, 503, $previous);
    }
}