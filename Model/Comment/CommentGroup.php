<?php

namespace Neuron\Model\Comment;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Comment group
 *
 * @author Jan Marek
 *
 * @Entity
 * @Table(name="commentgroup")
 */
class CommentGroup extends \Neuron\Model\BaseEntity
{
	/** @OneToMany(targetEntity="Comment", mappedBy="commentGroup") */
	private $comments;

	/** @Column(type="boolean") */
	private $locked = false;



	public function __construct(array $values = array())
	{
		parent::__construct($values);
		$this->comments = new ArrayCollection;
	}







	public function addComent()
	{


	}



	public function setLocked($locked)
	{
		$this->locked = $locked;
	}



	public function isLocked()
	{
		return $this->locked;
	}



	public function addComment($comment)
	{
		$this->comments->add($comment);
		$comment->setCommentGroup($this);
	}



	public function getComments()
	{
		return $this->comments;
	}

}
