# php-rwgen

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Read/write generators in PHP.

PSR-4 compliant library to simplify reading and writing from/to files or any other endpoint within loops.

The benefits of using this library are:

- Read and write from/to many different sources using a common syntax
- Use it easily in foreach loops (readers) or any other kind of loop (writers)
- Unclutter your code by delegating the preparation and cleanup steps

## Install

Via Composer

```bash
$ composer require jotaelesalinas/php-rwgen
```

## Usage

```php
$input = new JLS\Reader\Csv($filename_customers);
$output_csv = new JLS\Writer\Csv($filename_output . '.csv');
$output_kml = new JLS\Writer\Kml($filename_output . '.kml');
foreach ( $input as $customer ) {
    // ...some filtering and transforming...
    $output_csv->send($customer_data);
    $output_kml->send($customer_geolocation_data);
}
```

## Creating your own `Reader`s and `Writer`s

Please see [extending.md](docs/extending.md) for more information.

## Change log

Please see the [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

```bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email DM [@jotaelesalinas](http://twitter.com/jotaelesalinas) instead of using the issue tracker.

## To do

- [x] Add destructor to Writer and `$this->send(null);` on termination
- [ ] Adhere to PSR-2
- [ ] Add to packagist
- [ ] Improve tests code coverage
- [ ] Test using it in another project
- [ ] Add docblocks
- [ ] Update changelog
- [ ] Travis-CI. WTF?
- [ ] Update .gitattributes and .gitignore

## Credits

- [José Luis Salinas][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/jotaelesalinas/php-rwgen.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/jotaelesalinas/php-rwgen/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/jotaelesalinas/php-rwgen.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/jotaelesalinas/php-rwgen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/jotaelesalinas/php-rwgen.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/jotaelesalinas/php-rwgen
[link-travis]: https://travis-ci.org/jotaelesalinas/php-rwgen
[link-scrutinizer]: https://scrutinizer-ci.com/g/jotaelesalinas/php-rwgen/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/jotaelesalinas/php-rwgen
[link-downloads]: https://packagist.org/packages/jotaelesalinas/php-rwgen
[link-author]: https://github.com/jotaelesalinas
[link-contributors]: ../../contributors
