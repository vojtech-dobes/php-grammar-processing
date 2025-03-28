<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


final class EmptyNode implements Node
{

	public null $value {

		get {
			return null;
		}

	}



	public function __construct(
		public readonly string $name,
	) {}

}
