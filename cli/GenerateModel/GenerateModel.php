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
				new InputOption("output", "o", InputOption::PARAMETER_OPTIONAL, "Output directory"),
				new InputOption("name", "m", InputOption::PARAMETER_REQUIRED, "Model name"),
				new InputOption("application", "p", InputOption::PARAMETER_OPTIONAL, "Application namespace", "Neuron"),
			));
	}

	
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$name = ucfirst($input->getOption("name"));
		$dir = $input->getOption("output") ?: APP_DIR . "/Model/" . $name;
		$namespace = ucfirst($input->getOption("application")) . "\\Model\\" . $name;

		// entity
		$template = $this->createTemplate(__DIR__ . "/entity.phtml");
		$template->name = $name;
		$template->namespace = $namespace;
		$this->tryWriteFile("$dir/$name.php", $template, $output);

		// service
		$template = $this->createTemplate(__DIR__ . "/service.phtml");
		$template->name = $name;
		$template->namespace = $namespace;
		$this->tryWriteFile("$dir/Service.php", $template, $output);

		// finder
		$template = $this->createTemplate(__DIR__ . "/finder.phtml");
		$template->name = $name;
		$template->namespace = $namespace;
		$this->tryWriteFile("$dir/Finder.php", $template, $output);
	}

}