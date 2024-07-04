<?php

namespace Tests\App;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class Test002CreateUsers extends TestCase
{

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    /**
     * @dataProvider \Tests\App\DataUsers::users()
     */
    public function test_new_users_can_register($input, $expected): void
    {
        $response = $this->post('/register', $input);

        $response->assertStatus(200);
        $this->assertAuthenticated();
        $this->assertEquals(json_encode(['status' => 'ok']), $response->getContent());

        if (isset($input['advanced'])) {
            foreach($input['advanced'] as $key => $value) {
                auth()->user()->{$key} = $value;
            }
            auth()->user()->save();
        }
        $user = User::find(auth()->user()->id)->toArray();

        foreach ($user as $column => $value) {
            if (in_array($column, DataUsers::SKIP)) {
                continue;
            }
            $this->assertEquals($value, $expected[$column] ?? DataUsers::DEFAULT_EXPECTED[$column] ?? null, $column);
        }
    }

    /**
     * @dataProvider \Tests\App\DataUsers::users()
     */
    public function test_verification_and_logout_screen_can_be_rendered($input): void
    {
        $response = $this->post('/login', [
            'email' => $input['kontakt']['email'],
            'password' => 'password',
        ]);

        $response->assertRedirect(route('profile.edit'));

        $user = auth()->user();

        Event::fake();

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $response->assertRedirect(RouteServiceProvider::HOME.'?verified=1');

        $this->assertNotNull(auth()->user());

        $response = $this->post('/logout');

        $this->assertNull(auth()->user());

        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
