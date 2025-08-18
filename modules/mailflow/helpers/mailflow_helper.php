<?php

if ( ! function_exists('mailflow_encryption'))
{
    function mailflow_encryption($data = false, $type = 0)
    {

        try {

            $encryptConfigs = json_decode('{
    "CipherMethod": "AES-256-CBC"
}', true);

            $configsKey = "92a9314ebd9deed8608c4c6103476966e95d1feb495e00541af3e880c0f82badbff719c981f7896ee567e071ec9b509094fc1c200e75c441"; // This should be a secure, randomly generated key for encryption/decryption

            switch ($type) {
                case 0:
                    // Encryption
                    $ivLength = openssl_cipher_iv_length($encryptConfigs['CipherMethod']);
                    $iv = openssl_random_pseudo_bytes($ivLength);
                    $encryptedData = openssl_encrypt($data, $encryptConfigs['CipherMethod'], $configsKey, 0, $iv);
                    return bin2hex($iv . $encryptedData);
                    break;
                case 1:
                    // Decryption
                    $data = hex2bin($data);
                    $ivLength = openssl_cipher_iv_length($encryptConfigs['CipherMethod']);
                    $iv = substr($data, 0, $ivLength);
                    $encryptedData = substr($data, $ivLength);
                    return openssl_decrypt($encryptedData, $encryptConfigs['CipherMethod'], $configsKey, 0, $iv);
                    break;
                default:
                    return false; // Invalid type
            }


        } catch (Exception $e) {

            show_404();

        }

    }
}