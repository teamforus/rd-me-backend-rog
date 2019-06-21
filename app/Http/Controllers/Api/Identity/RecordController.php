<?php

namespace App\Http\Controllers\Api\Identity;

use App\Http\Requests\Api\Records\RecordStoreRequest;
use App\Models\User;
use App\Models\UserRecord;
use App\Http\Controllers\Controller;

class RecordController extends Controller
{
    /**
     * Get list records
     * @return array
     */
    public function index()
    {
        $user = User::with([
            'records.validations_approved'
        ])->find(auth()->id()) ?? abort(404);

        return $user->records->map(function(
            UserRecord $record
        ) {
            $validations = $record->validations_approved->pluck(
                'identity_address'
            );

            return array_merge($record->only([
                'id', 'key', 'value', 'order'
            ]), [
                'validations' => $validations,
                'record_category_id' => null
            ]);
        })->toArray();
    }

    /**
     * Create new record
     * @param RecordStoreRequest $request
     * @return array|bool
     * @throws \Exception
     */
    public function store(
        RecordStoreRequest $request
    ) {
        $user = User::find(auth()->id()) ?? abort(404);
        /** @var UserRecord $record */
        $record = $user->records()->create([
            'key' => $request->input('type'),
            'value' => $request->input('value'),
        ]);

        return array_merge($record->only([
            'id', 'key', 'value', 'order'
        ]), [
            'validations' => $record->validations_approved->pluck(
                'identity_address'
            ),
            'record_category_id' => null
        ]);
    }

    /**
     * Get record
     * @param int $recordId
     * @return array
     */
    public function show(
        int $recordId
    ) {
        $user = User::find(auth()->id()) ?? abort(404);
        $record = $user->records()->find($recordId) ?? abort(404);

        return array_merge($record->only([
            'id', 'key', 'value', 'order'
        ]), [
            'validations' => $record->validations_approved->pluck(
                'identity_address'
            ),
            'record_category_id' => null
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $recordId
     * @return \Illuminate\Http\Response
     */
    public function update(
        int $recordId
    ) {
        $user = User::find(auth()->id()) ?? abort(404);
        $record = $user->records()->find($recordId) ?? abort(404);

        return array_merge($record->only([
            'id', 'key', 'value', 'order'
        ]), [
            'validations' => $record->validations_approved->pluck(
                'identity_address'
            ),
            'record_category_id' => null
        ]);
    }

    /**
     * Delete record
     * @param int $recordId
     * @return array
     * @throws \Exception
     */
    public function destroy(
        int $recordId
    ) {
        $record = UserRecord::find($recordId);
        $success = $record->key == 'primary_email' ? false : true;
        $success = $success ? $record->delete() : $success;

        return compact('success');
    }

    /**
     * Sort records
     * @return array
     */
    public function sort() {
        return [
            'success' => true
        ];
    }
}
