<?php namespace App\Models\Types;

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class NotificationTypes
{
    const JOIN = 1;
    const QUIT = 2;
    const APPLY = 3;
    const PROPOSE = 4;
    const POLL_START = 5;
    const POLL_END = 6;
    const PRIZE_START = 7;
    const PRIZE_END = 8;
    const DELETE_APPLY = 9;
}