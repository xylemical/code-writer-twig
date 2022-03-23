# Twig Code Writer

Simple writer of code structures using twig templating. 

## Install

The recommended way to install this library is [through composer](http://getcomposer.org).

```sh
composer require xylemical/code-writer-twig
```

## Usage

Example writer:

```php
<?php

use Xylemical\Code\Writer\Twig\TwigWriter;

// Structure as created via xylemical/code documentation.
$class = ...;

$loader = new FilesystemLoader("templates");
$twig = new Environment($loader, ['debug' => TRUE]);
$twig->addExtension(new DebugExtension());
$engine = new TwigWriter($twig);

echo $engine->write($class);
```

## License

MIT, see LICENSE.
