<?php

use Illuminate\Database\Seeder;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('oauth_clients')->insert([
            'name'                      => 'Access client',
            'secret'                    => 'y2wkf7rN2wn1z8aCeBrHlqxzau9XM7y4dPR65Fz3',
            'redirect'                  => 'http://localhost',
            'personal_access_client'    => 1,
            'password_client'           => 0,
            'revoked'                   => 0,
        ]);

        DB::table('oauth_clients')->insert([
            'name'                      => 'Access client',
            'secret'                    => 'y2wkf7rN2wn1z8aCeBrHlqxzau9XM7y4dPR65Fz3',
            'redirect'                  => 'http://localhost',
            'personal_access_client'    => 0,
            'password_client'           => 1,
            'revoked'                   => 0,
        ]);

        DB::table('oauth_personal_access_clients')->insert([
            'client_id' => 1
        ]);
    }
}
