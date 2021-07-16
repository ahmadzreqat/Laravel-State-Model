<?php


namespace statemm;


use Illuminate\Support\ServiceProvider;
use statemm\Console\StateCommands;


class StateServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->commands([StateCommands::class]);
        $this->registerPublishable();
    }


    private function registerPublishable()
    {
        $basePath = dirname(__DIR__);

        $PublishAble = [
            'config.state' => [
                "$basePath/publish/config/state.php" => config_path('state.php')
            ]
        ];

        foreach ($PublishAble as $group => $paths) {
            $this->publishes($paths, $group);
        }
    }
}
