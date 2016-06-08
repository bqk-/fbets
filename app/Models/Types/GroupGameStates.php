<?php
/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 13/10/15
 * Time: 18:34
 */

namespace App\Models\Types;


abstract class GroupGameStates {
    const NOTHING = 0;
    const IN_VOTE = 1;
    const IN_GROUP = 2;
}