Unique, lexicographically sortable identifiers.
===============================================

Create a `new Ulid()` anywhere in your application, and you have a stringable object that can be used as the primary key in a database. Ulid strings look something like `01G2J6MYN0PGC5Q21W9C` or can be prefixed with a type like `CUSTOMER_01G2J6MYN0PGC5Q21W9C`. They are cryptographically pseudo-random, and sort so that newer Ulids compare "greater than" older Ulids.

This solves the problems exposed with working with auto-incrementing integer primary keys, which are predictable and difficult to work with in distributed databases.

***

<a href="https://github.com/PhpGt/Ulid/actions" target="_blank">
	<img src="https://badge.status.php.gt/ulid-build.svg" alt="Build status" />
</a>
<a href="https://app.codacy.com/gh/PhpGt/Ulid" target="_blank">
	<img src="https://badge.status.php.gt/ulid-quality.svg" alt="Code quality" />
</a>
<a href="https://app.codecov.io/gh/PhpGt/Ulid" target="_blank">
	<img src="https://badge.status.php.gt/ulid-coverage.svg" alt="Code coverage" />
</a>
<a href="https://packagist.org/packages/PhpGt/Ulid" target="_blank">
	<img src="https://badge.status.php.gt/ulid-version.svg" alt="Current version" />
</a>
<a href="http://www.php.gt/ulid" target="_blank">
	<img src="https://badge.status.php.gt/ulid-docs.svg" alt="PHP.Gt/Ulid documentation" />
</a>

## Example usage:

```php
use GT\Ulid\Ulid;

$exampleDataSource->create(new Person(
	new Ulid("pet"),
	name: "Cody",
	age: 5,
));
```

## Explicit timestamps

```php
$ulid = new Ulid('pet', timestampSeconds: 1712345678.123);
$seconds = $ulid->getTimestampSeconds(); // 1712345678.123
$milliseconds = $ulid->getTimestampMilliseconds(); // 1712345678123
```

`timestampSeconds` accepts integer or fractional Unix seconds and rounds to the
nearest millisecond. Supply it on its own when choosing an explicit timestamp.

The second constructor argument, `timestamp`, still accepts Unix milliseconds
but is deprecated. To migrate, use `timestampSeconds: $milliseconds / 1000`.
Replace calls to the deprecated `getTimestamp()` with
`getTimestampMilliseconds()` to keep the same integer value.

# Proudly sponsored by

[JetBrains Open Source sponsorship program](https://www.jetbrains.com/community/opensource/)

[![JetBrains logo.](https://resources.jetbrains.com/storage/products/company/brand/logos/jetbrains.svg)](https://www.jetbrains.com/community/opensource/)
