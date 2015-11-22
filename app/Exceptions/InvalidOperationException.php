<?php namespace App\Exceptions;

use Exception;
use \Auth;

class InvalidOperationException extends Exception
{
    private $message;

    public function __construct($operation, Exception $previous = null)
    {
        $this->message = 'Invalid operation: ' . $operation . ' (User: ' . Auth::user()
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
