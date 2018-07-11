<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ProfilesController extends Controller
{
    public function show(User $user) 
    {
    	$activities = $user->activity()->latest()->with('subject')->get()->groupBy( function ($activity) {
    		return $activity->created_at->format('Y-d-m');
    	});
    	
        return view('profiles.show', [
            'user' => $user,
            'activities' => $activities
        ]);
    }
}
