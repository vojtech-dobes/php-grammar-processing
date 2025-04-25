<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class LocationGetter
{

	public function __construct(
		private readonly string $source,
	) {}



	public function getLocation(int $offset): Location
	{
		$precedingText = substr($this->source, 0, $offset);

		return new Location(
			line: substr_count($precedingText, "\n") + 1,
			column: $offset - strrpos("\n" . $precedingText, "\n") + 1,
		);
	}

}
