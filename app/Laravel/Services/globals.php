<?php

if (!function_exists('ceiling')) {
    function ceiling($number, $significance = 1)
    {
        return (is_numeric($number) && is_numeric($significance)) ? (ceil($number / $significance) * $significance) : false;
    }
}

if( !function_exists('nice_number') )
{
    function nice_number($amount)
    {
        $amount = str_replace(",", "", $amount);
        return number_format($amount, 0, '.', ',');
    }
}

if( !function_exists('money_db') )
{
    function money_db($amount)
    {
        $amount = str_replace(",", "", $amount);
        return (float) number_format($amount, 2, '.', '');
    }
}

if( !function_exists('money_format') )
{
    function money_format($amount)
    {
        $amount = str_replace(",", "", $amount);
        return number_format($amount, 2, '.', ',');
    }
}

if (!function_exists('get_client_ip')) {
    function get_client_ip()
    {
        $client_ip = request()->header('X-Forwarded-For');

        if (!$client_ip) {
            $client_ip = request()->ip();
        }
        return $client_ip;
    }
}

if (!function_exists('create_filename')) {

    function create_filename($extension)
    {
        return strtolower(hash('sha256', Str::random(10)) . "." . $extension);
    }

}

if (!function_exists('card_issuer')) {
    function card_issuer($cardNumber) {
        $cardNumber = preg_replace('/\D/', '', $cardNumber); // Remove non-digit characters

        if (preg_match('/^4\d{12}(\d{3})?$/', $cardNumber)) {
            return 'VISA';
        } elseif (preg_match('/^5[1-5]\d{14}$/', $cardNumber) || preg_match('/^2(2[2-9][1-9]|2[3-9]\d\d|[3-6]\d{3}|7[01]\d{2}|720)\d{12}$/', $cardNumber)) {
            return 'Mastercard';
        } elseif (preg_match('/^3[47]\d{13}$/', $cardNumber)) {
            return 'American Express';
        } elseif (preg_match('/^6011\d{12}$/', $cardNumber) || preg_match('/^65\d{14}$/', $cardNumber) || preg_match('/^64[4-9]\d{13}$/', $cardNumber)) {
            return 'Discover';
        } elseif (preg_match('/^35\d{14}$/', $cardNumber)) {
            return 'JCB';
        } elseif (preg_match('/^3(0[0-5]|[68]\d)\d{11}$/', $cardNumber)) {
            return 'Diners Club';
        } else {
            return 'Unknown';
        }
    }
}

if (!function_exists('is_uuid')) {
    function is_uuid($value) {
        return preg_match('/^[0-9a-f\-]{36}$/i', $value)?:false;
    }
}

if (!function_exists('reformat_card_number')) {
    function reformat_card_number($card_number,$card_format='0000 0000 0000 0000') {
        // Remove spaces from card_format to get the expected length
        $plain_format = str_replace(' ', '', $card_format);

        // Split the card_number into an array of characters to match the format
        $chars = str_split($card_number);

        // Replace '0' and 'A' with '%s' for vsprintf formatting
        $format_mask = str_replace(['0', 'A'], '%s', $card_format);

        // Format the card_number to match the card_format
        $formatted_card_number = vsprintf($format_mask, $chars);

        return $formatted_card_number;

    }
}

