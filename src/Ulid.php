<?php
namespace Gt\Ulid;

use Stringable;

class Ulid implements Stringable {
	const TOTAL_LENGTH = 20;
	const TIMESTAMP_LENGTH = 10;

	private float $timestamp;

	public function __construct(float|int $init = null) {
		if(!is_null($init)) {
			$timestamp = $init;
		}
		else {
			$timestamp = microtime(true);
		}

		$this->timestamp = $timestamp;
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
		$t = round($this->timestamp * 1000);
		$base32Timestamp = $this->base32((int)$t);
		return str_pad(
			$base32Timestamp,
			self::TIMESTAMP_LENGTH,
			"0",
			STR_PAD_LEFT
		);
	}

	public function getRandomString():string {
		$string = "";
		for($i = 0; $i < self::TOTAL_LENGTH - self::TIMESTAMP_LENGTH; $i++) {
			$rnd = random_int(0, 31);
			$string .= $this->base32(
				$rnd
			);
		}

		return strtoupper($string);
	}

	private function base32(int $number):string {
		$skipCharacters = ["i", "l", "o", "u"];
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
