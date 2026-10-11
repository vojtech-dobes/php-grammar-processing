<?php declare(strict_types=1);

require_once __DIR__ . '/../../vendor/autoload.php';

return [
	'Literal: pass' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
		],
		'x',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\TokenNode(
				new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
			),
		),
	],
	'Literal: fail, different token' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
		],
		'y',
		"Expected token 'x', got 'y' instead (line 1, col 1)",
	],
	'Literal: fail, end of stream' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
		],
		'',
		"Expected token 'x', got end of stream",
	],
	'Nonterminal: pass, token type' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Nondigit'),
		],
		'x',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\TokenNode(
				new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
			),
		),
	],
	'Nonterminal: fail, token type, different token' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Nondigit'),
		],
		'1',
		"Expected token with type 'Nondigit', got 'Digit' (1) instead (line 1, col 1)",
	],
	'Nonterminal: fail, token type, end of stream' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Nondigit'),
		],
		'',
		"Expected token with type 'Nondigit', got end of stream",
	],
	'Nonterminal: pass, rule' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('X'),
			'X' => new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
		],
		'x',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\NonterminalNode(
				'X',
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
				),
			),
		),
	],
	'Nonterminal: fail, rule, different token' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('X'),
			'X' => new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
		],
		'y',
		"Expected token 'x', got 'y' instead (line 1, col 1)",
	],
	'Nonterminal: fail, rule, end of stream' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('X'),
			'X' => new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
		],
		'',
		"Expected token 'x', got end of stream",
	],
	'Sequence: pass' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'xy',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
				),
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'y', 1),
				),
			]),
		),
	],
	'Sequence: fail, different token' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'xz',
		"Expected token 'y', got 'z' instead (line 1, col 2)",
	],
	'Sequence: fail, end of stream' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'x',
		"Expected token 'y', got end of stream",
	],
	'Sequence: fail, extra token' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'xyz',
		"Unexpected token 'z' (line 1, col 3)",
	],
	'OneOf: pass, first' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\OneOf([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'x',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\SelectedNode(
				0,
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
				),
			),
		),
	],
	'OneOf: pass, second' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\OneOf([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'y',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\SelectedNode(
				1,
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'y', 0),
				),
			),
		),
	],
	'OneOf: pass, longest wins, listed first' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\OneOf([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
				]),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
			]),
		],
		'xy',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\SelectedNode(
				0,
				new Vojtechdobes\GrammarProcessing\ListNode([
					new Vojtechdobes\GrammarProcessing\TokenNode(
						new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
					),
					new Vojtechdobes\GrammarProcessing\TokenNode(
						new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'y', 1),
					),
				]),
			),
		),
	],
	'OneOf: pass, longest wins, listed last' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\OneOf([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
				]),
			]),
		],
		'xy',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\SelectedNode(
				1,
				new Vojtechdobes\GrammarProcessing\ListNode([
					new Vojtechdobes\GrammarProcessing\TokenNode(
						new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
					),
					new Vojtechdobes\GrammarProcessing\TokenNode(
						new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'y', 1),
					),
				]),
			),
		),
	],
	'OneOf: pass, equally long, last wins' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\OneOf([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Nondigit'),
			]),
		],
		'x',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\SelectedNode(
				1,
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
				),
			),
		),
	],
	'OneOf: fail, different token' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\OneOf([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'z',
		"Expected token 'y', got 'z' instead (line 1, col 1)",
	],
	'OneOf: fail, end of stream' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\OneOf([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'',
		"Expected token 'y', got end of stream",
	],
	'OneOf: fail, furthest failure reported' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\OneOf([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
				]),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('z'),
			]),
		],
		'xz',
		"Expected token 'y', got 'z' instead (line 1, col 2)",
	],
	'Repeat: pass, min = 0, match' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				0,
				null,
			),
		],
		'x',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
				),
			]),
		),
	],
	'Repeat: pass, min = 0, multiple matches' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				0,
				null,
			),
		],
		'xx',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
				),
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 1),
				),
			]),
		),
	],
	'Repeat: pass, min = 0, no match' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
					0,
					null,
				),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'y',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([
				new Vojtechdobes\GrammarProcessing\ListNode([]),
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'y', 0),
				),
			]),
		),
	],
	'Repeat: pass, min = 0, matches, then different token' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
					0,
					null,
				),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'xxy',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([
				new Vojtechdobes\GrammarProcessing\ListNode([
					new Vojtechdobes\GrammarProcessing\TokenNode(
						new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
					),
					new Vojtechdobes\GrammarProcessing\TokenNode(
						new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 1),
					),
				]),
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'y', 2),
				),
			]),
		),
	],
	'Repeat: pass, min = 0, end of stream' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				0,
				null,
			),
		],
		'',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([]),
		),
	],
	'Repeat: pass, min = 1, match' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				1,
				null,
			),
		],
		'x',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
				),
			]),
		),
	],
	'Repeat: fail, min = 1, no match' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
					1,
					null,
				),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'y',
		"Expected token 'x', got 'y' instead (line 1, col 1)",
	],
	'Repeat: fail, min = 1, end of stream' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				1,
				null,
			),
		],
		'',
		"Expected token 'x', got end of stream",
	],
	'Repeat: pass, min = 2, match' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				2,
				null,
			),
		],
		'xx',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
				),
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 1),
				),
			]),
		),
	],
	'Repeat: fail, min = 2, below, different token' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
					2,
					null,
				),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'xy',
		"Expected token 'x', got 'y' instead (line 1, col 2)",
	],
	'Repeat: fail, min = 2, below, end of stream' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				2,
				null,
			),
		],
		'x',
		"Expected token 'x', got end of stream",
	],
	'Repeat: pass, max = 1, below' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				0,
				1,
			),
		],
		'',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([]),
		),
	],
	'Repeat: pass, max = 1, reached' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				0,
				1,
			),
		],
		'x',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
				),
			]),
		),
	],
	'Repeat: fail, max = 1, exceeded' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				0,
				1,
			),
		],
		'xx',
		"Unexpected token 'x' (line 1, col 2)",
	],
	'Repeat: pass, max = 2, below' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				0,
				2,
			),
		],
		'x',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
				),
			]),
		),
	],
	'Repeat: pass, max = 2, reached' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				0,
				2,
			),
		],
		'xx',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
				),
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 1),
				),
			]),
		),
	],
	'Repeat: fail, max = 2, exceeded' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
				0,
				2,
			),
		],
		'xxx',
		"Unexpected token 'x' (line 1, col 3)",
	],
	'Repeat: pass, partial item backtracked' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
					new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
						new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
						new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
					]),
					0,
					null,
				),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
			]),
		],
		'xyx',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\ListNode([
				new Vojtechdobes\GrammarProcessing\ListNode([
					new Vojtechdobes\GrammarProcessing\ListNode([
						new Vojtechdobes\GrammarProcessing\TokenNode(
							new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 0),
						),
						new Vojtechdobes\GrammarProcessing\TokenNode(
							new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'y', 1),
						),
					]),
				]),
				new Vojtechdobes\GrammarProcessing\TokenNode(
					new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'x', 2),
				),
			]),
		),
	],
	'Repeat: fail, partial item, literal failure' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
				]),
				0,
				null,
			),
		],
		'xyx',
		"Expected token 'y', got end of stream",
	],
	'Repeat: fail, partial item, token type failure' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
					new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Nondigit'),
				]),
				0,
				null,
			),
		],
		'xyx',
		"Expected token with type 'Nondigit', got end of stream",
	],
	'Repeat: fail, partial item, token type failure, then other symbol' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
				new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
					new Vojtechdobes\GrammarProcessing\Vocabulary\Sequence([
						new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
						new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Nondigit'),
					]),
					0,
					null,
				),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
			]),
		],
		'x',
		"Expected token with type 'Nondigit', got end of stream",
	],
	'Subtract: pass' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Subtract(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Base'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
			),
		],
		'y',
		new Vojtechdobes\GrammarProcessing\AbstractSyntaxTree(
			'Root',
			new Vojtechdobes\GrammarProcessing\NonterminalNode(
				'Base',
				new Vojtechdobes\GrammarProcessing\SelectedNode(
					1,
					new Vojtechdobes\GrammarProcessing\TokenNode(
						new Vojtechdobes\GrammarProcessing\Token('Nondigit', 'y', 0),
					),
				),
			),
		),
	],
	'Subtract: fail, base, different token' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Subtract(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Nondigit'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
			),
		],
		'1',
		"Expected token with type 'Nondigit', got 'Digit' (1) instead (line 1, col 1)",
	],
	'Subtract: fail, subtracted' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Subtract(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Base'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
			),
		],
		'x',
		"Unexpected token 'x' (line 1, col 1)",
	],
	'Subtract: fail, subtracted, end of stream' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Subtract(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
					0,
					null,
				),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Repeat(
					new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('y'),
					0,
					null,
				),
			),
		],
		'',
		'Unexpected end of stream',
	],
	'Subtract: fail, end of stream' => [
		[
			'Root' => new Vojtechdobes\GrammarProcessing\Vocabulary\Subtract(
				new Vojtechdobes\GrammarProcessing\Vocabulary\Nonterminal('Base'),
				new Vojtechdobes\GrammarProcessing\Vocabulary\Literal('x'),
			),
		],
		'',
		"Expected token with type 'Nondigit', got end of stream",
	],
];
