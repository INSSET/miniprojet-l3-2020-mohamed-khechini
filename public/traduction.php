<?php

use Symfony\Component\Translation\Loader\XliffFileLoader;
use Symfony\Component\Translation\Translator;

require_once "../vendor/autoload.php";

$translator = new Translator('fr');
$translator->addLoader('xlf', new XliffFileLoader());
$translator->addResource(
    'xlf',
    __DIR__.'/../vendor/symfony/validator/Resources/translations/validators.fr.xlf',
    'fr'
);