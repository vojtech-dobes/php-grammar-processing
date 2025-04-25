<?php declare(strict_types=1);

require_once __DIR__ . '/../../bootstrap.php';


$ebnfParser = new Vojtechdobes\GrammarProcessing\Ebnf\Parser(
	lexicalSymbols: [
		'letter',
		'digit',
		'symbol',
		'character',
		'identifier',
		'terminal',
		'S',
	],
	syntaxTokenSymbols: [
		'identifier',
		'terminal',
		'symbol',
		'S',
	],
	ignoredTokenSymbols: [],
);

$ebnfGrammarSource = file_get_contents(__DIR__ . '/ebnf-grammar.ebnf');


$ebnfGrammarA = $ebnfParser->parseGrammarFromSource($ebnfGrammarSource);

$ebnfGrammarB = $ebnfParser->parseGrammarFromAbstractSyntaxTree(
	$ebnfGrammarA->parseSource($ebnfGrammarSource, 'grammar'),
);

$ebnfGrammarC = $ebnfParser->parseGrammarFromAbstractSyntaxTree(
	$ebnfGrammarB->parseSource($ebnfGrammarSource, 'grammar'),
);

Tester\Assert::equal(
	$ebnfGrammarA,
	$ebnfGrammarB,
);
