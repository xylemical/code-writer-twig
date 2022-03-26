<?php

namespace Xylemical\Code\Writer\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;
use Xylemical\Code\Definition\Constant;
use Xylemical\Code\Definition\Contract;
use Xylemical\Code\Definition\File;
use Xylemical\Code\Definition\Import;
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
  public function getFunctions() {
    return [
      new TwigFunction('ns', [$this, 'doNs']),
    ];
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
      new TwigTest('import', function ($item) {
        return $item instanceof Import;
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
    return implode(FullyQualifiedName::getSeparator(), $name->getNamespace());
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
    return implode(FullyQualifiedName::getSeparator(), $name->getFullName());
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
   *
   * @return string
   *   The indented text.
   */
  public function doIndent(string $text, int $amount = 2): string {
    return Indenter::defix(Indenter::indent($text, $amount), $amount);
  }

  /**
   * Get the namespace separator.
   *
   * @return string
   *   The separator.
   */
  public function doNs(): string {
    return FullyQualifiedName::getSeparator();
  }

}
