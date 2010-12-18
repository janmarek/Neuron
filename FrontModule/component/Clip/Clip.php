<?php

namespace Neuron\Control;

/**
 * Clip
 *
 * @author Jan Marek
 */
class Clip extends \Neuron\BaseControl
{
	public function render($key)
	{
		$clip = $this->getService('ClipService')->findOneByInsertKey($key);

		if ($clip === null) {
			return;
		}

		$this->template->clip = $clip;
		$this->template->render();
	}

}
