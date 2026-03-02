<?php

namespace App\Http\Controllers;
use App\Models\Thread;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function store(Request $request, Thread $thread) {
        $request->validate(['body'=>'required|string']);
        $thread->posts()->create([
            'body'=>$request->body,
            'user_id'=>auth()->id()
        ]);
        return redirect()->route('threads.show',$thread)->with('success','Reply posted!');
    }
}