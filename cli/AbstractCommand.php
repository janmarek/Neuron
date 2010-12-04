<?php

namespace Neuron\Console\Command;

use Symfony\Component\Console\Output\OutputInterface;
use Nette\Templates\FileTemplate, Nette\Templates\LatteFilter;

/**
 * Abstract cli command
 *
 * @author Jan Marek
 */
class AbstractCommand extends \Symfony\Component\Console\Command\Command
{
	protected function createTemplate($file = null)
	{
		$template = new FileTemplate;
		$template->setFile($file);
		$template->registerHelperLoader("Nette\Templates\TemplateHelpers::loader");
		$template->registerHelper('substr', 'substr');
		$filter = new LatteFilter;
		$template->registerFilter($filter);
		return $template;
	}



	protected function tryWriteFile($path, $content, OutputInterface $output)
	{
		if (!file_exists($path)) {
			$dir = pathinfo($path, PATHINFO_DIRNAME);

			if (!file_exists($dir)) {
				mkdir($dir);
				$output->writeln("Directory $dir created.");
			}

			file_put_contents($path, (string) $content);
			$output->writeln("File $path created.");
		}
	}

}