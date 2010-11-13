<?php

namespace Neuron\Console;

use Symfony\Component\Console\Input\InputInterface, Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

use Nette\Templates\FileTemplate, Nette\Templates\LatteFilter;
use Nette\Reflection\ClassReflection;

use Doctrine\ORM\Mapping\ClassMetadataInfo;

/**
 * FormCreate
 *
 * @author Jan Marek
 */
class GenerateFormCommand extends \Symfony\Component\Console\Command\Command
{
	protected function configure()
	{
		$this
			->setName("neuron:generate-form")
			->setDescription("Generate Neuron\Form\EntityForm class and latte template.")
			->setHelp("Generate Neuron\Form\EntityForm class and latte template.")
			->setDefinition(array(
				new InputOption("output", "o", InputOption::PARAMETER_REQUIRED, "Output directory"),
				new InputOption("entity", "e", InputOption::PARAMETER_REQUIRED, "Entity name"),
			));
	}

	
	
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$dir = $input->getOption("output");
		$name = ClassReflection::from($input->getOption("entity"))->getShortName();

		$em = $this->getHelper("em")->getEntityManager();
		/* @var $em \Doctrine\ORM\EntityManager */

		$config = $em->getClassMetadata($input->getOption("entity"));
		$pk = $config->getSingleIdentifierFieldName();

		foreach ($config->getColumnNames() as $column) {
			$fieldName = $config->getFieldName($column);

			if ($fieldName === $pk) {
				continue;
			}

			$maping = $config->getFieldMapping($fieldName);

			$field = (object) array(
				"name" => $fieldName,
			);

			if ($maping["type"] === "text") {
				$field->inputName = "TextArea";
			} elseif ($maping["type"] === "boolean") {
				$field->inputName = "Checkbox";
			} else {
				$field->inputName = "Text";
			}

			$fields[] = $field;
		}

		foreach ($config->getAssociationMappings() as $maping) {
			$field = (object) null;
			$field->name = $maping["fieldName"];
			$field->inputName = "Select";

			$fields[] = $field;
		}

		$template = $this->createTemplate()->setFile(__DIR__ . "/formClass.phtml");
		$template->name = $name;
		$template->fields = $fields;
		$this->tryWriteFile($dir . '/' . $name . 'Form.php', $template, $output);

		$template = $this->createTemplate()->setFile(__DIR__ . "/formTemplate.phtml");
		$template->fields = $fields;
		$this->tryWriteFile($dir . '/' . $name . 'Form.phtml', $template, $output);
	}

	
	
	protected function createTemplate()
	{
		$template = new FileTemplate;
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
				$output->write("Directory $dir created." . PHP_EOL);
			}

			file_put_contents($path, (string) $content);
			$output->write("File $path created." . PHP_EOL);
		}
	}

}