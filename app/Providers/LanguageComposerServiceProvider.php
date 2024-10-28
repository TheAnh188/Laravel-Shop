<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\LanguageRepositoryInterface as LanguageRepository;


class LanguageComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */

     public $serviceBindings = [
        'App\Repositories\Interfaces\LanguageRepositoryInterface' => 'App\Repositories\LanguageRepository',
    ];

    public function register(): void
    {
        foreach($this->serviceBindings as $key => $value){
            $this->app->bind($key, $value);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //hiển thị các ngôn ngữ ở các view desktop_header
        View::composer('*', function($view) {
            $languageRepository = $this->app->make(LanguageRepository::class);
            $languagess = $languageRepository->all();
            $view->with('languagess', $languagess);
        });
    }
}
