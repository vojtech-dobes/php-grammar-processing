<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class NonterminalNode implements Node
{

	public function __construct(
		public readonly string $name,
		public readonly mixed $value,
	) {}



	public function getName(): string
	{
		return $this->name;
	}



	public function getValue(): mixed
	{
		return $this->value;
	}

}
