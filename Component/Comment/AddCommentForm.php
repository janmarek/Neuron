<?php

namespace Neuron\Form;

/**
 * Add comment form
 *
 * @author Jan Marek
 */
class AddCommentForm extends BaseForm
{
	private $commentGroup;

	private $user;



	protected function addFields()
	{
		$this->addTexyla('text')->setRequired('Vyplňte text komentáře.');
	}



	public function handler($values)
	{
		$this->getService('CommentService')->addComment($this->commentGroup, array(
			'text' => $values['text'],
			'author' => $this->user,
		));
	}



	public function setUser($user)
	{
		$this->user = $user;
	}



	public function setCommentGroup($commentGroup)
	{
		$this->commentGroup = $commentGroup;
	}

}
