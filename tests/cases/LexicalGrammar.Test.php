<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

/**
 * @dataProvider LexicalGrammar.cases.php
 */

$case = Tester\Environment::loadData();

[
	$ignoredTokenSymbols,
	$syntaxTokenSymbols,
	$symbols,
	$source,
	$expectedTokens,
] = $case;

$lexicalGrammar = new Vojtechdobes\GrammarProcessing\LexicalGrammar(
	ignoredTokenSymbols: $ignoredTokenSymbols,
	syntaxTokenSymbols: $syntaxTokenSymbols,
	symbols: $symbols,
);

try {
	$tokenStream = $lexicalGrammar->parseSource($source);

	Tester\Assert::equal(
		expected: $expectedTokens,
		actual: array_map(
			static fn ($token) => [
				$token->type,
				$token->value,
				"{$token->location->line},{$token->location->column}",
			],
			$tokenStream->tokens,
		),
		matchOrder: true,
	);
} catch (Vojtechdobes\GrammarProcessing\UnexpectedTokenException $e) {
	if (is_string($expectedTokens)) {
		Tester\Assert::same(
			expected: $expectedTokens,
			actual: $e->getMessage(),
		);
	} else {
		throw $e;
	}
}
