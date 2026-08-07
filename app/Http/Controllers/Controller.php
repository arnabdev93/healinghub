<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

/**
 * @OA\Info(
 *    title="HealingHub API",
 *    version="1.0.0",
 * )
 */
abstract class Controller extends \Illuminate\Routing\Controller
{
    use AuthorizesRequests;
}
