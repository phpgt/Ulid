Unique, lexicographically sortable identifiers.
===============================================

Create a `new Ulid()` anywhere in your application, and you have a stringable object that can be used as the primary key in a database. Ulid strings look something like `01G2J6MYN0PGC5Q21W9C`. They are cryptographically pseudo-random, and sort so that newer Ulids compare "greater than" older Ulids.

This solves the problems exposed with working with auto-incrementing integer primary keys, which are predictable and difficult to work with in distributed databases.

***

<a href="https://github.com/PhpGt/Ulid/actions" target="_blank">
	<img src="https://badge.status.php.gt/ulid-build.svg" alt="Build status" />
</a>
<a href="https://scrutinizer-ci.com/g/PhpGt/Ulid" target="_blank">
	<img src="https://badge.status.php.gt/ulid-quality.svg" alt="Code quality" />
</a>
<a href="https://scrutinizer-ci.com/g/PhpGt/Ulid" target="_blank">
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
use Gt\Ulid\Ulid;

$exampleDataSource->create(new Person(
	new Ulid(),
	name: "Cody",
	age: 5,
));
```
