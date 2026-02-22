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
            "name" => "required",
            "description" => "required|max:255",
            "price" => "required|numeric|min:1",
            "image" => "required|mimes:jpeg,png,jpg",

            "category_ids" => "required",
            "condition_id" => "required",
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "商品名を入力してください",
            "description.required" => "商品説明を入力してください",
            "description.max" => "商品説明は255文字以内で入力してください",
            "price.required" => "販売価格を入力してください",
            "price.numeric" => "販売価格は半角数字で入力してください",
            "price.min" => "販売価格は1円以上の金額を入力してください",
            "image.required" => "画像を添付してください",
            "image.mimes" => "画像ファイルはjpeg、png、jpg形式を選択してください",

            "category_ids.required" => "商品のカテゴリーを選択してください",
            "condition_id.required" => "商品の保存状態を選択してください",
        ];
    }
}
