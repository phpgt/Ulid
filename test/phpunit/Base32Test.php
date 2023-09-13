<?php
namespace Gt\Ulid\Test;

use Gt\Ulid\Base32;
use PHPUnit\Framework\TestCase;

class Base32Test extends TestCase {
	public function testFromDec():void {
		$sut = new Base32();
		$b32 = $sut->fromDec(105);
		self::assertSame(105, $sut->toDec($b32));
	}

	public function testFromDec_timestamp():void {
		$timestamp = strtotime("5th April 1988 17:24:00");
		$sut = new Base32();
		$b32 = $sut->fromDec($timestamp);
		self::assertSame($timestamp, $sut->toDec($b32));
	}

	public function testFromDec_timestampWayInTheFuture():void {
		$timestamp = strtotime("31st December 9999 23:59:59");
		$sut = new Base32();
		$b32 = $sut->fromDec($timestamp);
		self::assertSame($timestamp, $sut->toDec($b32));
	}

	public function testFromDec_timestampWayInThePast():void {
		$timestamp = strtotime("1st January -5000 13:05:00");
		$sut = new Base32();
		$b32 = $sut->fromDec($timestamp);
		self::assertSame($timestamp, $sut->toDec($b32));
	}
}
