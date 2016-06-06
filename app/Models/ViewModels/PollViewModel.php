<?php namespace App\Models\ViewModels;

use App\Models\Services\PollStatus;
use App\Models\Services\UserBrief;

class PollViewModel
{
    public $Id;
    public $Type;
    public $ExpirationDate;
    public $Status;
    public $MyVote;
    public $Text;
    public $User;
    public $Game;
    
    public function __construct($id, $type, $date, PollStatus $status, $my, UserBrief $user)
    {
        $this->Id = $id;
        $this->Type = $type;
        $this->ExpirationDate = $date;
        $this->Status = $status;
        $this->MyVote = $my;
        $this->User = $user;
        $this->Text = $this->FormatPoll();
    }
    
    private function FormatPoll()
    {
        return trans('polls.type_' . $this->Type, array('user' => $this->User->Pseudo, 'id' => $this->User->Id));
    }
}
