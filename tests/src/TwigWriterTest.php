<?php

namespace Xylemical\Code\Writer\Twig;

use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;
use Xylemical\Code\Language;
use Xylemical\Code\NameManager;
use Xylemical\Code\Writer\TestWriterCase;

/**
 * Tests \Xylemical\Code\Writer\Twig\TwigWriter.
 */
class TwigWriterTest extends TestWriterCase {

  /**
   * Tests the twig templates.
   */
  public function testTwigTemplateEngine(): void {
    $path = realpath(__DIR__ . '/../fixtures');
    $loader = new FilesystemLoader("{$path}/templates");
    $twig = new Environment($loader, ['debug' => TRUE]);
    $twig->addExtension(new DebugExtension());
    $engine = new TwigWriter($twig);

    // Ensure the twig template engine works for all the different variables.
    foreach ($this->getSources($path) as $source => $destination) {
      $manager = new NameManager(new Language());
      $result = $engine->write(include $source);
      $this->assertEquals(file_get_contents($destination), $result, 'Comparing ' . basename($source, '.inc'));
    }
  }

}
