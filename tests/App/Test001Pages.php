<?php

namespace Tests\App;

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class Test001Pages extends TestCase
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

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     */
    public function test_the_page_projekty_returns_a_successful_xresponse(): void
    {
        $response = $this->get('/projekty');

        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     */
    public function test_the_page_jak_to_funguje_returns_a_successful_xresponse(): void
    {
        $response = $this->get('/jak-to-funguje');

        $response->assertStatus(200);
    }

    /**
     * A basic test example.
     */
    public function test_the_page_kontakt_returns_a_successful_xresponse(): void
    {
        $response = $this->get('/kontakt');

        $response->assertStatus(200);
    }
}
