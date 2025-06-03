<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class EmptyNode implements Node
{

	public function __construct(
		public readonly string $name,
	) {}



	public function getName(): string
	{
		return $this->name;
	}



	public function getValue(): null
	{
		return null;
	}

}
