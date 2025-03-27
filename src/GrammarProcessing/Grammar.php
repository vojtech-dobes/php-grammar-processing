<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class Grammar
{

	/** @var LexicalGrammar<string> */
	private readonly LexicalGrammar $lexicalGrammar;

	/** @var SyntacticGrammar<string> */
	private readonly SyntacticGrammar $syntacticGrammar;



	/**
	 * @template TLexicalSymbol of string
	 * @template TSyntaxTokenSymbol of TLexicalSymbol
	 * @template TIgnoredTokenSymbol of TLexicalSymbol
	 * @param non-empty-array<TLexicalSymbol, Vocabulary\Symbol> $lexicalSymbols
	 * @param non-empty-list<TSyntaxTokenSymbol> $syntaxTokenSymbols
	 * @param list<TIgnoredTokenSymbol> $ignoredTokenSymbols
	 * @param non-empty-array<string, Vocabulary\Symbol> $syntacticSymbols
	 */
	public function __construct(
		array $lexicalSymbols,
		array $syntaxTokenSymbols,
		array $ignoredTokenSymbols,
		array $syntacticSymbols,
	)
	{
		$this->lexicalGrammar = new LexicalGrammar(
			symbols: $lexicalSymbols,
			syntaxTokenSymbols: $syntaxTokenSymbols,
			ignoredTokenSymbols: $ignoredTokenSymbols,
		);

		$this->syntacticGrammar = new SyntacticGrammar(
			syntacticSymbols: $syntacticSymbols,
		);
	}



	public function tokenizeSource(string $source): TokenStream
	{
		return $this->lexicalGrammar->parseSource($source);
	}



	/**
	 * @template TRootSymbol of string
	 * @param TRootSymbol $rootSymbol
	 * @return AbstractSyntaxTree<TRootSymbol>
	 * @throws CannotConsumeTokenException
	 */
	public function parseSource(string $source, string $rootSymbol): AbstractSyntaxTree
	{
		return $this->parseTokenStream(
			$this->tokenizeSource($source),
			$rootSymbol,
		);
	}



	/**
	 * @template TRootSymbol of string
	 * @param TRootSymbol $rootSymbol
	 * @return AbstractSyntaxTree<TRootSymbol>
	 * @throws CannotConsumeTokenException
	 */
	public function parseTokenStream(TokenStream $tokenStream, string $rootSymbol): AbstractSyntaxTree
	{
		return $this->syntacticGrammar->parseLexicalTokens(
			$tokenStream,
			$rootSymbol,
		);
	}

}
