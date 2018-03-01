<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class SoldRequest extends FormRequest
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
        $regex = "/^(?=.+)(?:[1-9]\d*|0)?(?:\.\d+)?$/";

        return [
            'soldPrice' => ['required', 'regex:' . $regex],
            'soldModalWarehouseIDs'    => 'required'
        ];
    }

    public function messages()
    {
        return [
            'soldModalWarehouseIDs.required' => '請輸入賣出金額',
            'soldPrice.regex'  => '賣出金額格式輸入錯誤',
        ];
    }
}
