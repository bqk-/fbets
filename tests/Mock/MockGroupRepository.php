<?php namespace Mock;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class MockGroupRepository implements \App\Repositories\Contracts\IGroupRepository
{
    public function CreateApplication($iduser, $idgroup, $from, $message) {
        
    }

    public function CreateApplicationPoll($application, $iduser, $idgroup) {
        
    }

    public function CreateGroup($name, $description) {
        
    }

    public function CreateNotification($iduser, $idgroup, $type) {
        
    }

    public function DeleteApplication($user, $group) {
        
    }

    public function Get($id) {
        
    }

    public function GetApplication($user, $group) {
        
    }

    public function GetApplications($idgroup, $limit = 20) {
        
    }

    public function GetNotifications($idgroup, $limit = 20) {
        
    }

    public function GetUsers($idgroup) {
        
    }

    public function PutUserInGroup($user, $group) {
        
    }

    public function GetByName($name) {
        return null;
    }

}