<?php

namespace Neuron\Control;

use Nette\Caching\Cache, Nette\Tools, Nette\String, Nette\SafeStream, Nette\Web\Html, Nette\Environment;

/**
 * Feed
 *
 * @author Honza
 */
class Feed extends \Nette\Application\Control
{
	/** @var array */
	private $properties;

	/** @var array */
	private $items = array();

	/** @var string */
	private $linkTitle;

	/** @var string */
	public $folderPath;

	/** @var string */
	public $folderUri;

	/** @var string */
	public $fileName = "rss.xml";

	/** @var array */
	public $onLoadData;

	/** @var array */
	public $cacheOptions = array(
		Cache::EXPIRE => Tools::DAY,
	);

	/** @var array */
	public $propertyElements = array(
		'title', 'link', 'description', 'language', 'copyright', 'skipDays',
		'managingEditor', 'webMaster', 'pubDate', 'lastBuildDate', 'category',
		'generator', 'docs', 'ttl', 'image', 'rating', 'textInput', 'skipHours',
	);

	/** @var array */
	public $itemElements = array(
		'title', 'link', 'description', 'author', 'category', 'comments',
		'enclosure', 'guid', 'pubDate', 'source',
	);

	/**
	 * Write content to file
	 * @param string $path
	 * @param string $content
	 */
	protected function writeFile($path, $content) {
		if (!in_array(SafeStream::PROTOCOL, stream_get_wrappers())) {
			SafeStream::register();
		}

		file_put_contents("safe://" . $path, $content);
	}

	/**
	 * Load data, generate content and write file
	 */
	protected function generateFile() {
		$this->onLoadData($this);

		// check
		if (empty($this->properties["title"]) || empty($this->properties["description"]) || empty($this->properties["link"])) {
			throw new \InvalidStateException("At least one of mandatory properties title, description or link was not set.");
		}

		// render template
		$template = $this->getTemplate()->setFile(__DIR__ . "/rss.phtml");

		$template->channelProperties = $this->properties;
		$template->items = $this->items;

		$this->writeFile($this->folderPath . "/" . $this->fileName, (string) $template);
	}

	/**
	 * Render control
	 */
	public function render()
	{
		$cache = self::getCache();
		$uri = $this->getHref();

		if (!isset($cache[$uri])) {
			$this->generateFile();
			$cache->save($uri, true, $this->cacheOptions);
		}

		echo $this->getElement($uri, $this->getLinkTitle());
	}

	/**
	 * Get feed url
	 * @return string
	 */
	public function getHref()
	{
		return $this->folderUri . $this->fileName;
	}

	/**
	 * Get link element
	 * @param string $uri
	 * @param string $title
	 * @return Html|string
	 */
	public function getElement($uri, $title = null) {
		return Html::el("link")->href($uri)->type("application/rss+xml")->title($title);
	}

	/**
	 * Get cache
	 * @return Cache
	 */
	public static function getCache() {
		return Environment::getCache(__CLASS__);
	}

	/**
	 * Convert date to RFC822
	 * @param string|date $date
	 * @return string
	 */
	public static function prepareDate($date)
	{
		if ($date instanceof \DateTime) {
			$date = $date->getTimestamp();
		}

		if (is_string($date) && $date === (string) (int) $date) {
			$date = (int) $date;
		}

		if (is_string($date) && !String::endsWith($date, "GMT")) {
			$date = strtotime($date);
		}

		if (is_int($date)) {
			$date = gmdate('D, d M Y H:i:s', $date) . " GMT";
		}

		return $date;
	}

	/**
	 * Add item
	 * @param array $item
	 * @see RssControl::$itemElements
	 */
	public function addItem($item) {
		$item = (array) $item;

		if (empty($item["title"]) && empty($item["description"])) {
			throw new \InvalidArgumentException("One of title or description has to be set.");
		}

		// remove not allowed tags
		foreach ($item as $key => $value) {
			if (!in_array($key, $this->itemElements)) {
				unset($item[$key]);
			}
		}

		// guid & link
		if (empty($item["guid"]) && isset($item["link"])) {
			$item["guid"] = $item["link"];
		}

		if (empty($item["link"]) && isset($item["guid"])) {
			$item["link"] = $item["guid"];
		}

		// pubDate
		if (isset($item["pubDate"])) {
			$item["pubDate"] = self::prepareDate($item["pubDate"]);
		}

		$this->items[] = $item;
	}

	/**
	 * Add items
	 * @param array $items
	 */
	public function addItems(array $items) {
		foreach ($items as $item) {
			$this->addItem($item);
		}
	}

	/**
	 * Remove all items
	 */
	public function clearItems() {
		$this->items = array();
	}

	/**
	 * Get items
	 * @return array
	 */
	public function getItems()
	{
		return $this->items;
	}

	/**
	 * Set items
	 * @param array $items
	 */
	public function setItems($items)
	{
		$this->clearItems();
		$this->addItems($items);
	}

	// getters & setters

	/**
	 * Set channel property
	 * @param string $name
	 * @param mixed $value
	 */
	public function setChannelProperty($name, $value)
	{
		if (!in_array($name, $this->propertyElements)) {
			throw new \InvalidArgumentException("Element '$name' is not valid!");
		}

		if ($name === "pubDate" || $name === "lastBuildDate") {
			$value = self::prepareDate($value);
		}

		$this->properties[$name] = $value;
	}

	/**
	 * Get channel property
	 * @param string $name
	 * @return mixed
	 */
	public function getChannelProperty($name)
	{
		return $this->properties[$name];
	}

	/**
	 * Get properties
	 * @return array
	 */
	public function getProperties()
	{
		return $this->properties;
	}

	/**
	 * Set title
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->setChannelProperty("title", $title);
		if (empty($this->linkTitle)) $this->linkTitle = $title;
	}

	/**
	 * Get title
	 * @return string
	 */
	public function getTitle()
	{
		return $this->getChannelProperty("title");
	}

	/**
	 * Set description
	 * @param string $description
	 */
	public function setDescription($description)
	{
		$this->setChannelProperty("description", $description);
	}

	/**
	 * Get description
	 * @return string
	 */
	public function getDescription()
	{
		return $this->getChannelProperty("description");
	}

	/**
	 * Set link
	 * @param string $link
	 */
	public function setLink($link)
	{
		$this->setChannelProperty("link", $link);
	}

	/**
	 * Get link
	 * @return string
	 */
	public function getLink()
	{
		return $this->getChannelProperty("link");
	}

	/**
	 * Get html link title
	 * @return string
	 */
	public function getLinkTitle() {
		return $this->linkTitle;
	}

	/**
	 * Set link title
	 * @param string $linkTitle
	 */
	public function setLinkTitle($linkTitle) {
		$this->linkTitle = $linkTitle;
	}
}