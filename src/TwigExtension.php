<?php

declare(strict_types=1);

namespace Xylemical\Code\Writer\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigTest;
use Xylemical\Code\Definition\Constant;
use Xylemical\Code\Definition\Contract;
use Xylemical\Code\Definition\File;
use Xylemical\Code\Definition\Method;
use Xylemical\Code\Definition\Mixin;
use Xylemical\Code\Definition\Parameter;
use Xylemical\Code\Definition\Property;
use Xylemical\Code\Definition\Structure;
use Xylemical\Code\FullyQualifiedName;
use Xylemical\Code\Util\Indenter;

/**
 * Provides functionality to simplify twig templating for code structures.
 */
class TwigExtension extends AbstractExtension {

  /**
   * The template engine.
   *
   * @var \Xylemical\Code\Writer\Twig\TwigWriter
   */
  protected TwigWriter $engine;

  /**
   * TwigExtension constructor.
   *
   * @param \Xylemical\Code\Writer\Twig\TwigWriter $engine
   *   The template engine.
   */
  public function __construct(TwigWriter $engine) {
    $this->engine = $engine;
  }

  /**
   * {@inheritdoc}
   */
  public function getFilters() {
    return [
      new TwigFilter('namespace', [$this, 'doNamespace']),
      new TwigFilter('alias', [$this, 'doAlias']),
      new TwigFilter('fqn', [$this, 'doFullyQualifiedName']),
      new TwigFilter('name', [$this, 'doName']),
      new TwigFilter('indent', [$this, 'doIndent']),
      new TwigFilter('outdent', [$this, 'doOutdent']),
      new TwigFilter('constants', [$this, 'doConstants']),
      new TwigFilter('properties', [$this, 'doProperties']),
      new TwigFilter('methods', [$this, 'doMethods']),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getTests() {
    return [
      new TwigTest('constant', function ($item) {
        return $item instanceof Constant;
      }),
      new TwigTest('contract', function ($item) {
        return $item instanceof Contract;
      }),
      new TwigTest('file', function ($item) {
        return $item instanceof File;
      }),
      new TwigTest('method', function ($item) {
        return $item instanceof Method;
      }),
      new TwigTest('mixin', function ($item) {
        return $item instanceof Mixin;
      }),
      new TwigTest('parameter', function ($item) {
        return $item instanceof Parameter;
      }),
      new TwigTest('property', function ($item) {
        return $item instanceof Property;
      }),
      new TwigTest('structure', function ($item) {
        return $item instanceof Structure;
      }),
    ];
  }

  /**
   * Generate a namespace from a fully qualified name.
   *
   * @param \Xylemical\Code\FullyQualifiedName $name
   *   The fully qualified name.
   *
   * @return string
   *   The namespace.
   */
  public function doNamespace(FullyQualifiedName $name): string {
    return $name->getNamespace();
  }

  /**
   * Generate an alias from a fully qualified name.
   *
   * @param \Xylemical\Code\FullyQualifiedName $name
   *   The fully qualified name.
   *
   * @return string
   *   The alias.
   */
  public function doAlias(FullyQualifiedName $name): string {
    return $name->getShorthand();
  }

  /**
   * Generate a fully qualified name from a fully qualified name.
   *
   * @param \Xylemical\Code\FullyQualifiedName $name
   *   The fully qualified name.
   *
   * @return string
   *   The fully qualified name.
   */
  public function doFullyQualifiedName(FullyQualifiedName $name): string {
    return (string) $name;
  }

  /**
   * Generate a name from a fully qualified name.
   *
   * @param \Xylemical\Code\FullyQualifiedName $name
   *   The fully qualified name.
   *
   * @return string
   *   The name.
   */
  public function doName(FullyQualifiedName $name): string {
    return $name->getName();
  }

  /**
   * Indents text by amount.
   *
   * @param string $text
   *   The text to indent.
   * @param int $amount
   *   The amount to indent.
   * @param int $levels
   *   The number of levels to indent.
   * @param bool $ignoreFirst
   *   To ignore the first line on indent.
   *
   * @return string
   *   The indented text.
   */
  public function doIndent(string $text, int $amount = 2, int $levels = 1, bool $ignoreFirst = TRUE): string {
    $options = [
      'spaces' => $amount,
      'levels' => $levels,
      'ignoreFirst' => $ignoreFirst,
    ];
    return Indenter::indent($text, $options);
  }

  /**
   * Outdents text by amount.
   *
   * @param string $text
   *   The text to outdent.
   * @param int $amount
   *   The amount to outdent.
   * @param int $levels
   *   The levels to outdent.
   * @param bool $ignoreFirst
   *   To ignore first line on outdent.
   *
   * @return string
   *   The outdented text.
   */
  public function doOutdent(string $text, int $amount = 2, int $levels = 1, bool $ignoreFirst = TRUE): string {
    $options = [
      'spaces' => $amount,
      'levels' => $levels,
      'ignoreFirst' => $ignoreFirst,
    ];
    return Indenter::outdent($text, $options);
  }

  /**
   * Filter out the constants.
   *
   * @param \Xylemical\Code\Definition\ElementInterface[] $elements
   *   The elements.
   *
   * @return \Xylemical\Code\Definition\Constant[]
   *   The constants.
   */
  public function doConstants(array $elements): array {
    return array_filter($elements, function ($element) {
      return $element instanceof Constant;
    });
  }

  /**
   * Filter out the properties.
   *
   * @param \Xylemical\Code\Definition\ElementInterface[] $elements
   *   The elements.
   *
   * @return \Xylemical\Code\Definition\Property[]
   *   The properties.
   */
  public function doProperties(array $elements): array {
    return array_filter($elements, function ($element) {
      return $element instanceof Property;
    });
  }

  /**
   * Filter out the methods.
   *
   * @param \Xylemical\Code\Definition\ElementInterface[] $elements
   *   The elements.
   *
   * @return \Xylemical\Code\Definition\Method[]
   *   The methods.
   */
  public function doMethods(array $elements): array {
    return array_filter($elements, function ($element) {
      return $element instanceof Method;
    });
  }

}
