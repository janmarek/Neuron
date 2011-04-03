<?php

namespace Neuron\Form;

/**
 * Photo form
 *
 * @author Jan Marek
 */
class PhotoForm extends EntityForm
{
	protected function addFields()
	{
		$this->addTextArea("description");
	}



	protected function addSubmitButtons()
	{
		parent::addSubmitButtons();
		$this->addSubmit("saveAndNext");
		$this->addSubmit("prev");
		$this->addSubmit("next");
	}



	protected function handler($values)
	{
		$photo = $this->getEntity();
		$this->getEntityService()->update($photo, $values);

		if ($this["s"]->isSubmittedBy() || $photo->getNextPhoto() === null) {
			$this->setRedirect('default', $photo->getGallery()->getId());
		} elseif ($this['saveAndNext']->isSubmittedBy()) {
			$this->setRedirect('editPhoto', $photo->getNextPhoto()->getId());
		}
	}

}