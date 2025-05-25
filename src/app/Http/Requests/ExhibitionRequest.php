<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', 'string'],
            'brand' => ['nullable', 'string'],
            'description' => ['required', 'string', 'max:255'],
            'image' => ['required', 'image', 'mimes:jpeg,png'],
            'product_condition_id' => ['required', 'integer'],
            'category_id' => ['required', 'array'],
            'category_id.*' => ['integer'],
            'price' => ['required', 'numeric', 'min:0']
        ];
    }

    public function messages() {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品説明を入力してください',
            'description.max' => '商品説明は255文字以内で入力してください',
            'image.required' => '商品画像を選択してください',
            'image.image' => '拡張子が.jpegもしくは.pngの画像を選択してください',
            'image.mimes' => '拡張子が.jpegもしくは.pngの商品画像を選択してください',
            'product_condition_id.required' => '商品の状態を入力してください',
            'category_id.required' => 'カテゴリーを選択してください',
            'price.required' => '商品価格を入力してください',
            'price.numeric' => '商品価格は数値で入力してください',
            'price.min' => '商品価格は0円以上で入力してください',
        ];
    }

    public function prepareForValidation(){
        $this->merge([
            'price' => str_replace(',', '', $this->price),
        ]);
    }
}
