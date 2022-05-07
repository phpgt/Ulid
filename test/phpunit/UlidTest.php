<?php
namespace Gt\Ulid\Test;

use Gt\Ulid\Ulid;
use PHPUnit\Framework\TestCase;

class UlidTest extends TestCase {
	public function testGetTimestamp():void {
		$sut = new Ulid();
		$timestamp = microtime(true);
		self::assertSame(round($timestamp), round($sut->getTimestamp()));
	}

	public function testGetTimestamp_setInConstructor():void {
		$timestamp = (float)strtotime("5th April 1988");
		$sut = new Ulid($timestamp);
		self::assertSame($timestamp, $sut->getTimestamp());
	}

	public function testGetHexTimestamp_lexSorting():void {
		$lastHex = null;
		for($year = 1970; $year < 2676; $year++) {
			$timestamp = (float)strtotime("1st January $year");
			$timestamp += rand(-1000, 1000) / 1000;
			$sut = new Ulid($timestamp);
			$hex = $sut->getTimestampString();
			if($lastHex) {
				self::assertGreaterThan($lastHex, $hex, $year);
			}
			$lastHex = $hex;
		}

		$sut = new Ulid(strtotime("5th April 1988"));
		self::assertLessThan($lastHex, $sut->getTimestampString());
	}

	public function testToString_unique():void {
		self::assertNotSame((string)new Ulid(), (string)new Ulid());
	}

	public function testToString_length():void {
		// Testing multiple times in case randomness causes different length strings.
		for($i = 0; $i < 1_000; $i++) {
			$sut = (string)(new Ulid(0));
			self::assertSame(Ulid::TOTAL_LENGTH, strlen($sut));
		}
	}
}
