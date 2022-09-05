<?php

namespace App\Providers;

use App\Interfaces\CaptchaInterface;
use App\Interfaces\EmailInterface;
use App\Interfaces\MetaInterface;
use App\Interfaces\RequestInterface;
use App\Services\CaptchaService;
use App\Services\EmailService;
use App\Services\MetaService;
use App\Services\RequestService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @todo Strategy pattern for the services via config file.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(EmailInterface::class, fn () => new EmailService(new CaptchaService));
        $this->app->bind(CaptchaInterface::class, fn () => new CaptchaService);
        $this->app->bind(MetaInterface::class, fn () => new MetaService);
        $this->app->bind(RequestInterface::class, fn () => new RequestService);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('local')) {
            Mail::alwaysTo('lewyy2501@gmail.com');
        }
    }
}
