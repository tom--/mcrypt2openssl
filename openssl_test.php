<?php

$lipsum
    = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
sunt in culpa qui officia deserunt mollit anim id est laborum.";
// Take a plaintext that's an integer number times 16 bytes long.

// 96-byte plaintext is an integer number of blocks for block sizes 8, 16, 24 and 32.
$plaintext = mb_substr($lipsum, 0, 96, '8bit');
$ikm = '';
$iiv = '';
for ($i = 0; $i <= 255; $i += 1) {
    $ikm .= chr($i);
    $iiv .= chr(($i + 128) % 256);
}

// Already processed list of ciphers because openssl_get_cipher_methods() lists many aliases
$ciphers = [
    ['aes-128-cbc', 'aes128'],
    ['aes-128-cfb'],
    ['aes-128-cfb1'],
    ['aes-128-cfb8'],
    ['aes-128-ecb'],
    ['aes-128-ofb'],
    ['aes-192-cbc', 'aes192'],
    ['aes-192-cfb'],
    ['aes-192-cfb1'],
    ['aes-192-cfb8'],
    ['aes-192-ecb'],
    ['aes-192-ofb'],
    ['aes-256-cbc', 'aes256'],
    ['aes-256-cfb'],
    ['aes-256-cfb1'],
    ['aes-256-cfb8'],
    ['aes-256-ecb'],
    ['aes-256-ofb'],
    ['bf-cbc', 'bf', 'blowfish'],
    ['bf-cfb'],
    ['bf-ecb'],
    ['bf-ofb'],
    ['cast5-cbc', 'cast', 'cast-cbc'],
    ['cast5-cfb'],
    ['cast5-ecb'],
    ['cast5-ofb'],
    ['des-cbc', 'des'],
    ['des-cfb'],
    ['des-cfb1'],
    ['des-cfb8'],
    ['des-ecb'],
    ['des-ede'],
    ['des-ede-cbc'],
    ['des-ede-cfb'],
    ['des-ede-ofb'],
    ['des-ede3'],
    ['des-ede3-cbc', 'des3'],
    ['des-ede3-cfb'],
    ['des-ede3-cfb1'],
    ['des-ede3-cfb8'],
    ['des-ede3-ofb'],
    ['des-ofb'],
    ['desx-cbc', 'desx'],
    ['rc2', 'rc2-cbc'],
    ['rc2-40-cbc'],
    ['rc2-64-cbc'],
    ['rc2-cfb'],
    ['rc2-ecb'],
    ['rc2-ofb'],
    ['rc4-40', 'rc4'],
    ['rc5-cbc', 'rc5'],
    ['rc5-cfb'],
    ['rc5-ecb'],
    ['rc5-ofb'],
    ['seed-cbc', 'seed'],
    ['seed-cfb'],
    ['seed-ecb'],
    ['seed-ofb'],
];

$opensslCiphertexts = [];
foreach ($ciphers as $ids) {
    $cipher = $ids[0];
    $aliases = $ids;
    array_shift($aliases);

    $key = $ikm;
    $ivSize = openssl_cipher_iv_length($cipher);

    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, substr($iiv, 0, $ivSize));
    $error = openssl_error_string();

    while (preg_match('{EVP_CIPHER_CTX_set_key_length}', $error)) {
        $key = mb_substr($key, 0, -1, '8bit');
        $ciphertext = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, substr($iiv, 0, $ivSize));
        $error = openssl_error_string();
    }

    if (!$ciphertext || $error || mb_strlen($ciphertext, '8bit') < mb_strlen($plaintext, '8bit')) {
        $status = 'Fail';
        do {
            if ($error) {
                $status .= '. ' . $error;
            }
            $error = openssl_error_string();
        } while ($error);
    } else {
        $keySize = mb_strlen($key, '8bit');
        $padSize = mb_strlen($ciphertext, '8bit') - mb_strlen($plaintext, '8bit');
        $status = 'OK';
        $opensslCiphertexts[$cipher] = [$ids, $keySize, $ivSize, $padSize];
    }

    printf(
        "%14s  %3d  %3d  %3d  %s\n",
        $cipher,
        $keySize,
        $ivSize,
        $padSize,
        implode(',', $aliases)
    );
}
