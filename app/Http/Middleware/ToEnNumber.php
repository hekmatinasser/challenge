<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ToEnNumber
{
    public function handle(Request $request, Closure $next): Response
    {

        $request->merge([
            'source_card_number'      => $this->toEnNumber($request->get('source_card_number')),
            'destination_card_number' => $this->toEnNumber($request->get('destination_card_number')),
            'amount'                  => $this->toEnNumber($request->get('amount')),
        ]);


        return $next($request);
    }

    function toEnNumber($number): string
    {
        $conversion_map = array(
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',

            //arabic
            '٠' => '0', '١' => 1, '٢' => 2, '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9'
        );

        return (int) strtr($number, $conversion_map);
    }
}
