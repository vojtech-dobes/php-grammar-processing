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



	/**
	 * @return TName
	 */
	public function getName(): string
	{
		return $this->name;
	}



	public function getValue(): mixed
	{
		return $this->value;
	}



	public function interpret(Interpretation $interpretation): mixed
	{
		$nodeInterpretations = $interpretation->nodeInterpretations;

		$generatorStack = [];
		$currentNodeValue = NULL;

		$currentGenerator = $nodeInterpretations[$this->name]->interpret($this->value);

		while (TRUE) {
			if ($currentGenerator->valid()) {
				$subnode = $currentGenerator->current();

				if ($subnode instanceof SelectedNode) {
					$subnode = $subnode->getValue();
				}

				if ($subnode === NULL) {
					$currentGenerator->send(NULL);
				} elseif ($subnode instanceof Token) {
					$currentGenerator->send($subnode->value);
				} elseif ($subnode instanceof TokenNode) {
					if (isset($nodeInterpretations[$subnode->getName()])) {
						$generatorStack[] = $currentGenerator;
						$currentGenerator = $nodeInterpretations[$subnode->getName()]->interpret($subnode);
					} else {
						$currentGenerator->send($subnode->value->value);
					}
				} else {
					$generatorStack[] = $currentGenerator;
					$currentGenerator = $nodeInterpretations[$subnode->getName()]->interpret($subnode->getValue());
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
