<?php namespace App\Exceptions;

use Exception;
use \Auth;

class MissingArgumentException extends Exception
{
    protected $message;

    public function __construct($fieldName, Exception $previous = null)
    {
        $this->message = 'Missing field: ' . $fieldName . ' (User: ' . Auth::user()->id . ')';

        parent::__construct($this->message,
            112,
            $previous);
    }

    public function __toString() {
        return __CLASS__ . ": $this->message\n";
    }
}