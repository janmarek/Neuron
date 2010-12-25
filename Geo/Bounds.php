<?php

namespace Neuron\Geo;

/**
 * Bounds
 *
 * @author Jan Marek
 */
class Bounds
{
	private $maxLat, $minLat, $maxLng, $minLng;



	public function __construct(Point $p1, Point $p2)
	{
		$this->maxLat = max($p1->getLatitude(), $p2->getLatitude());
		$this->minLat = min($p1->getLatitude(), $p2->getLatitude());
		$this->maxLng = max($p1->getLongitude(), $p2->getLongitude());
		$this->minLng = min($p1->getLongitude(), $p2->getLongitude());
	}



	public function extend(Point $point)
	{
		$this->maxLat = max($point->getLatitude(), $this->maxLat);
		$this->minLat = min($point->getLatitude(), $this->minLat);
		$this->maxLng = max($point->getLongitude(), $this->maxLng);
		$this->minLng = min($point->getLongitude(), $this->minLng);
	}



	public function contains(Point $point)
	{
		return
			$point->getLatitude() >= $this->minLat
			&& $point->getLatitude() <= $this->maxLat
			&& $point->getLongitude() >= $this->minLng
			&& $point->getLongitude() <= $this->maxLng;
	}



	public function getNorthEast()
	{

	}



	public function getNorthWest()
	{

	}



	public function getSouthEast()
	{

	}



	public function getSouthWest()
	{

	}

}
