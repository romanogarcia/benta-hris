<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Employee;
use App\Role;


class CheckUserAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $employee = Employee::findOrFail(Auth::user()->id);
        if($employee) {
            $acl_levels = array();
            // get employee's department and get employees access in role table
            $access = Role::where('department_id', $employee->department_id)->where('is_active', 1)->get()->toArray();
            // check current running controller and method
            $routeArray = app('request')->route()->getAction();
            $controllerAction = class_basename($routeArray['controller']);
            list($controller, $action) = explode('@', $controllerAction);
            //dump($controllerAction);

            $acl = array();
            $methods = array('index','create', 'show', 'edit', 'destroy');
            foreach($access as $acc) {
                if($acc['role'] == 'full') {
                    foreach($methods as $method){
                        $acl[] = $acc['page'] . 'Controller@' . $method;
                    }
                    $acl[] = $acc['page'] . 'Controller@' . $action;
                } else {
                    $acl[] = $acc['page'] . 'Controller@' . $acc['role'];
                    if(!in_array($action, $methods)) {
                        $acl[] = $acc['page'] . 'Controller@' . $action;
                    }
                }
            }
            //dd($acl);
            
            // actual check -> 'full' access etc.
            if(in_array($controllerAction, $acl)){
                return $next($request);
            } else {
                abort(403);
            }
        } 

        return $next($request);
    }
}
