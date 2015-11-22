<?php namespace App\Repositories\Contracts;

interface IPollRepository
{

    public function UpdatePolls();

    public function CreatePoll($user, $id_group, $id, $type);
    
    public function AddVote($userid, $id_poll, $opinion);
}
