<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\User;
use Auth;
use App\Leave;
use App\LeaveRequest;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $current_user_id = Auth::user()->id;
        $get_current_user = User::
        join('employees', 'users.id', '=', 'employees.user_id')
        ->findOrFail($current_user_id, 
            array('users.*', 'employees.first_name', 'employees.last_name', 'employees.middle_name', 'employees.position')
        );

        $current_role = $get_current_user->role;

        $leave_requests = LeaveRequest::where('state_status', 'Approved')
        ->where('user_id', $current_user_id)
        ->paginate(5);

        $user_role_access_summary_total = [1, 2, 3];

        return view('home', compact('user_role_access_summary_total', 'current_role', 'leave_requests', 'get_current_user'));
    }
}
