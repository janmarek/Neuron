<?php

namespace Neuron\Form;

/**
 * EntitySelect
 *
 * @author Jan Marek
 */
class EntitySelect extends \Nette\Forms\SelectBox
{
	private $entities = array(), $idKey, $nameKey;



	public function __construct($label = NULL, array $entities = NULL, $idKey = 'id', $nameKey = 'name')
	{
		$this->entities = $entities;
		$this->idKey = $idKey;
		$this->nameKey = $nameKey;

		parent::__construct($label);

		if ($entities !== NULL) {
			$this->setItems($entities);
		}
	}



	public function setItems(array $entities, $useKeys = TRUE)
	{
		$arrItems = array();

		foreach ($entities as $item) {
			$arrItems[$item->{'get' . $this->idKey}()] = $item->{'get' . $this->nameKey}();
		}

		parent::setItems($arrItems);
	}



	public function setValue($value)
	{
		if ($value instanceof \Neuron\Model\BaseEntity) {
			$value = $value->{'get' . $this->idKey}();
		}
		parent::setValue($value);
	}



	public function getValue()
	{
		$back = debug_backtrace();
		if (isset($back[1]["function"]) && isset($back[1]["class"]) && $back[1]["function"] === "getControl" && $back[1]["class"] === "Nette\Forms\SelectBox") {
			return parent::getValue();
		}
		$val = parent::getValue();
		foreach ($this->entities as $item) {
			if ($item->{'get' . $this->idKey}() == $val) {
				return $item;
			}
		}
		return NULL;
	}

}
