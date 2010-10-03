<?php

namespace Nella\Panels;

use Nette\Debug, 
	Nette\String;

/**
 * @author Štěpán Svoboda
 * @author Michael Moravec
 * @author Patrik Votoček
 */
class Doctrine2Panel implements \Doctrine\DBAL\Logging\SQLLogger, \Nette\IDebugPanel
{
	const VERSION = "1.1";
	const TIMER_NAME = "doctrine-query";

	private $queries = array();
	private $totalTime = 0;
	private $i = 0;

	/**
     * {@inheritdoc}
     */
	public function startQuery($sql, array $params = NULL, array $types = NULL)
	{
		$this->queries[$this->i] = array('sql' => $sql, 'params' => $params, 'types' => $types);
		Debug::timer(self::TIMER_NAME);
	}
	
	/**
     * {@inheritdoc}
     */
    public function stopQuery()
    {
    	$executionMS = Debug::timer(self::TIMER_NAME);
        $this->queries[$this->i]['execution'] = round($executionMS * 1000, 3);
        $this->totalTime += ($executionMS * 1000);
        $this->i++;
    }

	public function getPanel()
	{
		if (count($this->queries) == 0)
			return NULL;

		$platform = get_class(\Nette\Environment::getService('Doctrine\ORM\EntityManager')->getConnection()->getDatabasePlatform());
		$platform = substr($platform, strrpos($platform, "\\") + 1, strrpos($platform, "Platform") - (strrpos($platform, "\\") + 1));
		$queries = $this->queries;
		$totalTime = round($this->totalTime, 3);
		$i = 0;
		ob_start();
		require_once __DIR__."/doctrine2.panel.phtml";
		return ob_get_clean();
	}

	public function getTab()
	{
		return '<img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAQAAAC1+jfqAAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAAEYSURBVBgZBcHPio5hGAfg6/2+R980k6wmJgsJ5U/ZOAqbSc2GnXOwUg7BESgLUeIQ1GSjLFnMwsKGGg1qxJRmPM97/1zXFAAAAEADdlfZzr26miup2svnelq7d2aYgt3rebl585wN6+K3I1/9fJe7O/uIePP2SypJkiRJ0vMhr55FLCA3zgIAOK9uQ4MS361ZOSX+OrTvkgINSjS/HIvhjxNNFGgQsbSmabohKDNoUGLohsls6BaiQIMSs2FYmnXdUsygQYmumy3Nhi6igwalDEOJEjPKP7CA2aFNK8Bkyy3fdNCg7r9/fW3jgpVJbDmy5+PB2IYp4MXFelQ7izPrhkPHB+P5/PjhD5gCgCenx+VR/dODEwD+A3T7nqbxwf1HAAAAAElFTkSuQmCC">'.count($this->queries).' queries';
	}

	public function getId()
	{
		return 'doctrine2';
	}

	/**
	 * Prints out a syntax highlighted version of the SQL command.
	 *
	 * @author David Grudl
	 * @param $sql string|DibiResult
	 * @return string
	 */
	public static function dump($sql)
	{
		$keywords1 = 'CREATE\s+TABLE|CREATE(?:\s+UNIQUE)?\s+INDEX|SELECT|UPDATE|INSERT(?:\s+INTO)?|REPLACE(?:\s+INTO)?|DELETE|FROM|WHERE|HAVING|GROUP\s+BY|ORDER\s+BY|LIMIT|SET|VALUES|LEFT\s+JOIN|INNER\s+JOIN|TRUNCATE';
		$keywords2 = 'ALL|DISTINCT|DISTINCTROW|AS|USING|ON|AND|OR|IN|IS|NOT|NULL|LIKE|TRUE|FALSE|INTEGER|CLOB|VARCHAR|DATETIME|TIME|DATE|INT|SMALLINT|BIGINT|BOOL|BOOLEAN|DECIMAL|FLOAT|TEXT|VARCHAR|DEFAULT|AUTOINCREMENT|PRIMARY\s+KEY';

		// insert new lines
		$sql = " $sql ";
		$sql = String::replace($sql, "#(?<=[\\s,(])($keywords1)(?=[\\s,)])#", "\n\$1");
		if (strpos($sql, "CREATE TABLE") !== FALSE)
			$sql = String::replace($sql, "#,\s+#i", ", \n");

		// reduce spaces
		$sql = String::replace($sql, '#[ \t]{2,}#', " ");

		$sql = wordwrap($sql, 100);
		$sql = htmlSpecialChars($sql);
		$sql = String::replace($sql, "#([ \t]*\r?\n){2,}#", "\n");
		$sql = String::replace($sql, "#VARCHAR\\(#", "VARCHAR (");

		// syntax highlight
		$sql = String::replace($sql,
						"#(/\\*.+?\\*/)|(\\*\\*.+?\\*\\*)|(?<=[\\s,(])($keywords1)(?=[\\s,)])|(?<=[\\s,(=])($keywords2)(?=[\\s,)=])#s",
						function ($matches) {
							if (!empty($matches[1])) // comment
								return '<em style="color:gray">'.$matches[1].'</em>';

							if (!empty($matches[2])) // error
								return '<strong style="color:red">'.$matches[2].'</strong>';

							if (!empty($matches[3])) // most important keywords
								return '<strong style="color:blue">'.$matches[3].'</strong>';

							if (!empty($matches[4])) // other keywords
								return '<strong style="color:green">'.$matches[4].'</strong>';
						}
		);
		$sql = trim($sql);
		return '<pre class="dump">'.$sql."</pre>\n";
	}

	/**
	 * Register Doctrine 2 Panel
	 */
	public static function getAndRegister()
	{
		$panel = new static;
		\Nette\Debug::addPanel($panel);
		return $panel;
	}
}