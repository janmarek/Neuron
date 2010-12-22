<?php

namespace Neuron\Model;

/**
 * File repository
 *
 * @author Jan Marek
 */
class FileRepository
{
	private $root;

	private $fileExtension;



	public function __construct($root, $ext = "")
	{
		$this->root = $root;
		$this->fileExtension = $ext;
	}



	public function save($content, $name = null)
	{
		$name = $name ?: md5($content);
		$path = $this->getPath($name);
		@mkdir(dirname($path), 0777);
		file_put_contents($path, $content);
		return $name;
	}



	public function load($hash)
	{
		return file_get_contents($this->getPath($hash));
	}



	public function getPath($hash)
	{
		return $this->root . "/" . substr($hash, 0, 2) . "/" . substr($hash, 2, 2) . "." . $this->fileExtension;
	}

}
