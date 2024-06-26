<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\EntityManagerInterface;

require_once __DIR__ . '/vendor/autoload.php';

$container = \App\Bootstrap::boot()->createContainer();
$entityManager = $container->getByType(EntityManagerInterface::class);

return ConsoleRunner::createApplication(new \Symfony\Component\Console\Helper\HelperSet(), [new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand()]);
