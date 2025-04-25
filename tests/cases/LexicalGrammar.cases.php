<?php declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

return [
	'empty source' => [
		[],
		[
			'Base',
		],
		[
			'Base' => new Vojtechdobes\GrammarProcessing\Vocabulary\Regexp('.'),
		],
		'',
		[],
	],
	'any character' => [
		[],
		[
			'Base',
		],
		[
			'Base' => new Vojtechdobes\GrammarProcessing\Vocabulary\Regexp('.'),
		],
		'abc',
		[
			['Base', 'a', '1,1'],
			['Base', 'b', '1,2'],
			['Base', 'c', '1,3'],
		],
	],
	'any character with ignored whitespace' => [
		[
			'Ignored',
		],
		[
			'Base',
			'Ignored',
		],
		[
			'Base' => new Vojtechdobes\GrammarProcessing\Vocabulary\Regexp('.'),
			'Ignored' => new Vojtechdobes\GrammarProcessing\Vocabulary\Regexp('\s'),
		],
		'a b c',
		[
			['Base', 'a', '1,1'],
			['Base', 'b', '1,3'],
			['Base', 'c', '1,5'],
		],
	],
	'keyword character with everything else ignored' => [
		[
			'Else',
		],
		[
			'Else',
			'Keyword',
		],
		[
			'Keyword' => new Vojtechdobes\GrammarProcessing\Vocabulary\Regexp('a'),
			'Else' => new Vojtechdobes\GrammarProcessing\Vocabulary\Subtract(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Regexp('.'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Keyword'),
			),
		],
		'a b c',
		[
			['Keyword', 'a', '1,1'],
		],
	],
	'single character' => [
		[],
		[
			'Base',
		],
		[
			'Base' => new Vojtechdobes\GrammarProcessing\Vocabulary\Regexp('a'),
		],
		'a',
		[
			['Base', 'a', '1,1'],
		],
	],
	'invalid character' => [
		[],
		[
			'Base',
		],
		[
			'Base' => new Vojtechdobes\GrammarProcessing\Vocabulary\Regexp('a'),
		],
		'ab',
		"Unexpected token 'b' (line 1, col 2)",
	],
];
