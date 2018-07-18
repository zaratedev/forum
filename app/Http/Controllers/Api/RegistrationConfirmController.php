<?php

namespace App\Http\Controllers\Api;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RegistrationConfirmController extends Controller
{
    public function index()
    {
      try {
        User::where('confirmation_token', request('token'))
          ->firstOrFail()
          ->confirm();
      }
      catch(\Exception $e)
      {
				return redirect('/threads')->with('flash', 'Token Invalid');
      }
        
      return redirect('/threads')->with('flash', 'Your account is now activated!');
    }
}
