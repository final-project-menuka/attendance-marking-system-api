<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\AuthService;
use App\User;
use App\Services\StudentService;
use App\OnGoingLec;
use App\StudentAttendance;
use App\Students;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(AuthService::class,function(){
            return new AuthService(new User());
        });
        $this->app->singleton(StudentService::class,function(){
            return new StudentService(new User(),new OnGoingLec(),new StudentAttendance(),new Students());
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
