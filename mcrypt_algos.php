<?php

$lipsum
    = "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do
eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim
veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum
dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident,
sunt in culpa qui officia deserunt mollit anim id est laborum.";
// Take a plaintext that's an integer number times 16 bytes long.

$plaintext = mb_substr($lipsum, 0, 256, '8bit');
$ikm = '';
$iiv = '';
for ($i = 0; $i <= 255; $i += 1) {
    $ikm .= chr($i);
    $iiv .= chr(($i + 128) % 256);
}

$ciphers = mcrypt_list_algorithms();
sort($ciphers);
$modes = mcrypt_list_modes();
sort($modes);

foreach ($ciphers as $cipher) {
    foreach ($modes as $mode) {
        $status = 'Yes';
        $keySize = '';
        $blockSize = '';
        $ivSize = '';

        $module = @mcrypt_module_open($cipher, '', $mode, '');
        if ($module === false || !is_resource($module)) {
            $status = 'No';
            continue;
        } else {
            $keySize = mcrypt_get_key_size($cipher, $mode);
            $blockSize = mcrypt_get_block_size($cipher, $mode);
            $ivSize = mcrypt_get_iv_size($cipher, $mode);

            $init = mcrypt_generic_init($module, substr($ikm, 0, $keySize), substr($iiv, 0, $ivSize));
            if ($init !== 0) {
                $status = 'Init:' . $init;
            } else {
                $ciphertext = mcrypt_generic($module, $plaintext);
                if (mb_strlen($ciphertext, '8bit') < mb_strlen($plaintext, '8bit')) {
                    $status = 'CryptFail';
                }
                mcrypt_generic_deinit($module);
            }
            mcrypt_module_close($module);
        }

        printf("%16s  %6s  %3s  %2s  %2s  %4s\n", $cipher, $mode, $keySize, $blockSize, $ivSize, $status);
    }
}

