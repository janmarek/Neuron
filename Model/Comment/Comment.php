<?php

namespace Neuron\Model\Comment;

use DateTime;

/**
 * Comment entity
 *
 * @author Jan Marek
 *
 * @Entity
 * @Table(name="comment")
 */
class Comment extends \Neuron\Model\BaseEntity
{
	/**
	 * @Column(type="text")
	 * @validation:NotBlank
	 * @validation:MaxLength(4000)
	 */
	private $text;

	/** @Column(type="datetime") */
	private $created;

	/** @ManyToOne(targetEntity="\User") */
	private $author;

	/** @ManyToOne(targetEntity="CommentGroup", inversedBy="comments") */
	private $commentGroup;



	public function __construct(array $values = array())
	{
		parent::__construct($values);
		$this->created = new DateTime;
	}



	public function setAuthor($author)
	{
		$this->author = $author;
	}



	public function getAuthor()
	{
		return $this->author;
	}



	public function setCommentGroup($commentGroup)
	{
		$this->commentGroup = $commentGroup;
	}



	public function getCommentGroup()
	{
		return $this->commentGroup;
	}



	public function getCreated()
	{
		return $this->created;
	}



	public function setText($text)
	{
		$this->text = $text;
	}



	public function getText()
	{
		return $this->text;
	}

}