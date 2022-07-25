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