<?php

namespace Neuron;

/**
 * TemplateHelperLoader
 *
 * @author Jan Marek
 */
class TemplateHelperLoader
{
	protected $helpers = array(
		'texy' => 'Neuron\Texy\TemplateHelper::process',
		'thumbnail' => 'Neuron\Image\ThumbnailHelper::createThumbnail',
		'gravatar' => 'Neuron\Helper\Gravatar::getImageTag',
	);



	public function setHelper($name, $helper)
	{
		if (!is_callable($helper)) {
			throw new \InvalidArgumentException("Helper is not callable.");
		}

		$this->helpers[$name] = $helper;
	}



	public function getHelper($name)
	{
		return isset($this->helpers[$name]) ? $this->helpers[$name] : null;
	}

}