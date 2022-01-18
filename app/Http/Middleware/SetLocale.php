<?php

namespace App\Http\Middleware;

use App\Models\Settings;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    protected $except_urls = [
        'install'
    ];

    /**
     *
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $regex = '#' . implode('|', $this->except_urls) . '#';

        if (preg_match($regex, $request->path()))
        {
            return $next($request);
        }


        if (Session::has('locale')) {
            $locale = Session::get('locale', config("SETTINGS::LOCALE:DEFAULT"));
        } else {
            if (config("SETTINGS::LOCALE:DYNAMIC") !== "true") {
                $locale = config("SETTINGS::LOCALE:DEFAULT");
            } else {
                $locale = substr($request->server('HTTP_ACCEPT_LANGUAGE'), 0, 2);

                if (!in_array($locale, explode(',', config("SETTINGS::LOCALE:AVAILABLE")))) {
                    $locale = config("SETTINGS::LOCALE:DEFAULT");
                }
            }
        }
        App::setLocale($locale);

        return $next($request);
    }
}
