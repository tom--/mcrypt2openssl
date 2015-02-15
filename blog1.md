
Everyone should have stopped using the PHP Mcrypt extension for new work already and should
be planning to move their existing apps off it too because [libmcrypt
was abandoned in 2003](http://thefsb.tumblr.com/post/110639027905/custodians-of-php-vote-to-keep-a-crypto-lib) and is unmaintained.

The best alternative in my view is OpenSSL. It's taken a lot of
heat in recent years but I think it's a good choice for
symmetric block encryption. (I might explain why I think so another day.)

Documentation for `openssl_encrypt()` and `openssl_decrypt()` in
PHP is a bit lacking. This article aims to fill in some blanks.
Here's the signature for both.


    string openssl_encrypt ( string $data , string $method , string $password [, int $options = 0 [, string $iv = "" ]] )
    string openssl_decrypt ( string $data , string $method , string $password [, int $options = 0 [, string $iv = "" ]] )


We will always use an initialization vector and it turns out the `$options`
makes a big difference so we can simplify:

    string openssl_encrypt ( string $data , string $method , string $password , int $options, string $iv )
    string openssl_decrypt ( string $data , string $method , string $password , int $options, string $iv )

In which `$options` will be either `OPENSSL_RAW_DATA` or `OPENSSL_ZERO_PADDING`.



## The `$password` argument is the encryption key

The `$password` argument name is very misleading. It's not a password, it's an
encryption key. The `openssl enc` command line utility will accept either a
password or key will do key derivation and salting if you want.
But  these PHP functions expect `$password`  to be an encryption key.

They also require the key to be properly prepared for the cipher algorithm you
use. I only use AES-128 which has a key size of 16 bytes.
If you pass `openssl_en/decrypt()` a key (in the `$password` argument) longer than the cipher's
intrinsic key size, the excess is discarded. If you pass it a key shorter
than expected, it is padded with zero, i.e. `\x00` bytes.

So you need to prepare your keys carefully. If the user gives you a password,
use something like [PBKDF2](https://en.wikipedia.org/wiki/PBKDF2) with a
unique salt. If you have an input encryption
key, you can derive a key for `openssl_en/decrypt()` using [HKDF](https://tools.ietf.org/html/rfc5869).

So lets simplify again:

    string openssl_encrypt ( string $data , string $method , string $key , int $options, string $iv )
    string openssl_decrypt ( string $data , string $method , string $key , int $options, string $iv )

If you are generating a key, as opposed to deriving one from a user-input
key or password, you should use a binary string of random bytes drawn from a
[cryptographically-secure pseudo-random number
generator](http://en.wikipedia.org/wiki/Cryptographically_secure_pseudorandom_number_generator)
or CSPRNG. (I will perhaps blog on how to obtain such strings in PHP one day, it's
actually [not as simple as just calling `openssl_random_pseudo_bytes()`](http://sockpuppet.org/blog/2014/02/25/safely-generate-random-numbers/).)


## Initialization vector

Requirements for the initialization vector `$iv` are similar to those for `$password`.
It should be a binary string of the same length as the cipher's block size. Excess bytes
are discarded and a too-short  `$iv` is padded to the block size with zero bytes.

Initialization vectors should be a binary string of random bytes from a CSPRNG.
Do not reuse IVs.

## The `$method` is the cipher spec

The allowed values for `$method` are listed by the `openssl_get_cipher_methods()` function
on your PHP platform and spelled out a bit more in the
[openssl enc docs](https://www.openssl.org/docs/apps/enc.html).

I'm not going to get into a discussion of the relative merits of these. The only one I use is AES in CBC mode. Excepting RC5 (patented by RAS and not widely used), all the other symmetric block ciphers that OpenSSL provides have 64-bit blocks or smaller.

I always use AES-128 which has a 16-byte key.
So that means I'll be choosing 'AES-128-CBC'. I think you can safely choose
'AES-192-CBC' or 'AES-256-CBC' but I won't get drawn into an argument over
which is best.

In any case, `$method` must be one of the strings `openssl_get_cipher_methods()`
returns and you must use an appropriate lengths of `$iv` and of `$key` for that cipher.


## Encoding, padding and  `OPENSSL_RAW_DATA` vs. `OPENSSL_ZERO_PADDING`

In Mcrypt, the input and output encoding on encryption and decryption are raw binary string.
Paintext and ciphertext are both binary strings.

Mcrypt automatically adds 
[zero byte padding](https://en.wikipedia.org/wiki/Padding_%28cryptography%29#Zero_padding)
to the plaintext before encrypting with a block cipher in CBC or ECB mode
and returns the zero byte-padded plaintext after decryption (i.e. Mcrypt does
not strip padding after decryption). 

OpenSSL has two modes:
 
- `$options = OPENSSL_RAW_DATA`

     - The input and output encoding of both `openssl_encrypt()` and `openssl_decrypt()`
     is binary string, i.e. the plaintext and cipertext are both binary strings.
     - `openssl_encrypt()` adds 
    [PKCS7 padding](https://en.wikipedia.org/wiki/Padding_%28cryptography%29#PKCS7)
    to the plaintext before encrypting with a block cipher in CBC or ECB mode.
     - `openssl_decrypt()` strips the padding after decryption.

- If  `$options = OPENSSL_ZERO_PADDING` then 
    
    - The input encoding to `openssl_encrypt()` and output encodng from `openssl_decrypt()` 
    is raw binary string, i.e. the plaintext is a raw binary string.
    - The output encoding fron `openssl_encrypt()` and input encodng to `openssl_decrypt()` 
    is base64, i.e. the ciphertext is base64 encoded.
    - The size of the input to `openssl_encrypt()` must be an integer multiple of the
    block size, otherwise an error code is returned.
    - No padding is added by `openssl_encrypt()` or removed by `openssl_decrypt()`


Either can be made to work but I prefer `OPENSSL_RAW_DATA`. If I need a specific
encoding for the ciphertext then I would rather do that myself. And PKCS7 would be
my choice of padding anyway, in fact it was what I used with Mcrypt.


## Summary

Having figured out all these undocumented features of these two functions, it
turns out that the only way I intend to use OpenSSL for symmetric encryption is like this:

    $ciphertext = openssl_encrypt($plaintext,  'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);
    $plaintext  = openssl_decrypt($ciphertext, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $iv);

In which:

- `$plaintext` is any string, any length.
- `$key` is a cryptographic key in the form of a binary string 16 bytes long (because AES-128 has a key size of 16 bytes)
- `$iv` is a crypto-secure random binary string 16 bytes long (because AES has block size of 16 bytes) that I will use only once
- `$ciphertext` is a binary string between 1 and 16 bytes longer than `$plaintext` (because of PKCS7 padding to the 16-byte block size)

And remembering that a cryptographic keys must be either properly derived from
the input key material or password or be generated by a CSPRNG.


## A final word

Remember that if you need to encrypt data then you almost certainly also need to
authenticate it too. For this purpose you can use an HMAC signature. Use one of
the SHA-2 algorithms.

Remember also to [sign last and authenticate
first](http://www.thoughtcrime.org/blog/the-cryptographic-doom-principle/), i.e. the HMAC
needs to covers the entire message containing the encrypted data.





