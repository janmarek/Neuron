<?php

namespace Symfony\Component\Validator\Constraints;

/**
 * @author Roman Sklenář, Jan Marek
 * @link https://gist.github.com/664454
 */
class Unique extends \Symfony\Component\Validator\Constraint
{
	public $message = 'This value should be unique';
}
