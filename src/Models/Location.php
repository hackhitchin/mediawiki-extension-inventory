<?php
namespace MediaWiki\Extension\HackHitchinInventory\Models;

class Location {
    private ?int $id;
    private string $key;
    private string $description;

    public static function recreate(int $id, string $key, string $description) {
        return new self($id, $key, $description);
    }

    public static function create(string $key, string $description) {
        return new self(null, $key, $description);
    }

	private function __construct(
        ?int $id,
        string $key,
        string $description
    ) {
        $keyValidation = self::validateKey($key);
        if ($keyValidation !== true) {
            // TODO: better error handling
            throw new \Error($keyValidation);
        }
        $this->id = $id;
        $this->key = $key;
        $this->description = $description;
    }

    public function getId() : ?int {
        return $this->id;
    }

    public function getKey() : string {
        return $this->key;
    }

    public function getDescription() : string {
        return $this->description;
    }

	public static function validateKey($keyInput, $allInputs = []) {
		if(!preg_match("/^[0-9A-Za-z-_]+$/", $keyInput)) {
			return "Key is numbers, letters, underscore (_) and hyphen (-) only.";
		}

		return true;
	}
}