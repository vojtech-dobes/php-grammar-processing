<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class Token
{

	/** @var callable(int): Location $getLocation */
	private $getLocation;

	public Location $location {

		get {
			return ($this->getLocation)($this->tokenOffset);
		}

	}



	/**
	 * @param callable(int): Location $getLocation
	 */
	public function __construct(
		public readonly string $type,
		public readonly string $value,
		private readonly int $tokenOffset,
		callable $getLocation,
	)
	{
		$this->getLocation = $getLocation;
	}

}
