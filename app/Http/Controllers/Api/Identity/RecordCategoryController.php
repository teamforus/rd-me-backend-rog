<?php

namespace App\Http\Controllers\Api\Identity;

use App\Http\Requests\Api\RecordCategories\RecordCategoryStoreRequest;
use App\Http\Requests\Api\RecordCategories\RecordCategoryUpdateRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RecordCategoryController extends Controller
{
    protected $category = [
        "id" => 1,
        "name"=> "Relations",
        "order"=> 0,
    ];

    /**
     * Get list categories
     * @return array
     */
    public function index()
    {
        return [
            $this->category
        ];
    }

    /**
     * Create new record category
     * @param RecordCategoryStoreRequest $request
     * @return array
     */
    public function store(
        RecordCategoryStoreRequest $request
    ) {
        return [
            'success' => $request ? true : true
        ];
    }

    /**
     * Get record category
     * @param int $recordCategoryId
     * @return array|null
     */
    public function show(
        int $recordCategoryId
    ) {
        return $recordCategoryId ? $this->category : $this->category;
    }

    /**
     * Update record category
     * @param RecordCategoryUpdateRequest $request
     * @param int $recordCategoryId
     * @return array
     */
    public function update(
        RecordCategoryUpdateRequest $request,
        int $recordCategoryId
    ) {
        return [
            'success' => ($recordCategoryId && $request) ? true : true
        ];
    }

    /**
     * Delete record category
     * @param int $recordCategoryId
     * @return array
     * @throws \Exception
     */
    public function destroy(
        int $recordCategoryId
    ) {
        return [
            'success' => $recordCategoryId ? true : true
        ];
    }

    /**
     * Sort record categories
     * @param Request $request
     * @return array
     */
    public function sort(
        Request $request
    ) {
        return [
            'success' => $request ? true : true
        ];
    }
}
