<?php namespace App\Repositories\Contracts;

interface IPollRepository
{

    public function DeletePoll($id_poll);

    public function GetVote($id_poll, $id_user);

    public function GetPoll($id_poll);

    public function DeleteUserVotes($id_user, $idgroup);

    public function GetVotes($id_poll);
    
    public function AddVote($userid, $id_poll, $opinion);
    
    public function CreateApplicationPoll($iduser, $idgroup);
    
    public function CreateGamePoll($idgame, $idgroup);
    
    public function GetPollsCreatedBefore($date);
}
