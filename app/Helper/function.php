<?php

/**
 * Generate Avatar
 *
 * @param $name = String
 * @param $type = ['male', 'female', 'human', 'identicon', 'initials', 'bottts', 'avataaars', 'jdenticon', 'gridy', 'micah']
 */
function getAvatar($name, $type = 'initials')
{
    if ($type == 'custom') {
        $avatar = asset($name);
    } else {
        $avatar = 'https://avatars.dicebear.com/api/'.$type.'/'.$name.'.svg';
    }

    return $avatar;
}

/**
 * siaCryption
 */
function siaCryption($value, $encrypt = false)
{
    $method = new \Illuminate\Encryption\Encrypter(env('APP_PRIVATE_KEY'), config('app.cipher'));
    $data = null;
    if ($encrypt) {
        $data = $method->encrypt($value);
    } elseif (! ($encrypt)) {
        try {
            $data = $method->decrypt($value);
        } catch (\RuntimeException $e) {
            $data = $value;

            \Log::debug("[Custom Helper] Check Decryption, fail to decrypt ~ app\Helper\function@siaCryption", [
                'value' => $value,
                'exception' => $e,
            ]);
        }
    }

    return $data;
}

/**
 * Format Rupiah
 *
 * Print number in Indonesian Rupiah
 */
function formatRupiah($number = 0, $prefix = true)
{
    // Check User Preferenfe
    if (auth()->check()) {
        $preference = \App\Models\UserPreference::where('user_id', auth()->user()->id)
            ->where('key', 'hide-balance')
            ->first();
        if (! empty($preference) && $preference->value) {
            return ($prefix ? 'Rp ' : '').'---';
        }
    }

    $number = round($number, 2);
    $decimal = null;
    $checkDecimal = explode('.', $number);
    if (count($checkDecimal) > 1) {
        $decimal = $checkDecimal[1];
    }

    return ($prefix ? 'Rp ' : '').number_format((int) $number, 0, ',', '.').(! empty($decimal) ? ','.$decimal : '');
}

/**
 * Date Format
 */
function dateFormat($rawDate, $type = 'days')
{
    $date = date('Y-m-d H:i:s', strtotime($rawDate));
    $result = '';

    switch ($type) {
        case 'days':
            $result = date('l', strtotime($date));
            switch ($result) {
                case 'Monday':
                    $result = 'Senin';
                    break;
                case 'Tuesday':
                    $result = 'Selasa';
                    break;
                case 'Wednesday':
                    $result = 'Rabu';
                    break;
                case 'Thursday':
                    $result = 'Kamis';
                    break;
                case 'Friday':
                    $result = "Jum'at";
                    break;
                case 'Saturday':
                    $result = 'Sabtu';
                    break;
                case 'Sunday':
                    $result = 'Minggu';
                    break;
            }
            break;
        case 'months':
            $result = date('F', strtotime($date));
            switch ($result) {
                case 'January':
                    $result = 'Januari';
                    break;
                case 'February':
                    $result = 'Februari';
                    break;
                case 'March':
                    $result = 'Maret';
                    break;
                case 'April':
                    $result = 'April';
                    break;
                case 'May':
                    $result = 'Mei';
                    break;
                case 'June':
                    $result = 'Juni';
                    break;
                case 'July':
                    $result = 'Juli';
                    break;
                case 'August':
                    $result = 'Agustus';
                    break;
                case 'September':
                    $result = 'September';
                    break;
                case 'October':
                    $result = 'Oktober';
                    break;
                case 'November':
                    $result = 'November';
                    break;
                case 'December':
                    $result = 'Desember';
                    break;
            }
            break;
    }

    return $result;
}

/**
 * 
 */
function convertToUtc($datetime, $offset, $utc = true)
{
    $original = $datetime;
    $originalOffset = $offset;

    // Convert to UTC
    if ($utc) {
        $offset *= -1;
    }
    $offsetInSeconds = ($offset * -1) * 60;
    $utc = date('Y-m-d H:i:s', strtotime($datetime) + $offsetInSeconds);

    return $utc;
}