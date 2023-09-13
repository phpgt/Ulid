<?php
namespace Gt\Ulid;

use Stringable;

class Ulid implements Stringable {
	const DEFAULT_TOTAL_LENGTH = 20;
	const DEFAULT_TIMESTAMP_LENGTH = 10;

	private float $timestamp;
	private string $randomString;

	public function __construct(
		float|int $init = null,
		private int $length = self::DEFAULT_TOTAL_LENGTH,
		private int $timestampLength = self::DEFAULT_TIMESTAMP_LENGTH,
	) {
		if(!is_null($init)) {
			$timestamp = $init;
		}
		else {
			$timestamp = microtime(true);
		}

		$this->timestamp = $timestamp;

		$this->randomString = "";
		for($i = 0; $i < $this->length - $this->timestampLength; $i++) {
			$rnd = random_int(0, 31);
			$this->randomString .= $this->base32(
				$rnd
			);
		}
	}

	public function __toString():string {
		$timestampString = $this->getTimestampString();
		$randomString = $this->getRandomString();
		return implode("", [
			$timestampString,
			$randomString,
		]);
	}

	public function getTimestamp():float {
		return $this->timestamp;
	}

	public function getTimestampString():string {
		$timestamp = round($this->timestamp * 1000);
		$base32Timestamp = $this->base32((int)$timestamp);
		return substr(
			str_pad(
				$base32Timestamp,
				$this->timestampLength,
				"0",
				STR_PAD_LEFT
			),
			0,
			$this->timestampLength,
		);
	}

	public function getRandomString():string {
		return $this->randomString;
	}

	private function base32(int $number):string {
		$skipCharacters = ["i", "l", "o", "u"];
		if($number < 0) {
			$number = -$number;
		}
		$converted = base_convert((string)$number, 10, 32);

		for($i = 0, $len = strlen($converted); $i < $len; $i++) {
			$ord = ord($converted[$i]);
			foreach($skipCharacters as $skip) {
				$skipOrd = ord($skip);
				if($ord >= $skipOrd) {
					$ord++;
				}
				$converted[$i] = chr($ord);
			}
		}

		return strtoupper($converted);
	}
}
