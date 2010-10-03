<?php

/**
 * Nella
 *
 * @copyright  Copyright (c) 2006, 2010 Patrik Votoček
 * @license    http://nellacms.com/license  New BSD License
 * @link       http://nellacms.com
 * @category   Nella
 * @package    Nella
 */

namespace Nella;

use Nette\Environment;

/**
 * Doctrine cache use Nette\Cache
 *
 * @copyright  Copyright (c) 2006, 2010 Patrik Votoček
 * @package    Nella
 */
class NetteDoctrineCache extends \Doctrine\Common\Cache\AbstractCache
{

	/**
	 * @var array $data
	 */
	private $data = array();



	public function __construct()
	{
		$this->data = Environment::getCache('Doctrine');
	}



	/**
	 * {@inheritdoc}
	 */
	public function getIds()
	{
		return array_keys($this->data);
	}



	/**
	 * {@inheritdoc}
	 */
	protected function _doFetch($id)
	{
		if (isset($this->data[$id])) {
			return $this->data[$id];
		}
		return FALSE;
	}



	/**
	 * {@inheritdoc}
	 */
	protected function _doContains($id)
	{
		return isset($this->data[$id]);
	}



	/**
	 * {@inheritdoc}
	 */
	protected function _doSave($id, $data, $lifeTime = 0)
	{
		if ($lifeTime != 0)
			$this->data->save($id, $data, array('expire' => time() + $lifeTime));
		else
			$this->data->save($id, $data);
		return TRUE;
	}



	/**
	 * {@inheritdoc}
	 */
	protected function _doDelete($id)
	{
		unset($this->data[$id]);
		return TRUE;
	}

}