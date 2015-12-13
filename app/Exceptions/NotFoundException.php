<?php namespace App\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    private $message;

    public function __construct($objectName, $field, $value, Exception $previous = null)
    {
        $this->message = "User " . Auth::user()->id . "
            tried to access <" . $objectName . ">
            with " . $field . " = " . var_dump($value);

        parent::__construct($this->message,
            111,
            $previous);
    }

    public function __toString() {
        return __CLASS__ . ": $this->message\n";
    }
}