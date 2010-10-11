<?php

namespace Neuron;

/**
 * Upload control
 */
class UploadControl extends BaseControl
{
	private $handler;

	private $redirectUri;



	public function setHandler($handler)
	{
		$this->handler = $handler;
	}



	public function setRedirectUri($redirectUri)
	{
		$this->redirectUri = $redirectUri;
	}



	public function handleUpload()
	{
		$files = \Nette\Environment::getHttpRequest()->getFile("user_file");
		foreach ($files as $file) {
			call_user_func($this->handler, $file);
		}
	}



	protected function createComponentUploadForm($name)
	{
		$form = new \Nette\Application\AppForm($this, $name);
		$form->addFile("file");

		$handler = $this->handler;
		$redirectUri = $this->redirectUri;
		$control = $this;

		$form->addSubmit("s")->onClick[] = function ($btn) use ($control, $handler, $redirectUri) {
			$handler($btn->form["file"]->value);
			if ($redirectUri) {
				$control->presenter->redirectUri($redirectUri);
			}
		};
	}



	/**
	 * Render control
	 */
	public function render()
	{
		$this->template->redirectUri = $this->redirectUri;
		$this->template->render();
	}

}