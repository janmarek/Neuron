<?php

namespace Neuron\Geo;

/**
 * Point
 *
 * @author Jan Marek
 */
class Point
{
	private $lat, $lng;

	public function __construct($lat, $lng)
	{
		$this->lat = $lat;
		$this->lng = $lng;
	}



	public function getLatitude()
	{
		return $this->lat;
	}



	public function getLongitude()
	{
		return $this->lng;
	}

}
