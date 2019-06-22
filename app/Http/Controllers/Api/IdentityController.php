<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Identity\IdentityAuthorizeCheckTokenRequest;
use App\Http\Requests\Api\Identity\IdentityAuthorizeTokenRequest;
use App\Http\Requests\Api\IdentityStoreRequest;
use App\Http\Controllers\Controller;
use App\Models\QrToken;
use App\Models\User;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;

class IdentityController extends Controller
{
    use ThrottlesLogins;

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
     * @param IdentityStoreRequest $request
     * @return array
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
            'access_token' => $user->makeToken()
        ];
    }

    /**
     * @param $userId
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function auth(
        $userId
    ) {
        $user = User::find($userId) ?? abort(403);
        $qrCode = new QrCode(json_encode([
            'type' => 'access_token',
            'value' => $user->makeToken()
        ]));

        $qrCode->setSize(1000);
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::LOW));

        return response($qrCode->writeString(), 200, [
            'Content-Type' => $qrCode->getContentType()
        ]);
    }

    /**
     * @return array
     */
    public function proxyAuthorizationToken() {
        $token_generator = resolve('token_generator');

        return QrToken::create([
            'auth_token' => $token_generator->generate(128),
            'check_token' => $token_generator->generate(512),
        ])->only([
            'auth_token', 'check_token', 'confirmed'
        ]);
    }

    /**
     * @param IdentityAuthorizeTokenRequest $request
     * @return array
     */
    public function proxyAuthorizeToken(
        IdentityAuthorizeTokenRequest $request
    ) {
        $user = User::find(auth()->id()) ?? abort(403);
        $qrToken = QrToken::findByAuthToken(
            $request->post('auth_token')
        ) ?? abort(404);

        $qrToken->update([
            'user_id' => $user->id,
            'access_token' => $user->makeToken(),
        ]);

        return [
            'success' => $qrToken->update([
                'user_id' => $user->id,
                'access_token' => $user->makeToken(),
            ])
        ];
    }

    /**
     * @param $authToken
     * @return array
     */
    public function proxyConfirmShareToken(
        $authToken
    ) {
        $qrToken = QrToken::findByAuthToken($authToken) ?? abort(404);

        return [
            'success' => $qrToken->update([
                'confirmed' => true
            ])
        ];
    }

    /**
     * @param IdentityAuthorizeCheckTokenRequest $request
     * @param $checkToken
     * @return array
     */
    public function proxyCheckToken(
        IdentityAuthorizeCheckTokenRequest $request,
        $checkToken
    ) {
        if ($this->hasTooManyLoginAttempts($request)) {
            abort(429, 'To many attempts.');
        }

        if (!$qrToken = QrToken::findByCheckToken($checkToken)) {
            $this->incrementLoginAttempts($request);
            abort(404);
        }

        return $qrToken->only([
            'access_token', 'confirmed'
        ]);
    }

    /**
     * Get the throttle key for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return strtolower($request->ip());
    }
}
