<?php

namespace Neuron\Form;

/**
 * @property-read \Nette\Context $context
 */
abstract class BaseForm extends Nette\Application\AppForm
{
	private $template;


	public function __construct(Nette\IComponentContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		$this->addProtection("Prosím odešlete formulář znovu, vypršel bezpečnostní token.");

		$this->addFields();
		$this->addSubmitButtons();

		$this->onSubmit[] = array($this, "handleSubmit");
	}



	public function addDatePicker($name, $label = NULL, $cols = NULL, $maxLength = NULL)
	{
		return $this[$name] = new DatePicker($label, $cols, $maxLength);
	}



	protected function getTemplateFile()
	{
		return dirname($this->getReflection()->getFileName()) . "/formTemplate.phtml";
	}



	protected function createTemplate()
	{
		return $this->getPresenter()->createTemplate()->setFile($this->getTemplateFile());
	}



	public function getTemplate()
	{
		if (empty($this->template)) {
			$this->template = $this->createTemplate();
		}

		return $this->template;
	}



	public function render()
	{
		// render("begin") or render("end")
		$args = func_get_args();
		if ($args) {
			parent::render($args[0]);
			return;
		}

		$this->getTemplate()->form = $this;
		$this->getTemplate()->render();
	}



	public function handleSubmit()
	{
		$values = $this->getValues();

		try {
			$this->handler($values);
		} catch (Model\ValidationException $e) {
			if ($e->getPropertyPath()) {
				$this[$e->getPropertyPath()]->addError($e->getMessage());
			} else {
				$this->addError($e->getMessage());
			}
		}
	}



	abstract protected function handler($values);



	abstract protected function addFields();



	protected function addSubmitButtons()
	{
		$this->addSubmit("s");
	}



	/**
	 * Get context
	 * @return \Nette\Context
	 */
	public function getContext()
	{
		return $this->getApplication()->getContext();
	}


	
	public function getService($name)
	{
		$this->getContext()->getService($name);
	}



	public function getUser()
	{
		return Nette\Environment::getUser();
	}

}