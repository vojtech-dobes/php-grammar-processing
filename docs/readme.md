# 📓 Grammar Processing for PHP

This library provides convenient APIs for describing a context-free grammar, and subsequently using it to build an abstract syntax tree and analyze it for an arbitrary purpose.



## Installation

To install the latest version, run the following command:

```
composer require vojtech-dobes/php-grammar-processing
```

Minimum supported PHP version is 8.4.



## Documentation

> [!NOTE]
> All code examples assume classes are imported from `Vojtechdobes\GrammarProcessing` namespace.

Let's say in variable `$source` we have source text of a language that we want to process. For example, we want to evaluate a mathematical expression:

```php
$source = '2 + 5';
```

Processing of the source text happens in few phases:

$$Tokenization \to Parsing \to Interpretation$$

1. In _Tokenization_ phase, source text is split into basic small units called lexical tokens.
2. In _Parsing_ phase, abstract syntax tree is constructed from these lexical tokens.
3. In _Interpretation_ phase, abstract syntax tree is evaluated to a final arbitrary result that can be used further.

To carry out _Tokenization_ and _Parsing_ phases, we first have to define grammar of the language.



### Grammar definition

Grammar is defined by creating [`Grammar`](../src/GrammarProcessing/Grammar.php) object.

```php
$grammar = new Grammar(
    // Tokenization
    lexicalSymbols: $lexicalSymbols,
    syntaxTokenSymbols: $syntaxTokenSymbols,
    ignoredTokenSymbols: $ignoredTokenSymbols,
    // Parsing
    syntacticSymbols: $syntacticSymbols,
);
```

- **`$lexicalSymbols`**

  ```
  array<string, Vocabulary\Symbol>
  ```

  Lexical symbols represent [production rules](https://en.wikipedia.org/wiki/Production_(computer_science)) for the source text. Production rule is provided as implementation of `Vocabulary\Symbol` interface. Example:

  ```php
  $lexicalSymbols = [
      'Digit' => new Vocabulary\Regexp('[0-9]'),
      'Integer' => new Vocabulary\Sequence([
          new Vocabulary\Repeat(new Vocabulary\Nonterminal('Digit'), min: 1, max: null),
          new Vocabulary\NegativeLookahead(new Vocabulary\Nonterminal('Digit')),
      ]),
      'Operator' => new Vocabulary\OneOf([
          new Vocabulary\Literal('+'),
          new Vocabulary\Literal('-'),
      ]),
      'Whitespace' => new Vocabulary\Regexp('\s'),
  ];
  ```

  All available implementations of `Vocabulary\Symbol` are [listed below](#available-symbols). Through `Vocabulary\Nonterminal` instance lexical symbol can be based on other lexical symbols.

- **`$syntaxTokenSymbols`**

  ```
  list<string>
  ```

  From lexical symbols we select those that will become constituent items of the resulting token stream. Every part of the source text (except for [ignored parts](#ignored-token-symbols)) must fit into one of syntax token symbols, otherwise `UnexpectedTokenException` will be thrown.

  ```php
  $syntaxTokenSymbols = ['Integer', 'Operator'];
  ```

- <a name="ignored-token-symbols"></a>**`$ignoredTokenSymbols`**

  ```
  list<string>
  ```

  Not all parts of the source code are relevant in later stages, e.g. arbitrary whitespace or comments can be often discarded. We can list lexical symbols that cover such parts of the source code:

  ```php
  $ignoredTokenSymbols = ['Whitespace'];
  ```

- **`$syntacticSymbols`**

  ```
  array<string, Vocabulary\Symbol>
  ```

  Every syntactic symbol represents a syntactically meaningful sequence of lexical tokens. Like `$lexicalSymbols`, this parameter is a map where key is name of a symbol and value is production rule in form of `Vocabulary\Symbol` instance. Through `Vocabulary\Nonterminal` instance syntactic symbol can contain both lexical symbols and other syntactic symbols, thus giving rise to a tree structure. Example:

  ```php
  $syntacticSymbols = [
      'BinaryOperation' => new Vocabulary\OneOf([
          new Vocabulary\Nonterminal('Addition'),
          new Vocabulary\Nonterminal('Subtraction'),
      ]),
      'Addition' => new Vocabulary\Sequence([
          new Vocabulary\Nonterminal('Integer'),
          new Vocabulary\Literal('+'),
          new Vocabulary\Nonterminal('Integer'),
      ]),
      'Subtraction' => new Vocabulary\Sequence([
          new Vocabulary\Nonterminal('Integer'),
          new Vocabulary\Literal('-'),
          new Vocabulary\Nonterminal('Integer'),
      ]),
  ];
  ```



### Grammar usage

With `Grammar` instance, we can produce abstract syntax tree. We have to choose root syntactical symbol, which is expected to encompass the whole source text (the whole token stream).

```php
$abstractSyntaxTree = $grammar->parseSource($source, rootSymbol: 'BinaryOperation');
```

In case of an error, `UnexpectedTokenException` will be thrown.

The `parseSource` call carries out both _Tokenization_ and _Parsing_ phase. Alternatively we can execute them manually:

```php
$tokenStream = $grammar->tokenizeSource($source);
$abstractSyntaxTree = $grammar->parseTokenStream($tokenStream, rootSymbol: 'BinaryOperation');
```



### Interpretation

With our example mathematical expression, result of _Interpretation_ phase should be a number `7`. To do so, we will make following call:

```php
$result = $abstractSyntaxTree->interpret([
    'Addition' => new AdditionNodeInterpretation(),
    'Integer' => new IntegerNodeInterpretation(),
    'Subtraction' => new SubtractionNodeInterpretation(),
]);
```

Values in the provided map are custom implementations of `NodeInterpretation` interface. Each is responsible to evaluate node in the abstract syntax tree it's registered for. Our `AdditionNodeInterpretation` implementation could look like this:

```php
class AdditionNodeInterpretation implements NodeInterpretation
{
    public function evaluate(Node $node): Generator
    {
        return yield $node->value[0] + yield $node->value[2];
    }
}
```

What's happening here? In `$syntacticSymbols` we have defined `Addition` to be a `Sequence` with 3 elements. First and last elements are the operands of our mathematical addition operation. The middle element in the sequence (literal `+`) doesn't play role in our interpretation anymore - we already know we are in `Addition` node which is responsible for... adding things up!

To make sense of the operands, we can yield subnodes of the abstract syntax tree in order to interpret them first. In this case `$node->value[0]` & `$node->value[2]` represent `Integer` nodes, and so `IntegerNodeInterpretation` will be used to interpret them.

```php
class IntegerNodeInterpretation implements NodeInterpretation
{
    public function evaluate(Node $node): Generator
    {
        return (int) yield $node->value;
    }
}
```

Because `Integer` is lexical token, value of the node is `Token` from the token stream. Yielding instance of `Token` simply returns it's string value, which we can safely cast to PHP `int` type.



### Available symbols

- `Vocabulary\Literal`
- `Vocabulary\NegativeLookahead`
- `Vocabulary\Nonterminal`
- `Vocabulary\OneOf`
- `Vocabulary\Regexp`
- `Vocabulary\Sequence`
- `Vocabulary\Subtract`
