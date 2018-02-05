<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class UserFollowController extends Controller
{
    public function store(Request $request, $id)
    {
        \Auth::user()->follow($id);
        return redirect()->back();
    }

    public function destroy($id)
    {
        \Auth::user()->unfollow($id);
        return redirect()->back();
    }
}
