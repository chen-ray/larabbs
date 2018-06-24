<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

/**
 * Class NotificationsController
 * @package App\Http\Controllers
 */
class NotificationsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $notifications  = Auth::user()->notifications()->paginate(20);

        Auth::user()->markAsRead();
        return view('notifications.index', compact('notifications'));
    }
}
