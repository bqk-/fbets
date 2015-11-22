<?php
/**
 * Created by PhpStorm.
 * User: thibault
 * Date: 13/10/15
 * Time: 18:34
 */

namespace App\Models\Types;


abstract class BetStates {
    const WAITING = 0;
    const WIN = 1;
    const LOOSE = 2;
}