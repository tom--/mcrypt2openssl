# Cipher and mode compatibility map

## Key lengths

Some ciphers accept variable key lengths, e.g. Blowfish keys can be any size from 4 to
56 bytes. Others accept a number of different key sizes, e.g. Twofish keys are 16, 24
or 32 bytes. Others, such as DES, allow only one key size. For those ciphers that allow
different key sizes, I tested the longest allowed.

## Padding

When encrypting in CBC and ECB modes, I padded the Mcrypt input according to PKCS#7 and let OpenSSL 
pad input automatically (also PKCS#7) using the 
[`OPENSSL_RAW_DATA` option](http://thefsb.tumblr.com/post/110749271235/using-openssl-en-decrypt-in-php-instead-of). 



## Complete compatibility test result map

Each row represents a test. The First column specifies a cipher on one
extension and values in second or third columns specify a compatible cipher from
the complementary crypto extension.

The specifications are described by a code string composed as follows

Tag | Description
-- | --
`<algo>`   | Mcrypt "algorithm", see `mcrypt_list_algorithms()`
`<mode>`   | Mcrypt "mode", see `mcrypt_list_modes()`
`<method>` | OpenSSL "cipher method", see `openssl_get_cipher_methods()`
`<klen>`   | key length used in compatibility test.

For example, the second row in the map means that

- `mcrypt_module_open('blowfish', '', 'cbc', '')` with a 56-byte key

tested as compatible with (i.e. produced the same ciphertext as)

- `openssl_encrypt($plaintext, 'bf-cbc', ...)` with a 56-byte key.

Two rows down we see that no compatible OpenSSL encryption was found for Mcrypt's Blowfish in CTR mode.
(OpenSSL doesn't do CTR at all!)

```
                 Mcrypt               OpenSSL
                 ======               =======

   <algo>:<mode>:<klen>       <method>:<klen>

     arcfour:stream:256            rc4-40:256
        blowfish:cbc:56             bf-cbc:56
        blowfish:cfb:56
        blowfish:ctr:56
        blowfish:ecb:56             bf-ecb:56
       blowfish:ncfb:56             bf-cfb:56
       blowfish:nofb:56             bf-ofb:56
        blowfish:ofb:56
    blowfish-compat:*:*  [1]
        cast-128:cbc:16          cast5-cbc:16
        cast-128:cfb:16
        cast-128:ctr:16
        cast-128:ecb:16          cast5-ecb:16
       cast-128:ncfb:16          cast5-cfb:16
       cast-128:nofb:16          cast5-ofb:16
        cast-128:ofb:16
        cast-256:cbc:32
        cast-256:cfb:32
        cast-256:ctr:32
        cast-256:ecb:32
       cast-256:ncfb:32
       cast-256:nofb:32
        cast-256:ofb:32
              des:cbc:8             des-cbc:8
              des:cfb:8            des-cfb8:8
              des:ctr:8
              des:ecb:8             des-ecb:8
             des:ncfb:8             des-cfb:8
             des:nofb:8             des-ofb:8
              des:ofb:8
       enigma:stream:13
            gost:cbc:32
            gost:cfb:32
            gost:ctr:32
            gost:ecb:32
           gost:ncfb:32
           gost:nofb:32
            gost:ofb:32
          loki97:cbc:32
          loki97:cfb:32
          loki97:ctr:32
          loki97:ecb:32
         loki97:ncfb:32
         loki97:nofb:32
          loki97:ofb:32
                rc2:*:*  [3]
    rijndael-128:cbc:16        aes-128-cbc:16
    rijndael-128:cfb:16       aes-128-cfb8:16
    rijndael-128:ctr:16
    rijndael-128:ecb:16        aes-128-ecb:16
   rijndael-128:ncfb:16        aes-128-cfb:16
   rijndael-128:nofb:16        aes-128-ofb:16
    rijndael-128:ofb:16
    rijndael-128:cbc:24        aes-192-cbc:24
    rijndael-128:cfb:24       aes-192-cfb8:24
    rijndael-128:ctr:24
    rijndael-128:ecb:24        aes-192-ecb:24
   rijndael-128:ncfb:24        aes-192-cfb:24
   rijndael-128:nofb:24        aes-192-ofb:24
    rijndael-128:ofb:24
    rijndael-128:cbc:32        aes-256-cbc:32
    rijndael-128:cfb:32       aes-256-cfb8:32
    rijndael-128:ctr:32
    rijndael-128:ecb:32        aes-256-ecb:32
   rijndael-128:ncfb:32        aes-256-cfb:32
   rijndael-128:nofb:32        aes-256-ofb:32
    rijndael-128:ofb:32
    rijndael-192:cbc:32
    rijndael-192:cfb:32
    rijndael-192:ctr:32
    rijndael-192:ecb:32
   rijndael-192:ncfb:32
   rijndael-192:nofb:32
    rijndael-192:ofb:32
    rijndael-256:cbc:32
    rijndael-256:cfb:32
    rijndael-256:ctr:32
    rijndael-256:ecb:32
   rijndael-256:ncfb:32
   rijndael-256:nofb:32
    rijndael-256:ofb:32
       saferplus:cbc:32
       saferplus:cfb:32
       saferplus:ctr:32
       saferplus:ecb:32
      saferplus:ncfb:32
      saferplus:nofb:32
       saferplus:ofb:32
         serpent:cbc:32
         serpent:cfb:32
         serpent:ctr:32
         serpent:ecb:32
        serpent:ncfb:32
        serpent:nofb:32
         serpent:ofb:32
        tripledes:cbc:8             des-cbc:8
        tripledes:cfb:8            des-cfb8:8
        tripledes:ctr:8
        tripledes:ecb:8             des-ecb:8
       tripledes:ncfb:8             des-cfb:8
       tripledes:nofb:8             des-ofb:8
        tripledes:ofb:8
       tripledes:cbc:16        des-ede-cbc:16  [2]
       tripledes:cfb:16  [2]
       tripledes:ctr:16  [2]
       tripledes:ecb:16            des-ede:16  [2]
      tripledes:ncfb:16        des-ede-cfb:16  [2]
      tripledes:nofb:16        des-ede-ofb:16  [2]
       tripledes:ofb:16  [2]
       tripledes:cbc:24       des-ede3-cbc:24
       tripledes:cfb:24      des-ede3-cfb8:24
       tripledes:ctr:24
       tripledes:ecb:24           des-ede3:24
      tripledes:ncfb:24       des-ede3-cfb:24
      tripledes:nofb:24       des-ede3-ofb:24
       tripledes:ofb:24
         twofish:cbc:32
         twofish:cfb:32
         twofish:ctr:32
         twofish:ecb:32
        twofish:ncfb:32
        twofish:nofb:32
         twofish:ofb:32
         wake:stream:32
            xtea:cbc:16
            xtea:cfb:16
            xtea:ctr:16
            xtea:ecb:16
           xtea:ncfb:16
           xtea:nofb:16
            xtea:ofb:16


             OpenSSL                Mcrypt
             =======                ======

     <method>:<klen>  <algo>:<mode>:<klen>

      aes-128-cbc:16   rijndael-128:cbc:16
      aes-128-cfb:16  rijndael-128:ncfb:16
     aes-128-cfb1:16
     aes-128-cfb8:16   rijndael-128:cfb:16
      aes-128-ecb:16   rijndael-128:ecb:16
      aes-128-ofb:16  rijndael-128:nofb:16
      aes-192-cbc:24   rijndael-128:cbc:24
      aes-192-cfb:24  rijndael-128:ncfb:24
     aes-192-cfb1:24
     aes-192-cfb8:24   rijndael-128:cfb:24
      aes-192-ecb:24   rijndael-128:ecb:24
      aes-192-ofb:24  rijndael-128:nofb:24
      aes-256-cbc:32   rijndael-128:cbc:32
      aes-256-cfb:32  rijndael-128:ncfb:32
     aes-256-cfb1:32
     aes-256-cfb8:32   rijndael-128:cfb:32
      aes-256-ecb:32   rijndael-128:ecb:32
      aes-256-ofb:32  rijndael-128:nofb:32
           bf-cbc:56       blowfish:cbc:56
           bf-cfb:56      blowfish:ncfb:56
           bf-ecb:56       blowfish:ecb:56
           bf-ofb:56      blowfish:nofb:56
        cast5-cbc:16       cast-128:cbc:16
        cast5-cfb:16      cast-128:ncfb:16
        cast5-ecb:16       cast-128:ecb:16
        cast5-ofb:16      cast-128:nofb:16
           des-cbc:8             des:cbc:8       tripledes:cbc:8
           des-cfb:8            des:ncfb:8      tripledes:ncfb:8
          des-cfb1:8
          des-cfb8:8             des:cfb:8       tripledes:cfb:8
           des-ecb:8             des:ecb:8       tripledes:ecb:8
          des-ede:16      tripledes:ecb:16  [2]
      des-ede-cbc:16      tripledes:cbc:16  [2]
      des-ede-cfb:16     tripledes:ncfb:16  [2]
      des-ede-ofb:16     tripledes:nofb:16  [2]
         des-ede3:24      tripledes:ecb:24
     des-ede3-cbc:24      tripledes:cbc:24
     des-ede3-cfb:24     tripledes:ncfb:24
    des-ede3-cfb1:24
    des-ede3-cfb8:24      tripledes:cfb:24
     des-ede3-ofb:24     tripledes:nofb:24
           des-ofb:8            des:nofb:8      tripledes:nofb:8
         desx-cbc:24
        rc2-40-cbc:8  [3]
        rc2-64-cbc:8  [3]
             rc2-*:8  [3]
          rc4-40:256    arcfour:stream:256
         rc5-cbc:255
         rc5-cfb:255
         rc5-ecb:255
         rc5-ofb:255
         seed-cbc:16
         seed-cfb:16
         seed-ecb:16
         seed-ofb:16
```

### Notes

[1] Mcrypt 'blowfish-compat' is [Blowfish in little-endian byte order](http://stackoverflow.com/a/11423057)

[2] For 2-key tripple-DES, in OpenSSL use 'des-ede' with the 16-byte input input key K1 + K2, i.e. the concatenation
of the two 8-byte keys K1 and K2. In Mcrypt use 'tripledes' with the 24-byte input key K1 + K2 + K1.

[3] I couldn't get Mcrypt's RC2 cypher to agree with OpenSSL's. OpenSSL's output corresponds
in some ways with the test vectors in RFC-2268. I don't plan to spend to spend more time
investigating. The `rc2.php` script may be useful if you care about RC2.
