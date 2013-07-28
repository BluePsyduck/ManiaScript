# ManiaScript Library #

## ManiaScript Compressor ##

The ManiaScript Compressor is able to compress the ManiaScript without changing its logic. This can be used to make the
script smaller in order to reduce the traffic.

### Usage ###

To use the ManiaScript Compressor, either run the Composer's vendor/autoload.php, or require the src/autoload.php if you
do not want to run Composer.

```php
require('src/autoload.php');
$compressor = new \ManiaScript\Compressor();
$compressedCode = $compressor->setCode($yourManiaScriptCode)
                             ->compress()
                             ->getCompressedCode();
```

### Note: }}}-Bug of ManiaScript compiler ###

There is currently a bug in the ManiaScript compiler, which lets the compiler think it is in a triple-quoted string as 
soon as it encounters three curly closing brackets in a row. The Compressor works around this bug by forcing a space 
after the second bracket, so don't wonder about this "unneeded" space in the compressed code.

However, the brackets in triple quoted strings are not affected by this workaround.
