<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use Closure;
use Illuminate\Http\Request;

class GlobalNames
{

    protected $except_urls = [
        'install'
    ];
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $regex = '#' . implode('|', $this->except_urls) . '#';

        if (preg_match($regex, $request->path()))
        {
            return $next($request);
        }

        define('CREDITS_DISPLAY_NAME' , Settings::getValueByKey('SETTINGS::SYSTEM:CREDITS_DISPLAY_NAME' , 'Credits'));

        return $next($request);
    }
}
