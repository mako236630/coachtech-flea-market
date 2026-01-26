<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            "name" => "required|max:20",
            "postcode" => "required|string|regex:/^\d{3}-\d{4}$/",
            "address" => "required|string",
            "building" => "string|nullable",
            "image" => "image|nullable|mimes:jpeg,png,jpg",
        ];
    }

    public function messages()
    {
        return [
            "name.required" => "お名前を入力してください",
            "name.max" => "お名前は20文字以内で入力してください",
            "postcode.required" => "郵便番号を入力してください",
            "postcode.regex" => "郵便番号は-(ハイフン)を含めた8文字で入力してください",
            "address.required" => "住所を入力してください",
            "image.mimes" => "画像ファイルはjpegかpng形式を選択してください"
        ];
    }
}
