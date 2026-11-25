<?php
namespace GT\Ulid;

use DateTime;
use InvalidArgumentException;
use Stringable;

class Ulid implements Stringable {
	const DEFAULT_TOTAL_LENGTH = 20;
	const DEFAULT_TIMESTAMP_LENGTH = 10;

	private int $timestamp;
	private string $randomString;
	private Base32 $base32;

	/**
	 * @param int|null $timestamp Deprecated. Use $timestampSeconds.
	 *     The legacy argument is in Unix milliseconds.
	 * @param int|float|null $timestampSeconds Unix seconds, rounded to ms.
	 */
	public function __construct(
		private ?string $prefix = null,
		?int $timestamp = null,
		private int $length = self::DEFAULT_TOTAL_LENGTH,
		private int $timestampLength = self::DEFAULT_TIMESTAMP_LENGTH,
		?string $init = null,
		int|float|null $timestampSeconds = null,
	) {
		$this->base32 = new Base32();

		if($timestampSeconds !== null) {
			if($timestamp !== null || $init) {
				throw new InvalidArgumentException(
					"timestampSeconds cannot be used with timestamp or an existing ULID"
				);
			}

			$timestamp = $this->secondsToMilliseconds($timestampSeconds);
		}

		if($init) {
			$extractor = new Extractor();
			$this->prefix = $extractor->extractPrefix($init);
			$this->timestamp = $extractor->extractTimestamp($init);
			$this->randomString = $extractor->extractRandomString($init);
			return;
		}

		if(is_null($timestamp)) {
			$timestamp = (int)round(microtime(true) * 1000);
		}

		$this->timestamp = $timestamp;

		$this->randomString = "";
		for($i = 0; $i < $this->length - $this->timestampLength; $i++) {
			$rnd = random_int(0, 31);
			$this->randomString .= $this->base32->fromDec($rnd);
		}
	}

	public function __toString():string {
		$timestampString = $this->getTimestampString();
		$randomString = $this->getRandomString();

		$string = implode("", [
			$timestampString,
			$randomString,
		]);

		if($prefix = $this->getPrefix()) {
			$string = implode("_", [
				$prefix,
				$string,
			]);
		}

		return $string;
	}

	public function getPrefix():?string {
		if(!$this->prefix) {
			return null;
		}

		return strtoupper($this->prefix);
	}

	/** @deprecated Use getTimestampMilliseconds() instead. */
	public function getTimestamp():int {
		return $this->timestamp;
	}

	public function getTimestampMilliseconds():int {
		return $this->getTimestamp();
	}

	public function getTimestampSeconds():float {
		return $this->getTimestampMilliseconds() / 1000;
	}

	public function getTimestampString():string {
		$base32Timestamp = $this->base32->fromDec($this->timestamp);
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

	public function getDateTime():DateTime {
		$timestamp = $this->getTimestamp() / 1000;
		return new DateTime("@" . $timestamp);
	}

	private function secondsToMilliseconds(int|float $seconds):int {
		$milliseconds = $seconds * 1000;
		if(is_float($milliseconds)) {
			$milliseconds = round($milliseconds);
		}

		if(!is_finite($milliseconds)
		|| $seconds < 0
		|| $milliseconds >= PHP_INT_MAX + 1) {
			throw new InvalidArgumentException("Timestamp out of range");
		}

		$milliseconds = (int)$milliseconds;
		if(strlen($this->base32->fromDec($milliseconds)) > $this->timestampLength) {
			throw new InvalidArgumentException("Timestamp exceeds timestampLength");
		}

		return $milliseconds;
	}
}
