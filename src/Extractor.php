<?php
namespace Gt\Ulid;

class Extractor {
	private Base32 $base32;

	public function __construct(
		private int $length = Ulid::DEFAULT_TOTAL_LENGTH,
		private int $timestampLength = Ulid::DEFAULT_TIMESTAMP_LENGTH,
		?Base32 $base32 = null,
	) {
		$this->base32 = $base32 ?? new Base32();
	}

	public function extractPrefix(string $initString):?string {
		$underscorePos = strpos($initString, "_");
		if($underscorePos === false) {
			return null;
		}

		return substr($initString, 0, $underscorePos);
	}

	public function extractTimestamp(string $initString):int {
		$underscorePos = $this->positionAfterUnderscore($initString);

		$timestampString = substr(
			$initString,
			$underscorePos,
			$this->timestampLength,
		);
		return $this->base32->toDec($timestampString);
	}

	public function extractRandomString(string $initString):string {
		$underscorePos = $this->positionAfterUnderscore($initString);

		$initString = substr(
			$initString,
			$underscorePos,
		);

		return substr(
			$initString,
			$this->timestampLength,
			$this->length,
		);
	}

	protected function positionAfterUnderscore(string $initString):int {
		$underscorePos = strpos($initString, "_");
		if($underscorePos) {
			$underscorePos += 1;
		}

		return $underscorePos ?: 0;
	}
}
