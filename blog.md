# Porting PHP code from Mcrypt to OpenSSL

With Mcrypt [long ago abandoned](http://thefsb.tumblr.com/post/110639027905/custodians-of-php-vote-to-keep-a-crypto-lib), 
you might well ask: Can OpenSSL, the only other
 common PHP crypto extension, replace Mcrypt?
The answer is obviously: It depends. I've been studying on what it depends.

## Cipher algorithms

First, compatibility depends on the algorithm. OpenSSL supports:

- Blowfish
- CAST-128, i.e. CAST5
- DES
- Triple DES with 2 or 3 keys
- AES, known as 'rijndael-128' in Mcrypt
- RC4, known as 'arcfour' in Mcrypt

OpenSSL cannot substitute for these Mcrypt cipher algorithms:

- cast-256
- enigma
- gost
- loki97
- rijndael-192
- rijndael-256
- saferplus
- serpent
- twofish
- wake
- xtea

Both claim to support RC2 but Mcrypt's 'rc2' seems to produce incorrect ciphertext relative
to OpenSSL, [C♯](https://www.daniweb.com/web-development/php/threads/368039/rc2-mcrypt-vs-openssl-confusion)
 and test vectors in RFC-2268.


## Block modes

OpenSSL does not implement CTR mode or OFB mode with 8-bit feedback, which is called 'ofb' mode in Mcrypt.
Mcrypt's modes 'cfb', 'ncfb' and 'nofb' correspond to OpenSSL's 'cfb8', 'cfb' and 'ofb' modes respectively.


## Cipher and mode compatibility map

The results of my compatibility testing of all ciphers and modes 
[is in GitHub](https://github.com/tom--/mcrypt2openssl), together with PHP scripts
for preparing and running the tests.

The [cipher and mode compatibility map](https://github.com/tom--/mcrypt2openssl/blob/master/mapping.md) is 
in the same repo.


## Padding

I wrote about the padding behavior of Mcrypt and OpenSSL in detail [in a previous 
article](http://thefsb.tumblr.com/post/110749271235/using-openssl-en-decrypt-in-php-instead-of). Although
they are different, whatever padding you were doing with Mcrypt should be adaptable to
OpenSSL.