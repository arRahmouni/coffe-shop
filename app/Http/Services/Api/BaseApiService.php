<?php

namespace app\Http\Services\Api;

use Illuminate\Support\Facades\Storage;

class BaseApiService
{
    public $data = [];

    /**
     * Fields that are not necessary for creating or updating the model.
     */
    protected $unnecessaryFieldsForCrud = [];

    public function __construct()
    {
        //
    }

    /**
     * Remove unnecessary fields from the data array before creating or updating the model.
     *
     * @param array $data
     * @return array
     */
    protected function prepareModelData(array $data) : array
    {
        $data = array_diff_key($data, array_flip($this->unnecessaryFieldsForCrud));

        return $data;
    }

    /**
     * Upload image for the model.
     *
     * @param mixed $model
     * @param array $data
     * @param string $path
     * @param string $driver
     * @param string $field
     * @return mixed
     */
    protected function uploadImageForModel(mixed $model, array $data, string $path = 'images', string $driver = 'public', string $field = 'image'): mixed
    {
        if(isset($data[$field])) {
            // delete the old image
            if($model->{$field} && Storage::disk('public')->exists($model->{$field})) {
                Storage::disk('public')->delete($model->{$field});
            }

            // store the image
            $image = $data[$field];
            $image->store($path, $driver);
            $model->update([$field => $path . '/' . $image->hashName()]);
        }

        return $model;
    }
}


