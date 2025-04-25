<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Ebnf;

use Vojtechdobes\GrammarProcessing;


final class InterpretationFactory
{

	/**
	 * @template TLexicalSymbol of string
	 * @template TSyntaxTokenSymbol of TLexicalSymbol
	 * @template TIgnoredTokenSymbol of TLexicalSymbol
	 * @param non-empty-list<TLexicalSymbol> $lexicalSymbols
	 * @param non-empty-list<TSyntaxTokenSymbol> $syntaxTokenSymbols
	 * @param list<TIgnoredTokenSymbol> $ignoredTokenSymbols
	 */
	public function createInterpretation(
		array $lexicalSymbols,
		array $syntaxTokenSymbols,
		array $ignoredTokenSymbols,
	): GrammarProcessing\Interpretation
	{
		return new GrammarProcessing\Interpretation([
			'alternation' => new Nodes\Alternation(),
			'concatenation' => new Nodes\Concatenation(),
			'factor' => new Nodes\Factor(),
			'grammar' => new Nodes\Grammar(
				lexicalSymbols: $lexicalSymbols,
				syntaxTokenSymbols: $syntaxTokenSymbols,
				ignoredTokenSymbols: $ignoredTokenSymbols,
			),
			'identifier' => new Nodes\Identifier(),
			'lhs' => new Nodes\Lhs(),
			'rhs' => new Nodes\Rhs(),
			'rule' => new Nodes\Rule(),
			'term' => new Nodes\Term(),
			'terminal' => new Nodes\Terminal(),
		]);
	}

}
