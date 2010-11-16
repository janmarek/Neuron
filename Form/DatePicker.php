<?php

namespace Neuron\Form;

use DateTime;
use Nette\Forms\IFormControl;

class DatePicker extends \Nette\Forms\TextInput
{
	/** @var string */
	private $format = "j.n.Y";



	public function __construct($label = NULL, $cols = NULL, $maxLength = NULL)
	{
		parent::__construct($label, $cols, $maxLength);
		$this->setAttribute("class", "datepicker");
	}



	public function setValue($value)
	{
		if ($value instanceof DateTime) {
			parent::setValue($value->format($this->format));
		} else {
			parent::setValue($value);
		}
	}



	public function getValue()
	{
		$dateTime = DateTime::createFromFormat($this->format, parent::getValue());
		return $dateTime ?: null;
	}



	public function getFormat()
	{
		return $this->format;
	}



	public function setFormat($format)
	{
		$this->format = $format;
	}



	public static function validateFilled(IFormControl $control)
	{
		return $control->getValue() instanceof DateTime;
	}

}