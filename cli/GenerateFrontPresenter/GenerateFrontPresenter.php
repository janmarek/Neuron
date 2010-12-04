<?php

namespace Neuron\Console\Command;

use Symfony\Component\Console\Input\InputInterface, Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

/**
 * Generate front presenter
 *
 * @author Jan Marek
 */
class GenerateFrontPresenter extends AbstractCommand
{
	protected function configure()
	{
		$this
			->setName("neuron:generate-presenter")
			->setDescription("Generate front presenter.")
			->setHelp("Generate front presenter.")
			->setDefinition(array(
				new InputOption("output", "o", InputOption::VALUE_OPTIONAL, "Output directory"),
				new InputOption("name", "m", InputOption::VALUE_REQUIRED, "Model name"),
				new InputOption("application", "p", InputOption::VALUE_OPTIONAL, "Application namespace", "Neuron"),
			));
	}



	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$name = ucfirst($input->getOption("name"));
		$dir = $input->getOption("output") ?: APP_DIR . "/FrontModule";
		$presenterDir = "$dir/presenters";
		$templatesDir = "$dir/templates";

		$template = $this->createTemplate(__DIR__ . "/presenter.phtml");
		$template->name = $name;
		$template->application = ucfirst($input->getOption("application"));
		$this->tryWriteFile($presenterDir . "/" . $name . "Presenter.php", $template, $output);

		$template = $this->createTemplate(__DIR__ . "/template.phtml");
		$template->name = $name;
		$this->tryWriteFile($templatesDir . "/$name/default.phtml", $template, $output);
	}

}