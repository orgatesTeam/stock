<?php

namespace App\Formatters;

use Illuminate\Http\Request;
use Yish\Generators\Foundation\Format\FormatContract;
use Yish\Generators\Foundation\Format\Statusable;

class Success implements FormatContract
{
    use Statusable;

    protected $status = true;
    
    public function __construct($message = '', $code = 200)
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function message()
    {
        return $this->message;
    }

    public function code()
    {
        return $this->code;
    }
}
