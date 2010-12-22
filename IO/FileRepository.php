<?php

namespace Neuron\IO;

/**
 * File repository
 *
 * @author Jan Marek
 */
class FileRepository implements IRepository
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
		@mkdir(dirname($path), 0777, true);
		file_put_contents($path, $content);
		return $name;
	}



	public function load($name)
	{
		return file_get_contents("safe://" . $this->getPath($name));
	}



	public function exist($name)
	{
		return file_exists($this->getPath($name));
	}



	public function remove($name)
	{
		return @unlink($this->getPath($name));
	}



	public function getPath($hash)
	{
		// todo validate hash
		$ext = $this->fileExtension ? "." . $this->fileExtension : "";
		return $this->root . "/" . substr($hash, 0, 2) . "/" . substr($hash, 2, 2) . "/" . $hash . $ext;
	}

}
