<?php
// # ENCRYPT/DECRYPT -------------------------------------------------------------------------------------------------

namespace systasisgfifscrm;

class SystasisGFIFSCrypto
{
    public const RAW_APIKEY_PREFIX = 'KeapAK-';

    public const COOKED_APIKEY_PREFIX = 'KeapAK*';

    private const METHOD = 'aes-256-ctr';

    public static function encrypt($victim)
    {
        $length = strlen(self::RAW_APIKEY_PREFIX);
        if (self::RAW_APIKEY_PREFIX == \substr($victim, 0, $length)) {
            $cooked =  self::do_encrypt(substr($victim, $length, \strlen($victim)), 'fred', true);
            return self::COOKED_APIKEY_PREFIX . $cooked;
        } else {
            return $victim;
        }
    }

    public static function decrypt($victim)
    {
        $length = strlen(self::COOKED_APIKEY_PREFIX);
        if (self::COOKED_APIKEY_PREFIX == \substr($victim, 0, $length)) {
            $raw = self::do_decrypt(substr($victim, $length, \strlen($victim)), 'fred', true);
            return self::RAW_APIKEY_PREFIX . $raw;
        } else {
            return $victim;
        }
    }

    // https://stackoverflow.com/questions/9262109/simplest-two-way-encryption-using-php

    /**
     * Encrypts (but does not authenticate) a message
     * 
     * @param string $message - plaintext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encode - set to TRUE to return a base64-encoded 
     * @return string (raw binary)
     */
    private static function do_encrypt($message, $key, $encode = false)
    {
        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = openssl_random_pseudo_bytes($nonceSize);

        $ciphertext = openssl_encrypt(
            $message,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        // Now let's pack the IV and the ciphertext together
        // Naively, we can just concatenate
        if ($encode) {
            return base64_encode($nonce . $ciphertext);
        }
        return $nonce . $ciphertext;
    }

    /**
     * Decrypts (but does not verify) a message
     * 
     * @param string $message - ciphertext message
     * @param string $key - encryption key (raw binary expected)
     * @param boolean $encoded - are we expecting an encoded string?
     * @return string
     */

    private static function do_decrypt($message, $key, $encoded = false)
    {
        if ($encoded) {
            $message = base64_decode($message, true);
            if ($message === false) {
                throw new \Exception('Decryption failure in base64_decode');
            }
        }

        $nonceSize = openssl_cipher_iv_length(self::METHOD);
        $nonce = mb_substr($message, 0, $nonceSize, '8bit');
        $ciphertext = mb_substr($message, $nonceSize, null, '8bit');

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::METHOD,
            $key,
            OPENSSL_RAW_DATA,
            $nonce
        );

        return $plaintext;
    }
}
