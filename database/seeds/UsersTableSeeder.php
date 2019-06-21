<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $token_generator = resolve('token_generator');
        $hash = resolve('hash');

        $users = [[
            'id' => 1,
            'email' => 'dev1@dev-rminds.nl',
            'address' => $token_generator->address(),
            'password' => $hash->make($token_generator->generate(32)),
            'records' => [
                'first_name' => "Jamal",
                'last_name' => "Vleij",
            ]
        ], [
            'id' => 2,
            'email' => 'dev2@dev-rminds.nl',
            'address' => resolve('token_generator')->address(),
            'password' => $hash->make($token_generator->generate(32)),
            'records' => [
                'first_name' => "Gerben",
                'last_name' => "Bosschieter",
            ]
        ], [
            'id' => 3,
            'email' => 'dev3@dev-rminds.nl',
            'address' => resolve('token_generator')->address(),
            'password' => $hash->make($token_generator->generate(32)),
            'records' => [
                'first_name' => "Max",
                'last_name' => "Visser",
            ]
        ]];

        foreach ($users as $id => $user) {
            $user = User::create(array_merge(
                collect($user)->only([
                    'id', 'email', 'address', 'password',
                ])->toArray(), []
            ));

            foreach ($user['records'] as $key => $value) {
                $user->records()->create(compact(['key' => 'value']));
            }
        }
    }
}
