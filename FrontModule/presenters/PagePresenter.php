<?php

namespace Neuron\Presenter\FrontModule;

/**
 * Page presenter
 *
 * @author Jan Marek
 */
class PagePresenter extends \Neuron\Application\BasePresenter
{
	public function actionDefault($id)
	{
		$page = $this->getService("PageService")->find($id);
		$this->template->page = $page;
		$this->template->title = $page->name;
		$this->template->description = $page->description;
	}

}