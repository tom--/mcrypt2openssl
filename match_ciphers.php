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

// algorithm, mode, max-key-size, block-size
// for stream ciphers, iv-size is zero and block size is 1
$mcryptCiphers = [
    ['arcfour', 'stream', 256, 1],
    ['blowfish', 'cbc', 56, 8],
    ['blowfish', 'cfb', 56, 8],
    ['blowfish', 'ctr', 56, 8],
    ['blowfish', 'ecb', 56, 8],
    ['blowfish', 'ncfb', 56, 8],
    ['blowfish', 'nofb', 56, 8],
    ['blowfish', 'ofb', 56, 8],
    ['blowfish-compat', 'cbc', 56, 8],
    ['blowfish-compat', 'cfb', 56, 8],
    ['blowfish-compat', 'ctr', 56, 8],
    ['blowfish-compat', 'ecb', 56, 8],
    ['blowfish-compat', 'ncfb', 56, 8],
    ['blowfish-compat', 'nofb', 56, 8],
    ['blowfish-compat', 'ofb', 56, 8],
    ['cast-128', 'cbc', 16, 8],
    ['cast-128', 'cfb', 16, 8],
    ['cast-128', 'ctr', 16, 8],
    ['cast-128', 'ecb', 16, 8],
    ['cast-128', 'ncfb', 16, 8],
    ['cast-128', 'nofb', 16, 8],
    ['cast-128', 'ofb', 16, 8],
    ['cast-256', 'cbc', 32, 16],
    ['cast-256', 'cfb', 32, 16],
    ['cast-256', 'ctr', 32, 16],
    ['cast-256', 'ecb', 32, 16],
    ['cast-256', 'ncfb', 32, 16],
    ['cast-256', 'nofb', 32, 16],
    ['cast-256', 'ofb', 32, 16],
    ['des', 'cbc', 8, 8],
    ['des', 'cfb', 8, 8],
    ['des', 'ctr', 8, 8],
    ['des', 'ecb', 8, 8],
    ['des', 'ncfb', 8, 8],
    ['des', 'nofb', 8, 8],
    ['des', 'ofb', 8, 8],
    ['enigma', 'stream', 13, 1],
    ['gost', 'cbc', 32, 8],
    ['gost', 'cfb', 32, 8],
    ['gost', 'ctr', 32, 8],
    ['gost', 'ecb', 32, 8],
    ['gost', 'ncfb', 32, 8],
    ['gost', 'nofb', 32, 8],
    ['gost', 'ofb', 32, 8],
    ['loki97', 'cbc', 32, 16],
    ['loki97', 'cfb', 32, 16],
    ['loki97', 'ctr', 32, 16],
    ['loki97', 'ecb', 32, 16],
    ['loki97', 'ncfb', 32, 16],
    ['loki97', 'nofb', 32, 16],
    ['loki97', 'ofb', 32, 16],
    ['rc2', 'cbc',  8, 8],
    ['rc2', 'cfb',  8, 8],
    ['rc2', 'ctr',  8, 8],
    ['rc2', 'ecb',  8, 8],
    ['rc2', 'ncfb', 8, 8],
    ['rc2', 'nofb', 8, 8],
    ['rc2', 'ofb',  8, 8],
    ['rijndael-128', 'cbc', 16, 16],
    ['rijndael-128', 'cfb', 16, 16],
    ['rijndael-128', 'ctr', 16, 16],
    ['rijndael-128', 'ecb', 16, 16],
    ['rijndael-128', 'ncfb', 16, 16],
    ['rijndael-128', 'nofb', 16, 16],
    ['rijndael-128', 'ofb', 16, 16],
    ['rijndael-128', 'cbc', 24, 16],
    ['rijndael-128', 'cfb', 24, 16],
    ['rijndael-128', 'ctr', 24, 16],
    ['rijndael-128', 'ecb', 24, 16],
    ['rijndael-128', 'ncfb', 24, 16],
    ['rijndael-128', 'nofb', 24, 16],
    ['rijndael-128', 'ofb', 24, 16],
    ['rijndael-128', 'cbc', 32, 16],
    ['rijndael-128', 'cfb', 32, 16],
    ['rijndael-128', 'ctr', 32, 16],
    ['rijndael-128', 'ecb', 32, 16],
    ['rijndael-128', 'ncfb', 32, 16],
    ['rijndael-128', 'nofb', 32, 16],
    ['rijndael-128', 'ofb', 32, 16],
    ['rijndael-192', 'cbc', 32, 24],
    ['rijndael-192', 'cfb', 32, 24],
    ['rijndael-192', 'ctr', 32, 24],
    ['rijndael-192', 'ecb', 32, 24],
    ['rijndael-192', 'ncfb', 32, 24],
    ['rijndael-192', 'nofb', 32, 24],
    ['rijndael-192', 'ofb', 32, 24],
    ['rijndael-256', 'cbc', 32, 32],
    ['rijndael-256', 'cfb', 32, 32],
    ['rijndael-256', 'ctr', 32, 32],
    ['rijndael-256', 'ecb', 32, 32],
    ['rijndael-256', 'ncfb', 32, 32],
    ['rijndael-256', 'nofb', 32, 32],
    ['rijndael-256', 'ofb', 32, 32],
    ['saferplus', 'cbc', 32, 16],
    ['saferplus', 'cfb', 32, 16],
    ['saferplus', 'ctr', 32, 16],
    ['saferplus', 'ecb', 32, 16],
    ['saferplus', 'ncfb', 32, 16],
    ['saferplus', 'nofb', 32, 16],
    ['saferplus', 'ofb', 32, 16],
    ['serpent', 'cbc', 32, 16],
    ['serpent', 'cfb', 32, 16],
    ['serpent', 'ctr', 32, 16],
    ['serpent', 'ecb', 32, 16],
    ['serpent', 'ncfb', 32, 16],
    ['serpent', 'nofb', 32, 16],
    ['serpent', 'ofb', 32, 16],
    ['tripledes', 'cbc',  8, 8],
    ['tripledes', 'cfb',  8, 8],
    ['tripledes', 'ctr',  8, 8],
    ['tripledes', 'ecb',  8, 8],
    ['tripledes', 'ncfb', 8, 8],
    ['tripledes', 'nofb', 8, 8],
    ['tripledes', 'ofb',  8, 8],
    ['tripledes', 'cbc',  16, 8],
    ['tripledes', 'cfb',  16, 8],
    ['tripledes', 'ctr',  16, 8],
    ['tripledes', 'ecb',  16, 8],
    ['tripledes', 'ncfb', 16, 8],
    ['tripledes', 'nofb', 16, 8],
    ['tripledes', 'ofb',  16, 8],
    ['tripledes', 'cbc',  24, 8],
    ['tripledes', 'cfb',  24, 8],
    ['tripledes', 'ctr',  24, 8],
    ['tripledes', 'ecb',  24, 8],
    ['tripledes', 'ncfb', 24, 8],
    ['tripledes', 'nofb', 24, 8],
    ['tripledes', 'ofb',  24, 8],
    ['twofish', 'cbc', 32, 16],
    ['twofish', 'cfb', 32, 16],
    ['twofish', 'ctr', 32, 16],
    ['twofish', 'ecb', 32, 16],
    ['twofish', 'ncfb', 32, 16],
    ['twofish', 'nofb', 32, 16],
    ['twofish', 'ofb', 32, 16],
    ['wake', 'stream', 32, 1],
    ['xtea', 'cbc', 16, 8],
    ['xtea', 'cfb', 16, 8],
    ['xtea', 'ctr', 16, 8],
    ['xtea', 'ecb', 16, 8],
    ['xtea', 'ncfb', 16, 8],
    ['xtea', 'nofb', 16, 8],
    ['xtea', 'ofb', 16, 8],
];

foreach ($mcryptCiphers as $i => $spec) {
    list ($cipher, $mode, $keySize, $blockSize) = $spec;

    $module = mcrypt_module_open($cipher, '', $mode, '');

    $padding = $mode === 'cbc' || $mode === 'ecb' ? str_repeat(chr($blockSize), $blockSize) : '';
    $key = substr($ikm, 0, $keySize);
    if ($cipher === 'tripledes' && $keySize === 16) {
        $key .= substr($key, 0, 8);
    }
    $iv = $mode === 'stream' ? '' : substr($iiv, 0, $blockSize);

    $init = mcrypt_generic_init($module, $key, $iv);
    $ciphertext = mcrypt_generic($module, $plaintext . $padding);
    array_unshift($mcryptCiphers[$i], $ciphertext);
    mcrypt_generic_deinit($module);
    mcrypt_module_close($module);
}

// method, max-key-size, iv-size, pad-size, aliases
$opensslCiphers = [
    ['aes-128-cbc', 16, 16, 16, ['aes128']],
    ['aes-128-cfb', 16, 16, 0],
    ['aes-128-cfb1', 16, 16, 0],
    ['aes-128-cfb8', 16, 16, 0],
    ['aes-128-ecb', 16, 0, 16],
    ['aes-128-ofb', 16, 16, 0],
    ['aes-192-cbc', 24, 16, 16, ['aes192']],
    ['aes-192-cfb', 24, 16, 0],
    ['aes-192-cfb1', 24, 16, 0],
    ['aes-192-cfb8', 24, 16, 0],
    ['aes-192-ecb', 24, 0, 16],
    ['aes-192-ofb', 24, 16, 0],
    ['aes-256-cbc', 32, 16, 16, ['aes256']],
    ['aes-256-cfb', 32, 16, 0],
    ['aes-256-cfb1', 32, 16, 0],
    ['aes-256-cfb8', 32, 16, 0],
    ['aes-256-ecb', 32, 0, 16],
    ['aes-256-ofb', 32, 16, 0],
    ['bf-cbc', 56, 8, 8, ['bf', 'blowfish']],
    ['bf-cfb', 56, 8, 0],
    ['bf-ecb', 56, 0, 8],
    ['bf-ofb', 56, 8, 0],
    ['cast5-cbc', 16, 8, 8, ['cast', 'cast-cbc']],
    ['cast5-cfb', 16, 8, 0],
    ['cast5-ecb', 16, 0, 8],
    ['cast5-ofb', 16, 8, 0],
    ['des-cbc', 8, 8, 8, ['des']],
    ['des-cfb', 8, 8, 0],
    ['des-cfb1', 8, 8, 0],
    ['des-cfb8', 8, 8, 0],
    ['des-ecb', 8, 0, 8],
    ['des-ede', 16, 0, 8],
    ['des-ede-cbc', 16, 8, 8],
    ['des-ede-cfb', 16, 8, 0],
    ['des-ede-ofb', 16, 8, 0],
    ['des-ede3', 24, 0, 8],
    ['des-ede3-cbc', 24, 8, 8, ['des3']],
    ['des-ede3-cfb', 24, 8, 0],
    ['des-ede3-cfb1', 24, 8, 0],
    ['des-ede3-cfb8', 24, 8, 0],
    ['des-ede3-ofb', 24, 8, 0],
    ['des-ofb', 8, 8, 0],
    ['desx-cbc', 24, 8, 8, ['desx']],
    ['rc2-40-cbc', 8, 8, 8],
    ['rc2-64-cbc', 8, 8, 8],
    ['rc2-cbc',    8, 8, 0, ['rc2']],
    ['rc2-cfb',    8, 8, 0],
    ['rc2-ecb',    8, 0, 8],
    ['rc2-ofb',    8, 8, 0],
    ['rc4-40',  256, 0, 0, ['rc4']],
    ['rc5-cbc', 255, 8, 8, ['rc5']],
    ['rc5-cfb', 255, 8, 0],
    ['rc5-ecb', 255, 0, 8],
    ['rc5-ofb', 255, 8, 0],
    ['seed-cbc', 16, 16, 16, ['seed']],
    ['seed-cfb', 16, 16, 0],
    ['seed-ecb', 16, 0, 16],
    ['seed-ofb', 16, 16, 0],
];

foreach ($opensslCiphers as $i => $spec) {
    list ($cipher, $keySize, $ivSize) = $spec;
    $key = substr($ikm, 0, $keySize);
    $iv = $ivSize ? substr($iiv, 0, $ivSize) : '';
    $ciphertext = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    array_unshift($opensslCiphers[$i], $ciphertext);
}

foreach ($mcryptCiphers as $mcrypt) {
    $mid = $mcrypt[1] . ':' . $mcrypt[2] . ':' . $mcrypt[3];
    printf("%23s  ", $mid);
    foreach ($opensslCiphers as $openssl) {
        if ($mcrypt[0] === $openssl[0]) {
            $oid = $openssl[1] . ':' . $openssl[2];
            printf("%20s  ", $oid);
        }
    }
    echo "\n";
}

echo "\n\n";

foreach ($opensslCiphers as $openssl) {
    $oid = $openssl[1] . ':' . $openssl[2];
    printf("%20s  ", $oid);
    foreach ($mcryptCiphers as $mcrypt) {
        if ($mcrypt[0] === $openssl[0]) {
            $mid = $mcrypt[1] . ':' . $mcrypt[2] . ':' . $mcrypt[3];
            printf("%20s  ", $mid);
        }
    }
    echo "\n";
}

