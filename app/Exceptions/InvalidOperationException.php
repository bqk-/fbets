<?php namespace App\Exceptions;

use Exception;
use \Auth;

class InvalidOperationException extends Exception
{
    protected $message;

    public function __construct($operation, Exception $previous = null)
    {
        if(Auth::check())
        {
            $this->message = 'Invalid operation: ' . $operation . ' (User: ' . Auth::user()
                ->id .
            ')';
        }
        else
        {
            $this->message = 'Invalid operation: ' . $operation;
        }

        parent::__construct($this->message,
            112,
            $previous);
    }

    public function __toString() {
        return __CLASS__ . ": $this->message\n";
    }
}
