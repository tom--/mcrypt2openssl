<?php

$key = str_repeat(chr(255), 16);
$iv = str_repeat(chr(0), 16);

for ($i = 14; $i <=18; $i += 1) {
    $plaintext = str_repeat('@', $i);

    $pad7len = 16 - strlen($plaintext) % 16;
    $pad7 = str_repeat(chr($pad7len), $pad7len);
    $plaintext7 = $plaintext . $pad7;

    $pad0len = $pad7len % 16;
    $pad0 = str_repeat(chr(0), $pad0len);
    $plaintext0 = $plaintext . $pad0;

    $module = mcrypt_module_open('rijndael-128', '', 'cbc', '');
    $init = mcrypt_generic_init($module, $key, $iv);
    $mcrypt = mcrypt_generic($module, $plaintext);
    mcrypt_generic_deinit($module);
    mcrypt_module_close($module);

    $module = mcrypt_module_open('rijndael-128', '', 'cbc', '');
    $init = mcrypt_generic_init($module, $key, $iv);
    $mdecrypt = mdecrypt_generic($module, $mcrypt);
    mcrypt_generic_deinit($module);
    mcrypt_module_close($module);

    $module = mcrypt_module_open('rijndael-128', '', 'cbc', '');
    $init = mcrypt_generic_init($module, $key, $iv);
    $mcrypt0 = mcrypt_generic($module, $plaintext0);
    mcrypt_generic_deinit($module);
    mcrypt_module_close($module);

    $module = mcrypt_module_open('rijndael-128', '', 'cbc', '');
    $init = mcrypt_generic_init($module, $key, $iv);
    $mcrypt7 = mcrypt_generic($module, $plaintext7);
    mcrypt_generic_deinit($module);
    mcrypt_module_close($module);


    $opensslR = openssl_encrypt($plaintext, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
    openssl_error_string(); openssl_error_string(); openssl_error_string();
    $opensslZ = base64_decode(openssl_encrypt($plaintext, 'aes-128-cbc', $key, OPENSSL_ZERO_PADDING, $iv));
    echo openssl_error_string() . "\n";
    $openssl0R = openssl_encrypt($plaintext0, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
    $openssl0Z = base64_decode(openssl_encrypt($plaintext0, 'aes-128-cbc', $key, OPENSSL_ZERO_PADDING, $iv));
    $openssl7R = openssl_encrypt($plaintext7, 'aes-128-cbc', $key, OPENSSL_RAW_DATA, $iv);
    $openssl7Z = base64_decode(openssl_encrypt($plaintext7, 'aes-128-cbc', $key, OPENSSL_ZERO_PADDING, $iv));

    echo '  P: ' . chunk_split(bin2hex($plaintext), 8, ' ') . "\n";
    echo ' P0: ' . chunk_split(bin2hex($plaintext0), 8, ' ') . "\n";
    echo ' P7: ' . chunk_split(bin2hex($plaintext7), 8, ' ') . "\n";

    echo ' OZ: ' . chunk_split(bin2hex($opensslZ), 8, ' ') . "\n";
    echo 'O0Z: ' . chunk_split(bin2hex($openssl0Z), 8, ' ') . "\n";
    echo '  M: ' . chunk_split(bin2hex($mcrypt), 8, ' ') . "\n";
    echo ' M0: ' . chunk_split(bin2hex($mcrypt0), 8, ' ') . "\n";
    echo ' MD: ' . chunk_split(bin2hex($mdecrypt), 8, ' ') . "\n";

    echo ' M7: ' . chunk_split(bin2hex($mcrypt7), 8, ' ') . "\n";
    echo ' OR: ' . chunk_split(bin2hex($opensslR), 8, ' ') . "\n";

//    echo 'O7Z: ' . chunk_split(bin2hex($openssl7Z), 8, ' ') . "\n";
//    echo ' OZ: ' . chunk_split(bin2hex($opensslZ), 8, ' ') . "\n";
//    echo 'O0R: ' . chunk_split(bin2hex($openssl0R), 8, ' ') . "\n";
//    echo 'O7R: ' . chunk_split(bin2hex($openssl7R), 8, ' ') . "\n";

    echo "\n";
}