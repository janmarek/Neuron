<?php

namespace Neuron\Control;

class Comments extends \Neuron\BaseControl
{
	private $commentGroup;



	public function setCommentGroup($commentGroup)
	{
		$this->commentGroup = $commentGroup;
	}



	public function getCommentGroup()
	{
		return $this->commentGroup;
	}



	public function render()
	{
		if (!$this->commentGroup) {
			return;
		}

		$this->template->comments = $this->getService('CommentService')->getFinder()
			->whereGroup($this->commentGroup)
			->orderByDateDesc()
			->getResult();
		$this->template->showAddForm = !$this->commentGroup->isLocked() && $this->getUser()->isAllowed('comment', 'add');
		$this->template->render();
	}



	protected function createComponentAddForm($name)
	{
		$form = new \Neuron\Form\AddCommentForm($this, $name);
		$user = $this->getService('UserService')->find($this->getUser()->getId());
		$form->setUser($user);
		$form->setCommentGroup($this->getCommentGroup());
		$form->setSuccessFlashMessage('Komentář byl úspěšně přidán.');
		$form->setRedirect('this');
	}

}