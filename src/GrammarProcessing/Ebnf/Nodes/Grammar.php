<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Ebnf\Nodes;

use Generator;
use LogicException;
use Vojtechdobes\GrammarProcessing;


final class Grammar implements GrammarProcessing\NodeInterpretation
{

	/**
	 * @template TLexicalSymbol of string
	 * @template TSyntaxTokenSymbol of TLexicalSymbol
	 * @template TIgnoredTokenSymbol of TLexicalSymbol
	 * @param non-empty-list<TLexicalSymbol> $lexicalSymbols
	 * @param non-empty-list<TSyntaxTokenSymbol> $syntaxTokenSymbols
	 * @param list<TIgnoredTokenSymbol> $ignoredTokenSymbols
	 */
	public function __construct(
		private readonly array $lexicalSymbols,
		private readonly array $syntaxTokenSymbols,
		private readonly array $ignoredTokenSymbols,
	) {}



	public function interpret(GrammarProcessing\Node $node): Generator
	{
		$result = [];

		foreach ($node->value as $item) {
			[$identifier, $production] = yield $item->value[1];

			$result[$identifier->nonterminal] = $production;
		}

		$result = $this->ensureOnlyNonoptionalSyntaxTokenSymbols($result);

		return $this->createGrammar($result);
	}



	/**
	 * @param array<string, GrammarProcessing\Vocabulary\Symbol> $symbols
	 * @return array<string, GrammarProcessing\Vocabulary\Symbol>
	 */
	private function ensureOnlyNonoptionalSyntaxTokenSymbols(array $symbols): array
	{
		$optionalSyntaxTokenSymbols = [];

		foreach ($this->syntaxTokenSymbols as $syntaxTokenSymbol) {
			if (array_key_exists($syntaxTokenSymbol, $symbols) === false) {
				throw new LogicException(
					"Syntax token symbol '{$syntaxTokenSymbol}' wasn't found in parsed source",
				);
			}

			$symbol = $symbols[$syntaxTokenSymbol];

			if (
				$symbol instanceof GrammarProcessing\Vocabulary\Repeat
				&& $symbol->min === 0
			) {
				$optionalSyntaxTokenSymbols[] = $syntaxTokenSymbol;
			}
		}

		if ($optionalSyntaxTokenSymbols === []) {
			return $symbols;
		}

		$replace = function (
			GrammarProcessing\Vocabulary\Symbol $symbol,
		) use (
			$optionalSyntaxTokenSymbols,
		): GrammarProcessing\Vocabulary\Symbol {
			if (
				$symbol instanceof GrammarProcessing\Vocabulary\Nonterminal
				&& in_array($symbol->nonterminal, $optionalSyntaxTokenSymbols, true)
			) {
				return new GrammarProcessing\Vocabulary\Repeat(
					$symbol,
					0,
					1,
				);
			}

			return $symbol;
		};

		foreach ($symbols as $name => $symbol) {
			if (in_array($name, $optionalSyntaxTokenSymbols, true)) {
				/** @var GrammarProcessing\Vocabulary\Repeat $symbol */

				$symbols[$name] = new GrammarProcessing\Vocabulary\Repeat(
					$symbol->symbol->visit($replace),
					1,
					$symbol->max,
				);
			} else {
				$symbols[$name] = $symbol->visit($replace);
			}
		}

		return $symbols;
	}



	/**
	 * @param array<string, GrammarProcessing\Vocabulary\Symbol> $symbols
	 */
	private function createGrammar(array $symbols): GrammarProcessing\Grammar
	{
		$lexicalSymbols = [];

		foreach ($this->lexicalSymbols as $lexicalSymbol) {
			if (array_key_exists($lexicalSymbol, $symbols) === false) {
				throw new LogicException(
					"Lexical symbol '{$lexicalSymbol}' wasn't found in parsed source",
				);
			}

			$lexicalSymbols[$lexicalSymbol] = $symbols[$lexicalSymbol];
		}

		$syntacticSymbols = [];

		foreach ($symbols as $name => $symbol) {
			if (in_array($symbol, $lexicalSymbols, true) === false) {
				$syntacticSymbols[$name] = $symbol;
			}
		}

		if ($syntacticSymbols === []) {
			throw new LogicException(
				"Grammar doesn't contain any non-lexical symbols",
			);
		}

		return new GrammarProcessing\Grammar(
			lexicalSymbols: $lexicalSymbols,
			syntaxTokenSymbols: $this->syntaxTokenSymbols,
			ignoredTokenSymbols: $this->ignoredTokenSymbols,
			syntacticSymbols: $syntacticSymbols,
		);
	}

}
