<?php declare(strict_types=1);

namespace Vojtechdobes\GrammarProcessing;


/**
 * @template TName of string
 */
final class AbstractSyntaxTree implements Node
{

	/**
	 * @param TName $name
	 */
	public function __construct(
		public readonly string $name,
		public readonly mixed $value,
	) {}



	public function interpret(Interpretation $interpretation): mixed
	{
		$nodeInterpretations = $interpretation->nodeInterpretations;

		$generatorStack = [];
		$currentNodeValue = null;

		$currentGenerator = $nodeInterpretations[$this->name]->interpret($this->value);

		while (true) {
			if ($currentGenerator->valid()) {
				$subnode = $currentGenerator->current();

				if ($subnode instanceof SelectedNode) {
					$subnode = $subnode->value;
				}

				if ($subnode === null) {
					$currentGenerator->send(null);
				} elseif ($subnode instanceof Token) {
					$currentGenerator->send($subnode->value);
				} elseif ($subnode instanceof TokenNode) {
					if (isset($nodeInterpretations[$subnode->name])) {
						$generatorStack[] = $currentGenerator;
						$currentGenerator = $nodeInterpretations[$subnode->name]->interpret($subnode);
					} else {
						$currentGenerator->send($subnode->value->value);
					}
				} else {
					$generatorStack[] = $currentGenerator;
					$currentGenerator = $nodeInterpretations[$subnode->name]->interpret($subnode->value);
				}
			} else {
				$currentNodeValue = $currentGenerator->getReturn();

				if ($generatorStack !== []) {
					$currentGenerator = array_pop($generatorStack);
					$currentGenerator->send($currentNodeValue);
				} else {
					break;
				}
			}
		}

		return $currentNodeValue;
	}

}
