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
			if ($this->getUser()->isAllowed('clip', 'create')) {
				$this->template->key = $key;
				$this->template->setFile(__DIR__ . '/createClip.phtml')->render();
			}

			return;
		}

		$this->template->clip = $clip;
		$this->template->render();
	}

}
