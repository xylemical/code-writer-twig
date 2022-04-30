<?php

declare(strict_types=1);

namespace Xylemical\Code\Writer\Twig;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Xylemical\Code\DefinitionInterface;
use Xylemical\Code\Writer\WriterInterface;

/**
 * The twig writer.
 */
class TwigWriter implements WriterInterface {

  /**
   * The twig environment.
   *
   * @var \Twig\Environment
   */
  protected Environment $environment;

  /**
   * TwigTemplate constructor.
   *
   * @param \Twig\Environment $environment
   *   The twig environment.
   */
  public function __construct(Environment $environment) {
    $environment->addExtension(new TwigExtension($this));
    $this->environment = $environment;
  }

  /**
   * {@inheritdoc}
   */
  public function write(DefinitionInterface $definition): string {
    try {
      $template = $this->environment->resolveTemplate($this->getTargets($definition));
      return $this->environment->render($template, $this->getParameters($definition));
    }
    catch (LoaderError | RuntimeError | SyntaxError $e) {
      return '';
    }
  }

  /**
   * Get the class inheritance hierarchy.
   *
   * @param mixed $object
   *   The object.
   *
   * @return string[]
   *   The hierarchy.
   */
  protected function getHierarchy(mixed $object): array {
    return array_merge(
      [get_class($object)],
      class_parents($object),
    );
  }

  /**
   * Gets the template name.
   *
   * @param string $classname
   *   The object.
   *
   * @return string
   *   The template name.
   */
  protected function getTarget(string $classname): string {
    $parts = explode('\\', $classname);
    return strtolower(array_pop($parts));
  }

  /**
   * Get the target templates.
   *
   * @param mixed $object
   *   The object.
   *
   * @return string[]
   *   The targets.
   */
  protected function getTargets(mixed $object): array {
    $targets = [];
    foreach ($this->getHierarchy($object) as $target) {
      $targets[] = $this->getTarget($target) . '.twig';
    }
    return $targets;
  }

  /**
   * Get the parameters for the specific object.
   *
   * @param mixed $object
   *   The object.
   *
   * @return array
   *   The parameters.
   */
  protected function getParameters(mixed $object): array {
    $target = $this->getTarget(get_class($object));
    return [$target => $object];
  }

}
