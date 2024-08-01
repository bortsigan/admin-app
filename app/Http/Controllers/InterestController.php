<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Interest;
use Illuminate\Http\Request;
use DB;

class InterestController extends Controller
{
    public function getInterests()
    {
        $interests = Interest::all();
        return response()->json($interests);
    }

}