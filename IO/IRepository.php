<?php

namespace Neuron\IO;

interface IRepository
{
	public function save($content, $name = null);

	public function load($hash);

	public function exist($name);

	public function remove($name);

}
