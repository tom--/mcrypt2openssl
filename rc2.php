<?php

$vectors = [
    [
        'EKL' => 63,
        'Key' => '00000000 00000000',
        'Plaintext' => '00000000 00000000',
        'Ciphertext' => 'ebb773f9 93278eff',
    ],
    [
        'EKL' => 64,
        'Key' => 'ffffffff ffffffff',
        'Plaintext' => 'ffffffff ffffffff',
        'Ciphertext' => '278b27e4 2e2f0d49',
    ],
    [
        'EKL' => 64,
        'Key' => '30000000 00000000',
        'Plaintext' => '10000000 00000001',
        'Ciphertext' => '30649edf 9be7d2c2',
    ],
    [
        'EKL' => 64,
        'Key' => '88',
        'Plaintext' => '00000000 00000000',
        'Ciphertext' => '61a8a244 adacccf0',
    ],
    [
        'EKL' => 64,
        'Key' => '88bca90e 90875a',
        'Plaintext' => '00000000 00000000',
        'Ciphertext' => '6ccf4308 974c267f',
    ],
    [
        'EKL' => 64,
        'Key' => '88bca90e 90875a7f 0f79c384 627bafb2',
        'Plaintext' => '00000000 00000000',
        'Ciphertext' => '1a807d27 2bbe5db1',
    ],
    [
        'EKL' => 128,
        'Key' => '88bca90e 90875a7f 0f79c384 627bafb2',
        'Plaintext' => '00000000 00000000',
        'Ciphertext' => '2269552a b0f85ca6',
    ],
    [
        'EKL' => 129,
        'Key' => '88bca90e 90875a7f 0f79c384 627bafb2 16f80a6f 85920584 c42fceb0 be255daf 1e',
        'Plaintext' => '00000000 00000000',
        'Ciphertext' => '5b78d3a4 3dfff1f1',
    ],
];

foreach ($vectors as $vector) {
    foreach ($vector as $key => $value) {
        if (is_string($value)) {
            $vector[$key] = hex2bin(str_replace(' ', '', $value));
        }
    }

    $plaintext = $vector['Plaintext'];
    $expected = $vector['Ciphertext'];

    $t1 = $vector['EKL'];
    $t8 = floor(($t1+7)/8);

    $key = $vector['Key'];



    $module = mcrypt_module_open('rc2', '', 'cbc', '');
    $iv = str_repeat(chr(0), 8);
    $init = mcrypt_generic_init($module, $key, $iv);
    $mcrypt = mcrypt_generic($module, $plaintext . str_repeat(chr(8), 8));
    mcrypt_generic_deinit($module);
    mcrypt_module_close($module);

    $openssl = openssl_encrypt($plaintext, 'rc2-64-cbc', $key, OPENSSL_RAW_DATA, $iv);

    printf("%10s  %2d\n", 'EKL', $t1);
    printf("%10s  %s\n", 'key', bin2hex($key));
    printf("%10s  %s\n", 'plaintext', bin2hex($plaintext));
    printf("%10s  %s\n", 'expected', bin2hex($expected));
    printf("%10s  %s\n", 'openssl', bin2hex($openssl));
    printf("%10s  %s\n", 'mcrypt', bin2hex($mcrypt));
    echo "\n";
}