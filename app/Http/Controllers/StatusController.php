<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatusResource;

class StatusController extends Controller
{
    public function __invoke()
    {
        return (new StatusResource(null));
    }
}
