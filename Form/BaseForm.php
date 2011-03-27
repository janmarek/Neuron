<?php

namespace Neuron\Form;

/**
 * @property-read \Nette\Context $context
 */
abstract class BaseForm extends \Nette\Application\AppForm
{
	private $template;

	private $successFlashMessage;

	private $redirect;

	private $handler;
	
	protected $csrfProtection = TRUE;
	


	public function __construct(\Nette\IComponentContainer $parent = NULL, $name = NULL)
	{
		parent::__construct($parent, $name);
		
		if ($this->csrfProtection) {
			$this->addProtection("Prosím odešlete formulář znovu, vypršel bezpečnostní token.");
		}

		$this->addFields();
		$this->addSubmitButtons();

		$this->onSubmit[] = array($this, "handleSubmit");

		$this->init();
	}



	protected function init()
	{

	}


	public function getSuccessFlashMessage()
	{
		return $this->successFlashMessage;
	}



	public function setSuccessFlashMessage($successFlashMessage)
	{
		$this->successFlashMessage = $successFlashMessage;
	}



	public function setRedirect()
	{
		$this->redirect = func_get_args();
	}



	public function addDatePicker($name, $label = NULL, $cols = NULL, $maxLength = NULL)
	{
		return $this[$name] = new DatePicker($label, $cols, $maxLength);
	}


	public function addTexyla($name, $label = null, $cols = 40, $rows = 10)
	{
		return $this->addTextArea($name, $label, $cols, $rows)->setAttribute("class", "texyla");
	}


	public function addEntitySelect($name, $label = null, array $items = null, $idKey = 'id', $nameKey = 'name')
	{
		return $this[$name] = new EntitySelect($label, $items, $idKey, $nameKey);
	}


	public function addEntityMultiSelect($name, $label = null, array $items = null, $size = null, $idKey = 'id', $nameKey = 'name')
	{
		return $this[$name] = new EntityMultiSelect($label, $items, $size, $idKey, $nameKey);
	}



	protected function getTemplateFile()
	{
		$reflection = $this->getReflection();
		return dirname($reflection->getFileName()) . "/" . $reflection->getShortName() . ".phtml";
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
		$presenter = $this->getPresenter();

		try {
			$this->handler($values);

			if ($this->successFlashMessage) {
				$presenter->flashMessage($this->successFlashMessage);
			}

			if ($this->redirect) {
				call_user_func_array(array($presenter, "redirect"), $this->redirect);
			}

		} catch (\Neuron\Model\ValidationException $e) {
			if ($e->getPropertyPath() && isset($this[$e->getPropertyPath()])) {
				$this[$e->getPropertyPath()]->addError($e->getMessage());
			} else {
				$this->addError($e->getMessage());
			}
		}
	}



	public function setHandler($handler)
	{
		if (!is_callable($handler)) {
			throw new \InvalidArgumentException;
		}

		$this->handler = $handler;
	}



	protected function handler($values)
	{
		if (!$this->handler) {
			throw new \InvalidStateException;
		}

		call_user_func($this->handler, $values);
	}



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
		return $this->getPresenter()->getContext();
	}



	public function getService($name)
	{
		return $this->getContext()->getService($name);
	}



	/**
	 * @return \Nette\Web\User
	 */
	public function getUser()
	{
		return $this->getService("Nette\Web\IUser");
	}

}