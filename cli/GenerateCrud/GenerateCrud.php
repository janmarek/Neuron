<?php

namespace Neuron\Console\Command;

use Symfony\Component\Console\Input\InputInterface, Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Generate CRUD
 *
 * @author Jan Marek
 */
class GenerateCrud extends AbstractCommand
{
	protected function configure()
	{
		$this
			->setName("neuron:generate-crud")
			->setDescription("Generate admin CRUD presenter.")
			->setHelp("Generate admin CRUD presenter.")
			->setDefinition(array(
				new InputOption("output", "o", InputOption::PARAMETER_OPTIONAL, "Output directory"),
				new InputOption("name", "m", InputOption::PARAMETER_REQUIRED, "Model name"),
				new InputOption("application", "p", InputOption::PARAMETER_OPTIONAL, "Application namespace", "Neuron"),
			));
	}

	
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$name = ucfirst($input->getOption("name"));
		$dir = $input->getOption("output") ?: APP_DIR . "/AdminModule/presenters";

		$template = $this->createTemplate(__DIR__ . "/presenter.phtml");
		$template->name = $name;
		$template->application = ucfirst($input->getOption("application"));
		$this->tryWriteFile($dir . "/" . $name . "Presenter.php", $template, $output);
	}

}