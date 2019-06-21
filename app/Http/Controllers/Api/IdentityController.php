<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\IdentityStoreRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;

class IdentityController extends Controller
{
    /**
     * @return array
     */
    public function getPublic()
    {
        return [
            'address' => User::find(auth()->id())->address
        ];
    }

    /**
     * Create new identity
     *
     * @param IdentityStoreRequest $request
     * @return array
     * @throws \Exception
     */
    public function store(
        IdentityStoreRequest $request
    ) {
        $records = collect($request->input('records', []));
        $user = User::create([
            'email' => $records['primary_email'],
            'address' => resolve('token_generator')->address(),
            'password' => resolve('hash')->make(
                resolve('token_generator')->generate(32)
            )
        ]);

        foreach ($records as $key => $value) {
            $user->records()->create(compact('key', 'value'));
        }

        return [
            'access_token' => $user->createToken('access_token')->accessToken
        ];
    }

    /**
     * @param $userId
     * @return string
     */
    public function auth(
        $userId
    ) {
        $user = User::find($userId) ?? abort(404);
        $qrCode = new QrCode(json_encode([
            'type' => 'access_token',
            'value' => $user->createToken('access_token')
        ]));

        $qrCode->setSize(1000);
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::LOW));

        return response($qrCode->writeString(), 200, [
            'Content-Type' => $qrCode->getContentType()
        ]);
    }
}
