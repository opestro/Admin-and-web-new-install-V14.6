<?php

namespace App\Http\Controllers;

use App\Contracts\ControllerInterface;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as MainController;

abstract class BaseController extends MainController implements ControllerInterface
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
