<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class InstallController extends Controller
{
    private function setEnvironmentValue($envKey, $envValue)
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');

        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $str .= "\n"; // In case the searched variable is in the last line without \n
        $keyPosition = strpos($str, "{$envKey}=");
        $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
        $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
        $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
        $str = substr($str, 0, -1);

        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);

        Artisan::call('config:cache');
    }


    /** Show the application dashboard. */
    public function index(Request $request)
    {
        if(file_exists(base_path()."/install.lock")){
            die ("Installation already completed. To Re-Run please delete install.lock in your Root folder");
        }

        if($request->get("step")){
            return view('install')->with([
                'step' => "2",
            ]);
        }
        return view('install');
    }


    public function checkDB (Request $request)
    {


        $values = [
            //SETTINGS::VALUE => REQUEST-VALUE (coming from the html-form)
            "DB_HOST" => "databasehost",
            "DB_DATABASE" => "database",
            "DB_USERNAME" => "databaseuser",
            "DB_PASSWORD" => "databaseuserpass",
            "DB_PORT" => "databaseport",
            "DB_CONNECTION" => "databasedriver"
        ];

        foreach ($values as $key => $value) {
            $param = $request->get($value);
            $this->setEnvironmentValue($key, $param);
        }

        try {
            DB::table('settings')->get();
            return view('install')->with([
                'step' => "3",
                'message' => "The Database connection was successful"
            ]);
        } catch (\Exception $e) {
            return view('install')->with([
                'step' => "2",
                'message' => "The Database connection was not successful"
            ]);
        }

    }


    public function checkGeneral (Request $request)
    {

        $values = [
            //SETTINGS::VALUE => REQUEST-VALUE (coming from the html-form)
            "APP_URL" => "url",
            "APP_NAME" => "name",
        ];

        foreach ($values as $key => $value) {
            $param = $request->get($value);
            $this->setEnvironmentValue($key, $param);
        }

        try {
            return view('install')->with([
                'step' => "4",
            ]);
        } catch (\Exception $e) {
            return view('install')->with([
                'step' => "3",
                'message' => "Something went wrong"
            ]);
        }

    }

    public function checkSMTP (Request $request)
    {


        $values = [
            //SETTINGS::VALUE => REQUEST-VALUE (coming from the html-form)
            "MAIL_MAILER" => "databasehost",
            "MAIL_HOST" => "database",
            "MAIL_PORT" => "databaseuser",
            "MAIL_USERNAME" => "databaseuserpass",
            "MAIL_PASSWORD" => "databaseport",
            "MAIL_ENCRYPTION" => "databasedriver",
            "MAIL_FROM_ADDRESS" => ""
        ];

        foreach ($values as $key => $value) {
            $param = $request->get($value);
            $this->setEnvironmentValue($key, $param);
        }

        try {
            return view('install')->with([
                'step' => "3",
                'message' => "The Database connection was successful"
            ]);
        } catch (\Exception $e) {
            return view('install')->with([
                'step' => "2",
                'message' => "The Database connection was not successful " . env("DB_PASSWORD")
            ]);
        }

    }

}
