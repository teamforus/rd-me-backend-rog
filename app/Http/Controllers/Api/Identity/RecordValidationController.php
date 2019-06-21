<?php

namespace App\Http\Controllers\Api\Identity;

use App\Http\Requests\Api\RecordValidations\RecordValidationStoreRequest;
use App\Models\User;
use App\Models\UserRecord;
use App\Models\Validation;
use App\Http\Controllers\Controller;

class RecordValidationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  RecordValidationStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        RecordValidationStoreRequest $request
    ) {
        return UserRecord::find(
            $request->input('record_id')
        )->validations()->create([
            'uuid' => resolve('token_generator')->generate(64)
        ])->only([
            'uuid'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param string $recordUuid
     * @return \Illuminate\Http\Response
     */
    public function show(
        string $recordUuid
    ) {
        $validation = Validation::findByUuid($recordUuid) ?? abort(404);

        return array_merge($validation->only([
            'state', 'uuid', 'identity_address'
        ]), [
            'key' => $validation->record->key,
            'name' => $validation->record->key,
            'value' => $validation->record->value,
        ]);
    }

    /**
     * Approve validation request
     * @param string $recordUuid
     * @return mixed
     */
    public function approve(
        string $recordUuid
    ) {
        $validation = Validation::findByUuid($recordUuid) ?? abort(404);

        return [
            'success' => $validation->update([
                'state' => Validation::STATE_APPROVED,
                'identity_address' => User::find(auth()->id())->address
            ])
        ];
    }

    /**
     * Decline validation request
     * @param string $recordUuid
     * @return mixed
     */
    public function decline(
        string $recordUuid
    ) {
        $validation = Validation::findByUuid($recordUuid) ?? abort(404);

        return [
            'success' => $validation->update([
                'state' => Validation::STATE_DECLINED
            ])
        ];
    }
}
