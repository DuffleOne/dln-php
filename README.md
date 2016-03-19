# dln

Generates and validates driving license numbers (DLNs). Presently, only licenses
issued by the UK DVLA are supported. Please open an issue if you'd like to see
support added for another type of license - I'd be keen to work with you to
enable this.

```php
<?php

use Duffleman\DLN\DLN;

require_once('vendor\autoload.php');

$user1 = [
    'familyName'   => 'Morgan',
    'personalName' => 'Sarah Meredyth',
    'birthDate'    => '1964-07-05',
    'sex'          => 'F',
];

$user2 = [
    'familyName'   => 'Gardner',
    'personalName' => 'Charles',
    'birthDate'    => '1969-05-10',
    'sex'          => 'M',
];

$finalChars = '9IJ';
$completeDLN = 'MORGA657054SM9IJ';

dump(DLN::generate($user1)); // => MORGA657054SM
dump(DLN::generate($user1, $finalChars)); // => MORGA657054SM9IJ
dump(DLN::generate($user2)); // => GARDN605109C9
dump(DLN::validate($completeDLN)); // => true
dump(DLN::validate($completeDLN, $user1)); // => true
dump(DLN::validate($completeDLN, $user2)); // => false
```

## Installation

```bash
$ composer require duffleman/dln
```

## API

### `DLN::generate(array $userDetails[, $finalCharacters])`

Generates a DLN based on the given user details, optionally adding the final
characters to the end (as these characters cannot be generated accurately).

If the final characters are not provided, the output will not be a complete DLN
and will fail validation in the `DLN.validate` function.

Returns a string either 13 or 16 characters in length, depending on whether the
final characters are provided.

Throws if the user details or final characters are invalid.

### `DLN::validate($dln[, array $userDetails])`

Verifies that a complete DLN is valid, optionally checking that the first 13
characters of the DLN are correct for the given user details.

Returns a boolean indicating whether the DLN is valid.

Throws if the user details are invalid.

## Notes

The user details information, when provided, must be exactly as specified below:

```php
$user1 = [
    'familyName'   => 'Morgan', // required, string - can contain spaces
    'personalName' => 'Sarah Meredyth', // optional, string - first names, including middle names
    'birthDate'    => '1964-07-05', // required, string - YYYY-MM-DD date
    'sex'          => 'F', // required, string, (M|F) - whatever the person has stated to the DVLA
];
```

DLNs follow a relatively strict format, and are exactly 16 characters in length.
Only the first 13 characters of a DLN can be accurately generated - the
remaining 3 characters cannot and must be provided separately.

The final characters also follow a strict format, and are exactly 3 characters
in length. These are typically appended to the generated part of the DLN in
order to form a complete & valid DLN.

## Support

Please open an issue on this repository.

## Authors

- George Miller <github@duffleman.co.uk>
- James Billingham <james@jamesbillingham.com>

## License

MIT licensed - see [LICENSE](LICENSE) file