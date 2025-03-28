<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;

use LogicException;


/**
 * @template-covariant TSymbol of string
 */
final class LexicalGrammar
{

	/**
	 * @template TSyntaxTokenSymbol of TSymbol
	 * @template TIgnoredTokenSymbol of TSymbol
	 * @param non-empty-array<TSymbol, Vocabulary\Symbol> $symbols
	 * @param non-empty-list<TSyntaxTokenSymbol> $syntaxTokenSymbols
	 * @param list<TIgnoredTokenSymbol> $ignoredTokenSymbols
	 */
	public function __construct(
		private readonly array $symbols,
		private readonly array $syntaxTokenSymbols,
		private readonly array $ignoredTokenSymbols,
	) {}



	/**
	 * @throws UnexpectedTokenException
	 */
	public function parseSource(string $source): TokenStream
	{
		$res = preg_match_all(
			$this->createRegexPattern(),
			$source,
			$matches,
			PREG_OFFSET_CAPTURE | PREG_SET_ORDER | PREG_UNMATCHED_AS_NULL,
		);

		if ($res === false) {
			throw new UnexpectedTokenException("Can't parse source: " . preg_last_error_msg(), null);
		}

		if ($matches === []) {
			return new TokenStream([]);
		}

		$getLocation = function (int $offset) use ($source): Location {
			$precedingText = substr($source, 0, $offset);

			return new Location(
				line: substr_count($precedingText, "\n") + 1,
				column: $offset - strrpos("\n" . $precedingText, "\n") + 1,
			);
		};

		$expectedOffset = 0;
		$endOffset = strlen($source);

		foreach ($matches as $match) {
			if ($expectedOffset !== $match[0][1]) {
				$this->throwUnexpectedTokenException(
					$match[0][0],
					$getLocation($match[0][1]),
				);
			}

			$expectedOffset += strlen($match[0][0]);
		}

		if ($expectedOffset !== strlen($source)) {
			$this->throwUnexpectedTokenException(
				substr($source, $expectedOffset),
				$getLocation($expectedOffset),
			);
		}

		if ($this->ignoredTokenSymbols !== []) {
			if (count($this->ignoredTokenSymbols) === 1) {
				$matches = array_values(
					array_filter(
						$matches,
						static fn ($match) => $match[1][0] === NULL,
					),
				);
			} else {
				$range = range(0, count($this->ignoredTokenSymbols));

				$matches = array_values(
					array_filter(
						$matches,
						static fn ($match) => array_filter(
							$range,
							static fn ($i) => $match[$i + 1][0] !== null,
						) === [],
					),
				);
			}
		}

		if ($matches === []) {
			return new TokenStream([]);
		}

		$iMin = 1 + count($this->ignoredTokenSymbols);
		$iMax = count($this->syntaxTokenSymbols) + $iMin;

		return new TokenStream(
			array_map(
				function (array $match) use ($getLocation, $iMax, $iMin): Token {
					$type = NULL;

					for ($i = $iMin; $i < $iMax; $i++) {
						if ($match[$i][0] !== NULL) {
							$type = $this->syntaxTokenSymbols[$i - $iMin];
						}
					}

					return new Token(
						$type,
						$match[0][0],
						$match[0][1],
						$getLocation,
					);
				},
				$matches,
			),
		);
	}



	private function createRegexPattern(): string
	{
		return '~' . implode('|', array_map(
			fn ($lexicalSymbol) => sprintf(
				'(%s)',
				new Vocabulary\Nonterminal($lexicalSymbol)->getPattern($this->symbols),
			),
			[...$this->ignoredTokenSymbols, ...$this->syntaxTokenSymbols],
		)) . '~u';
	}



	/**
	 * @throws UnexpectedTokenException
	 */
	private function throwUnexpectedTokenException(
		string $value,
		Location $location,
	): never
	{
		throw new UnexpectedTokenException(
			sprintf(
				"Unxpected token '%s'",
				strlen($value) > 12
					? substr($value, 0, 10) . '... (truncated)'
					: $value,
			),
			$location,
		);
	}

}
