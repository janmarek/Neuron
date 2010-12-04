<?php

use Nette\Environment;
use Symfony\Component\Console;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper;
use Doctrine\ORM\Tools\Console\Command as DoctrineCommand;
use Doctrine\DBAL\Tools\Console\Command as DoctrineDBALCommand;
use Neuron\Console\Command as NeuronCommand;

require __DIR__ . '/../../../index.php';

$em = Environment::getService('Doctrine\ORM\EntityManager');
$helperSet = new Console\Helper\HelperSet;
$helperSet->set(new ConnectionHelper($em->getConnection()), 'db');
$helperSet->set(new EntityManagerHelper($em), 'em');

$cli = new Console\Application('Doctrine & Neuron Command Line Interface', Doctrine\ORM\Version::VERSION);
$cli->setCatchExceptions(true);
$cli->setHelperSet($helperSet);
$cli->addCommands(array(
    // DBAL Commands
    new DoctrineDBALCommand\RunSqlCommand(),
    new DoctrineDBALCommand\ImportCommand(),

    // ORM Commands
    new DoctrineCommand\ClearCache\MetadataCommand(),
    new DoctrineCommand\ClearCache\ResultCommand(),
    new DoctrineCommand\ClearCache\QueryCommand(),
    new DoctrineCommand\SchemaTool\CreateCommand(),
    new DoctrineCommand\SchemaTool\UpdateCommand(),
    new DoctrineCommand\SchemaTool\DropCommand(),
    new DoctrineCommand\EnsureProductionSettingsCommand(),
    new DoctrineCommand\ConvertDoctrine1SchemaCommand(),
    new DoctrineCommand\GenerateRepositoriesCommand(),
    new DoctrineCommand\GenerateEntitiesCommand(),
    new DoctrineCommand\GenerateProxiesCommand(),
    new DoctrineCommand\ConvertMappingCommand(),
    new DoctrineCommand\RunDqlCommand(),
    new DoctrineCommand\ValidateSchemaCommand(),

	// Neuron Commands
	new NeuronCommand\GenerateForm(),
	new NeuronCommand\GenerateModel(),
	new NeuronCommand\GenerateCrud(),
	new NeuronCommand\GenerateFrontPresenter(),
));
$cli->run();