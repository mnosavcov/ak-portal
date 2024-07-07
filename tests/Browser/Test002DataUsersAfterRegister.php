<?php

namespace Tests\Browser;

use App\Models\User;
use Tests\DataSets\DataUsers;
use Tests\TestCase;

class Test002DataUsersAfterRegister extends TestCase
{

    /**
     * @dataProvider \Tests\DataSets\DataUsers::users()
     */
    public function test_new_users_after_register($input, $expected): void
    {
        $user = User::where('email', $input['kontakt']['email'])->first()->toArray();

        foreach ($user as $column => $value) {
            if (in_array($column, DataUsers::SKIP)) {
                continue;
            }
            $this->assertEquals($value, $expected[$column] ?? DataUsers::DEFAULT_EXPECTED[$column] ?? null, $column);
        }
    }
}
