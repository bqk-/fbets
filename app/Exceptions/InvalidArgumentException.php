<?php namespace App\Exceptions;

use Exception;
use \Auth;

class InvalidArgumentException extends Exception
{
    protected $message;

    public function __construct($fieldName, $value, Exception $previous = null)
    {
        $this->message = 'Invalid value for argument: ' . $fieldName . ' = ' . var_export($value, true) . ' (User: ' .
            Auth::user()
        ->id .
            ')';

        parent::__construct($this->message,
            112,
            $previous);
    }

    public function __toString() {
        return __CLASS__ . ": $this->message\n";
    }
}