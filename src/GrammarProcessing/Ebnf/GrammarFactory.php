<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing\Ebnf;

use Vojtechdobes\GrammarProcessing;


final class GrammarFactory
{

	public function createGrammar(): GrammarProcessing\Grammar
	{
		$lexicalSymbols = [
			'letter' => new GrammarProcessing\Vocabulary\Regexp('[a-zA-Z]'),
			'digit' => new GrammarProcessing\Vocabulary\Regexp('[0-9]'),
			'symbol' => new GrammarProcessing\Vocabulary\OneOf([
				new GrammarProcessing\Vocabulary\Literal('['),
				new GrammarProcessing\Vocabulary\Literal(']'),
				new GrammarProcessing\Vocabulary\Literal('{'),
				new GrammarProcessing\Vocabulary\Literal('}'),
				new GrammarProcessing\Vocabulary\Literal('('),
				new GrammarProcessing\Vocabulary\Literal(')'),
				new GrammarProcessing\Vocabulary\Literal('<'),
				new GrammarProcessing\Vocabulary\Literal('>'),
				new GrammarProcessing\Vocabulary\Literal("'"),
				new GrammarProcessing\Vocabulary\Literal('"'),
				new GrammarProcessing\Vocabulary\Literal('='),
				new GrammarProcessing\Vocabulary\Literal('|'),
				new GrammarProcessing\Vocabulary\Literal('.'),
				new GrammarProcessing\Vocabulary\Literal(','),
				new GrammarProcessing\Vocabulary\Literal(';'),
				new GrammarProcessing\Vocabulary\Literal('-'),
				new GrammarProcessing\Vocabulary\Literal('+'),
				new GrammarProcessing\Vocabulary\Literal('*'),
				new GrammarProcessing\Vocabulary\Literal('?'),
				new GrammarProcessing\Vocabulary\Literal('\\'),
			]),
			'character' => new GrammarProcessing\Vocabulary\OneOf([
				new GrammarProcessing\Vocabulary\Nonterminal('letter'),
				new GrammarProcessing\Vocabulary\Nonterminal('digit'),
				new GrammarProcessing\Vocabulary\Nonterminal('symbol'),
				new GrammarProcessing\Vocabulary\Literal('_'),
				new GrammarProcessing\Vocabulary\Literal(' '),
			]),
			'identifier' => new GrammarProcessing\Vocabulary\Sequence([
				new GrammarProcessing\Vocabulary\Nonterminal('letter'),
				new GrammarProcessing\Vocabulary\Repeat(
					new GrammarProcessing\Vocabulary\OneOf([
						new GrammarProcessing\Vocabulary\Nonterminal('letter'),
						new GrammarProcessing\Vocabulary\Nonterminal('digit'),
						new GrammarProcessing\Vocabulary\Literal('_'),
					]),
					0,
					null,
				),
			]),
			'terminal' => new GrammarProcessing\Vocabulary\OneOf([
				new GrammarProcessing\Vocabulary\Sequence([
					new GrammarProcessing\Vocabulary\Literal("'"),
					new GrammarProcessing\Vocabulary\Repeat(
						new GrammarProcessing\Vocabulary\Subtract(
							new GrammarProcessing\Vocabulary\Nonterminal('character'),
							new GrammarProcessing\Vocabulary\Literal("'"),
						),
						1,
						null,
					),
					new GrammarProcessing\Vocabulary\Literal("'"),
				]),
				new GrammarProcessing\Vocabulary\Sequence([
					new GrammarProcessing\Vocabulary\Literal('"'),
					new GrammarProcessing\Vocabulary\Repeat(
						new GrammarProcessing\Vocabulary\Subtract(
							new GrammarProcessing\Vocabulary\Nonterminal('character'),
							new GrammarProcessing\Vocabulary\Literal('"'),
						),
						1,
						null,
					),
					new GrammarProcessing\Vocabulary\Literal('"'),
				]),
			]),
			'S' => new GrammarProcessing\Vocabulary\Repeat(
				new GrammarProcessing\Vocabulary\OneOf([
					new GrammarProcessing\Vocabulary\Literal(' '),
					new GrammarProcessing\Vocabulary\Regexp('\n'),
					new GrammarProcessing\Vocabulary\Regexp('\t'),
					new GrammarProcessing\Vocabulary\Regexp('\r'),
					new GrammarProcessing\Vocabulary\Regexp('\f'),
					new GrammarProcessing\Vocabulary\Regexp('\b'),
				]),
				1,
				null,
			),
		];

		$syntacticSymbols = [
			'opt_S' => new GrammarProcessing\Vocabulary\Repeat(
				new GrammarProcessing\Vocabulary\Nonterminal('S'),
				0,
				null,
			),
			'terminator' => new GrammarProcessing\Vocabulary\OneOf([
				new GrammarProcessing\Vocabulary\Literal(';'),
				new GrammarProcessing\Vocabulary\Literal('.'),
			]),
			'term' => new GrammarProcessing\Vocabulary\OneOf([
				new GrammarProcessing\Vocabulary\Sequence([
					new GrammarProcessing\Vocabulary\Literal('('),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					new GrammarProcessing\Vocabulary\Nonterminal('rhs'),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					new GrammarProcessing\Vocabulary\Literal(')'),
				]),
				new GrammarProcessing\Vocabulary\Sequence([
					new GrammarProcessing\Vocabulary\Literal('['),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					new GrammarProcessing\Vocabulary\Nonterminal('rhs'),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					new GrammarProcessing\Vocabulary\Literal(']'),
				]),
				new GrammarProcessing\Vocabulary\Sequence([
					new GrammarProcessing\Vocabulary\Literal('{'),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					new GrammarProcessing\Vocabulary\Nonterminal('rhs'),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					new GrammarProcessing\Vocabulary\Literal('}'),
				]),
				new GrammarProcessing\Vocabulary\Nonterminal('terminal'),
				new GrammarProcessing\Vocabulary\Nonterminal('identifier'),
			]),
			'factor' => new GrammarProcessing\Vocabulary\OneOf([
				new GrammarProcessing\Vocabulary\Sequence([
					new GrammarProcessing\Vocabulary\Nonterminal('term'),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					new GrammarProcessing\Vocabulary\Literal('?'),
				]),
				new GrammarProcessing\Vocabulary\Sequence([
					new GrammarProcessing\Vocabulary\Nonterminal('term'),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					new GrammarProcessing\Vocabulary\Literal('*'),
				]),
				new GrammarProcessing\Vocabulary\Sequence([
					new GrammarProcessing\Vocabulary\Nonterminal('term'),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					new GrammarProcessing\Vocabulary\Literal('+'),
				]),
				new GrammarProcessing\Vocabulary\Sequence([
					new GrammarProcessing\Vocabulary\Nonterminal('term'),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					new GrammarProcessing\Vocabulary\Literal('-'),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					new GrammarProcessing\Vocabulary\Nonterminal('term'),
				]),
				new GrammarProcessing\Vocabulary\Sequence([
					new GrammarProcessing\Vocabulary\Nonterminal('term'),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
				]),
			]),
			'concatenation' => new GrammarProcessing\Vocabulary\Sequence([
				new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
				new GrammarProcessing\Vocabulary\Nonterminal('factor'),
				new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
				new GrammarProcessing\Vocabulary\Repeat(
					new GrammarProcessing\Vocabulary\Sequence([
						new GrammarProcessing\Vocabulary\Literal(','),
						new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
						new GrammarProcessing\Vocabulary\Nonterminal('factor'),
						new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					]),
					0,
					null,
				),
			]),
			'alternation' => new GrammarProcessing\Vocabulary\Sequence([
				new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
				new GrammarProcessing\Vocabulary\Nonterminal('concatenation'),
				new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
				new GrammarProcessing\Vocabulary\Repeat(
					new GrammarProcessing\Vocabulary\Sequence([
						new GrammarProcessing\Vocabulary\Literal('|'),
						new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
						new GrammarProcessing\Vocabulary\Nonterminal('concatenation'),
						new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					]),
					0,
					null,
				),
			]),
			'rhs' => new GrammarProcessing\Vocabulary\Nonterminal('alternation'),
			'lhs' => new GrammarProcessing\Vocabulary\Nonterminal('identifier'),
			'rule' => new GrammarProcessing\Vocabulary\Sequence([
				new GrammarProcessing\Vocabulary\Nonterminal('lhs'),
				new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
				new GrammarProcessing\Vocabulary\Literal('='),
				new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
				new GrammarProcessing\Vocabulary\Nonterminal('rhs'),
				new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
				new GrammarProcessing\Vocabulary\Nonterminal('terminator'),
			]),
			'grammar' => new GrammarProcessing\Vocabulary\Repeat(
				new GrammarProcessing\Vocabulary\Sequence([
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
					new GrammarProcessing\Vocabulary\Nonterminal('rule'),
					new GrammarProcessing\Vocabulary\Nonterminal('opt_S'),
				]),
				0,
				null,
			),
		];

		return new GrammarProcessing\Grammar(
			lexicalSymbols: $lexicalSymbols,
			syntaxTokenSymbols: [
				'identifier',
				'terminal',
				'symbol',
				'S',
			],
			ignoredTokenSymbols: [],
			syntacticSymbols: $syntacticSymbols,
		);
	}

}
