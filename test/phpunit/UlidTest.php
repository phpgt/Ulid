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
			self::assertSame(Ulid::DEFAULT_TOTAL_LENGTH, strlen($sut));
		}
	}

	public function testToString_containsNoAmbiguousCharacters():void {
		$skipCharacters = ["I", "L", "O", "U"];
		for($i = 0; $i < 1_000; $i++) {
			$sut = new Ulid();
			foreach($skipCharacters as $char) {
				echo $sut, PHP_EOL;
				self::assertStringNotContainsString($char, $sut);
			}
		}
	}

	public function testToString_sameEachTime():void {
		$sut = new Ulid();
		$string1 = (string)$sut;
		$string2 = (string)$sut;
		self::assertSame($string1, $string2);
	}

	public function testConstruct_setLength():void {
		for($i = 0; $i < 10; $i++) {
			$length = rand(10, 100);
			$sut = new Ulid(length: $length);
			self::assertSame($length, strlen($sut));
		}
	}

	public function testConstruct_setTimestampLength():void {
		for($i = 0; $i < 10; $i++) {
			$tLength = rand(5, 10);
			$sut = new Ulid(timestampLength: $tLength);
			echo $sut, PHP_EOL;
			$tString = $sut->getTimestampString();
			self::assertSame($tLength, strlen($tString));
		}
	}
}
