<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreMultipleRecordsRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'data.*.*.subcategory' => 'required|string|max:255',
            'data.*.*.url' => 'nullable|string|max:255',
            'data.*.*.category' => 'required|string|max:255',
        ];
    }

    protected function prepareForValidation()
    {
        $data = $this->all();

        foreach ($data['data'] as $category => &$records) {
            foreach ($records as &$record) {
                if (empty($record['url']) && !empty($record['subcategory'])) {
                    $record['url'] = Str::slug($record['subcategory']);
                }
            }
        }

        $this->replace($data);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $data = $this->all();
            $uniqueCheck = [];

            foreach ($data['data'] as $category => $records) {
                foreach ($records as $index => $record) {
                    $slug = $record['url'];

                    // Check for uniqueness in combination of category and slug
                    if (isset($uniqueCheck[$category][$slug])) {
                        $validator->errors()->add("data.{$category}.{$index}.url", 'Kombinace kategorie a url musí být unikátní (1).');
                    } else {
                        $uniqueCheck[$category][$slug] = true;
                    }
                }
            }
        });
    }

    public function messages()
    {
        return [
            'data.*.*.subcategory.required' => 'Pole titulek je povinné.',
            'data.*.*.url.unique' => 'Kombinace kategorie a url musí být unikátní (2).',
        ];
    }
}
