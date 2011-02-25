<?php

namespace Neuron\Form;

/**
 * EntitySelect
 *
 * @author Jan Marek
 */
class EntityMultiSelect extends \Nette\Forms\MultiSelectBox
{
	private $items = array(), $idKey, $nameKey;



	public function __construct($label = NULL, array $items = NULL, $size = NULL, $idKey = 'id', $nameKey = 'name')
	{
		$this->items = $items;
		$this->idKey = $idKey;
		$this->nameKey = $nameKey;

		parent::__construct($label, NULL, $size);

		if ($items !== NULL) {
			$this->setItems($items);
		}
	}



	public function setItems(array $items, $useKeys = TRUE)
	{
		$arrItems = array();

		foreach ($items as $item) {
			$arrItems[$item->{'get' . $this->idKey}()] = $item->{'get' . $this->nameKey}();
		}

		parent::setItems($arrItems);
	}



	public function setValue($value)
	{
		if ($value === NULL) {
			$value = array();
		}

		$arr = array();

		foreach ($value as $item) {
			if ($value instanceof \Neuron\Model\BaseEntity) {
				$arr[] = $item->{'get' . $this->idKey}();
			} else {
				$arr[] = $item;
			}
		}

		parent::setValue($value);
	}



	public function getValue()
	{
		$keys = parent::getValue();
		$arr = array();

		foreach ($keys as $key) {
			foreach ($this->items as $item) {
				if ($item->{'get' . $this->idKey}() == $key) {
					$arr[] = $item;
				}
			}
		}

		return $arr;
	}

}
