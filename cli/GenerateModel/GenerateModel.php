<?php

namespace Neuron\Console\Command;

use Symfony\Component\Console\Input\InputInterface, Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Generate model
 *
 * @author Jan Marek
 */
class GenerateModel extends AbstractCommand
{
	protected function configure()
	{
		$this
			->setName("neuron:generate-model")
			->setDescription("Generate entity, service and finder stubs.")
			->setHelp("Generate entity, service and finder stubs.")
			->setDefinition(array(
				new InputOption("output", "o", InputOption::VALUE_OPTIONAL, "Output directory"),
				new InputOption("name", "m", InputOption::VALUE_REQUIRED, "Model name"),
				new InputOption("application", "p", InputOption::VALUE_OPTIONAL, "Application namespace", "Neuron"),
			));
	}



	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$name = ucfirst($input->getOption("name"));
		$dir = $input->getOption("output") ?: APP_DIR . "/Model/" . $name;
		$testDir = WWW_DIR . "/tests/Model/$name";
		$namespace = ucfirst($input->getOption("application")) . "\\Model\\" . $name;
		$testNamespace = ucfirst($input->getOption("application")) . '\Tests\Model\\' . $name;

		// entity
		$template = $this->createTemplate(__DIR__ . "/entity.phtml");
		$template->name = $name;
		$template->namespace = $namespace;
		$this->tryWriteFile("$dir/$name.php", $template, $output);

		// entity test
		$template = $this->createTemplate(__DIR__ . "/phpunit.entity.phtml");
		$template->name = $name;
		$template->namespace = $namespace;
		$template->testNamespace = $testNamespace;
		$this->tryWriteFile($testDir . "/" . $name . "Test.php", $template, $output);

		// service
		$template = $this->createTemplate(__DIR__ . "/service.phtml");
		$template->name = $name;
		$template->namespace = $namespace;
		$this->tryWriteFile("$dir/Service.php", $template, $output);

		// service test
		$template = $this->createTemplate(__DIR__ . "/phpunit.service.phtml");
		$template->name = $name;
		$template->namespace = $namespace;
		$template->testNamespace = $testNamespace;
		$this->tryWriteFile("$testDir/ServiceTest.php", $template, $output);

		// finder
		$template = $this->createTemplate(__DIR__ . "/finder.phtml");
		$template->name = $name;
		$template->namespace = $namespace;
		$this->tryWriteFile("$dir/Finder.php", $template, $output);

		// finder test
		$template = $this->createTemplate(__DIR__ . "/phpunit.finder.phtml");
		$template->name = $name;
		$template->namespace = $namespace;
		$template->testNamespace = $testNamespace;
		$this->tryWriteFile("$testDir/FinderTest.php", $template, $output);
	}

}