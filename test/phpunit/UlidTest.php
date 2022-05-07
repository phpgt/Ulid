<?php
namespace Gt\Ulid\Test;

use Gt\Ulid\Ulid;
use PHPUnit\Framework\TestCase;

class UlidTest extends TestCase {
	public function testUnique():void {
		self::assertNotSame((string)new Ulid(), (string)new Ulid());
	}
}
