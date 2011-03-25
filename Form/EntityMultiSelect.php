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
	
	
	private function prepareItems($value)
	{
		if ($value === NULL) {
			return array();
		}

		$arr = array();

		foreach ($value as $item) {
			if (is_object($item)) {
				$arr[] = $item->{'get' . $this->idKey}();
			} else {
				$arr[] = $item;
			}
		}
		
		//dump($arr);
		
		return $arr;
	}



	public function setValue($value)
	{
		parent::setValue($this->prepareItems($value));
	}



	public function setDefaultValue($value)
	{
		$v = $this->prepareItems($value);
		//dump($v);
		parent::setDefaultValue($v);
	}



	public function getValue()
	{
		$back = debug_backtrace();
		if (isset($back[1]["function"]) && isset($back[1]["class"]) && $back[1]["function"] === "getControl" && $back[1]["class"] === "Nette\Forms\SelectBox") {
			return parent::getValue();
		}
		
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
