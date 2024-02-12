<?php

namespace Dgaitan\Serializable\Console\Commands;

use Illuminate\Console\GeneratorCommand;

final class MakeSerializableFileCommand extends GeneratorCommand {
    protected $signature = "make:serializable {name : The Serializable File}";

    protected $description = "Create a new Serializable class";

    protected $type = 'Serializable';

    protected function getStub(): string {
        return __DIR__ . "/../../../stubs/serializable.stub";
    }

    protected function getDefaultNamespace($rootNamespace): string {
        return "{$rootNamespace}\\Http\\Serializers";
    }
}
