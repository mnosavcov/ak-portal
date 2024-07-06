<?php

namespace Tests\Browser;

use Illuminate\Support\Facades\Artisan;
use Tests\DuskTestCase;

class Test000Begin extends DuskTestCase
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        // Vytvoření instance aplikace
        $app = (new static(''))->createApplication();

        // Inicializace aplikace
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        // Spuštění migrací a seedování
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed', ['--class' => 'DatabaseSeeder']);
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }
}
