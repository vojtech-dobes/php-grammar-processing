<?php declare(strict_types=1);

require_once __DIR__ . '/../bootstrap.php';

/**
 * @dataProvider SyntacticGrammar.cases.php
 */

/** @var array<mixed> $case */
$case = Tester\Environment::loadData();

[
	$symbols,
	$source,
	$expectedAst,
] = $case;

$locationGetter = new Vojtechdobes\GrammarProcessing\LocationGetter($source);

function formatLocation(Vojtechdobes\GrammarProcessing\Location $location): string
{
	return "{$location->line},{$location->column}";
}

$lexicalGrammar = new Vojtechdobes\GrammarProcessing\LexicalGrammar(
	ignoredTokenSymbols: [],
	syntaxTokenSymbols: [
		'Digit', // must precede Nondigit, which matches anything
		'Nondigit',
	],
	symbols: [
		'Digit' => new Vojtechdobes\GrammarProcessing\Vocabulary\Regexp('[0-9]'),
		'Nondigit' => new Vojtechdobes\GrammarProcessing\Vocabulary\Regexp('.'),
	],
);

$tokenStream = $lexicalGrammar->parseSource($source);

$syntacticGrammar = new Vojtechdobes\GrammarProcessing\SyntacticGrammar($symbols + [
	'Base' => new Vojtechdobes\GrammarProcessing\Vocabulary\OneOf([
		new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Digit'),
		new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Nondigit'),
	]),
]);

try {
	$ast = $syntacticGrammar->parseLexicalTokens($tokenStream, 'Root');

	Tester\Assert::equal(
		expected: $expectedAst,
		actual: $ast,
		matchOrder: true,
	);
} catch (Vojtechdobes\GrammarProcessing\CannotConsumeTokenException $e) {
	if (is_string($expectedAst)) {
		Tester\Assert::same(
			expected: $expectedAst,
			actual: $e->getMessage(),
		);
	} else {
		throw $e;
	}
}
