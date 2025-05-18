<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class permissionController extends Controller
{
    //

    public function create()
    {
        return Inertia('permission/create');
    }
}
