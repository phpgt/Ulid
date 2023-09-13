<?php
namespace Gt\Ulid;

class Base32 {
	public function __construct(
		private string $skipCharacters = "ilou"
	) {}

	public function fromDec(int $base10):string {
		$skipCharacters = str_split($this->skipCharacters);

		$base10 = abs($base10);
		$converted = base_convert((string)$base10, 10, 32);

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

	public function toDec(string $base32): int {
		$skipCharacters = str_split($this->skipCharacters);
		$base32 = strtoupper($base32);

		for ($i = 0, $len = strlen($base32); $i < $len; $i++) {
			$ord = ord($base32[$i]);
			$adjustment = 0;

			foreach ($skipCharacters as $skip) {
				$skipOrd = ord(strtoupper($skip));
				if ($ord > $skipOrd) {
					$adjustment++;
				}
			}

			$ord -= $adjustment;
			$base32[$i] = chr($ord);
		}

		return intval(base_convert($base32, 32, 10));
	}

}
