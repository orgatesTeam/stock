<?php

namespace App\Http\Requests\Warehouse;

use Illuminate\Foundation\Http\FormRequest;

class AddRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return array_key_exists(request('buyStockID'),config('stocks'));
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
            'buyPrice' => ['required', 'regex:' . $regex],
            'sheets'    => 'required|integer',
            'buyDate'  => 'required|date'
        ];
    }

    public function messages()
    {
        return [
            'buyPrice.required' => '請輸入買入金額',
            'buyPrice.regex'    => '買入金額格式輸入錯誤',
            'sheets.required'    => '請輸入張數',
            'sheets.integer'     => '張數格式輸入錯誤',
            'buyDate.required'  => '請選擇買入日期',
            'buyDate.date'      => '買入日期格式錯誤'
        ];
    }
}
