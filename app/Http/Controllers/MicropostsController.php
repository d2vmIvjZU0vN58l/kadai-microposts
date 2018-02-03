<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class MicropostsController extends Controller
{
    
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:255',
        ]);
        
        $request->user()->microposts()->create([
            'content' => $request->content,
        ]);
        
        return redirect('/');
    }

    
    public function destroy($id)
    {
        $micropost = \App\Micropost::find($id);
        
        if (\Auth::user()->id === $micropost->user_id) {
            $micropost->delete();
        }
        
        return redirect()->back();
    }
}
