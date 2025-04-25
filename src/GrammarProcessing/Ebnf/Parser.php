<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Ebnf;

use Vojtechdobes\GrammarProcessing;


final class Parser
{

	private readonly GrammarProcessing\Grammar $ebnfGrammar;
	private readonly GrammarProcessing\Interpretation $ebnfInterpretation;



	/**
	 * @template TLexicalSymbol of string
	 * @template TSyntaxTokenSymbol of TLexicalSymbol
	 * @template TIgnoredTokenSymbol of TLexicalSymbol
	 * @param non-empty-list<TLexicalSymbol> $lexicalSymbols
	 * @param non-empty-list<TSyntaxTokenSymbol> $syntaxTokenSymbols
	 * @param list<TIgnoredTokenSymbol> $ignoredTokenSymbols
	 */
	public function __construct(
		array $lexicalSymbols,
		array $syntaxTokenSymbols,
		array $ignoredTokenSymbols,
	)
	{
		$this->ebnfGrammar = new GrammarFactory()->createGrammar();
		$this->ebnfInterpretation = new InterpretationFactory()->createInterpretation(
			lexicalSymbols: $lexicalSymbols,
			syntaxTokenSymbols: $syntaxTokenSymbols,
			ignoredTokenSymbols: $ignoredTokenSymbols,
		);
	}



	public function parseGrammarFromSource(string $source): GrammarProcessing\Grammar
	{
		return $this->parseGrammarFromAbstractSyntaxTree(
			$this->ebnfGrammar->parseSource($source, 'grammar'),
		);
	}



	/**
	 * @param GrammarProcessing\AbstractSyntaxTree<'grammar'> $abstractSyntaxTree
	 */
	public function parseGrammarFromAbstractSyntaxTree(
		GrammarProcessing\AbstractSyntaxTree $abstractSyntaxTree,
	): GrammarProcessing\Grammar
	{
		return $abstractSyntaxTree->interpret($this->ebnfInterpretation);
	}

}
