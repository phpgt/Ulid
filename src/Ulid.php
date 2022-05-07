<?php
namespace Gt\Ulid;

use Stringable;

class Ulid implements Stringable {
	private int $timestamp;
	private string $randomness;

	public function __construct() {
		$this->timestamp = (int) (microtime(true) * 10_000);
		$this->randomness = random_bytes(16);
	}

	public function __toString():string {
		return implode("-", [
			dechex($this->timestamp),
			bin2hex($this->randomness),
		]);
	}
}
