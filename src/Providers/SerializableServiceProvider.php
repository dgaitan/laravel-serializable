<?php

namespace Dgaitan\Serializable\Providers;

use Illuminate\Support\ServiceProvider;
use Dgaitan\Serializable\Console\Commands\MakeSerializableFileCommand;

final class SerializableServiceProvider extends ServiceProvider {
    public function boot(): void {
        if ($this->app->runningInConsole()) {
            $this->commands(
                commands: [
                    MakeSerializableFileCommand::class,
                ],
            );
        }
    }
}
