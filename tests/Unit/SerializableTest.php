<?php

namespace Dgaitan\Serializable\Tests\Unit;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Orchestra\Testbench\Concerns\WithLaravelMigrations;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\Factories\UserFactory;
use Orchestra\Testbench\TestCase;

class SerializableTest extends TestCase {
    use LazilyRefreshDatabase, WithLaravelMigrations, WithWorkbench;

    protected function afterRefreshingDatabase() {
        UserFactory::new()->create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'created_at' => now()->addDay()
        ]);
    }

    public function test_total_users_create(): void {
        $this->assertEquals(1, User::count());
    }
}
