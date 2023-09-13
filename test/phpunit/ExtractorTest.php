<?php
namespace Gt\Ulid\Test;

use Gt\Ulid\Extractor;
use Gt\Ulid\Ulid;
use PHPUnit\Framework\TestCase;

class ExtractorTest extends TestCase {
	public function testExtractTimestamp():void {
		$currentTimestamp = 1694626973857;
		$ulid = "01HA7T72510000000000";

		$sut = new Extractor();
		$timestamp = $sut->extractTimestamp($ulid);
		self::assertSame($currentTimestamp, $timestamp);
	}

	public function testExtractTimestamp_specificKnownDate():void {
		$knownTimestamp = strtotime("5th April 1988 17:24:00");

		$ulid = self::createMock(Ulid::class);
		$ulid->method("__toString")
			->willReturn("0000H5J61G" . "DYSS02F0S3");

		$sut = new Extractor();
		$timestamp = $sut->extractTimestamp($ulid);
		self::assertSame($knownTimestamp, $timestamp);
	}

	public function testExtractPrefix():void {
		$ulid = "example_1234567890";
		$sut = new Extractor();
		self::assertSame("example", $sut->extractPrefix($ulid));
	}

	public function testExtractPrefix_noPrefix():void {
		$ulid = "1234567890";
		$sut = new Extractor();
		self::assertNull($sut->extractPrefix($ulid));
	}

	public function testExtractRandomString():void {
		$random = "AABBCCDDEE";
		$timestamp = "0000000000";
		$ulid = "example_" . $timestamp . $random;
		$sut = new Extractor();
		self::assertSame($random, $sut->extractRandomString($ulid));
	}
}
