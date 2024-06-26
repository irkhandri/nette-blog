<?php

declare(strict_types=1);

namespace App\Module\Blog\Presenters;

use App\Model\BlogFacade;
use Nette;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
		// private Nette\Database\Explorer $database,
        private BlogFacade $facade,

	) {
	}

    


    public function renderDefault(): void
    {
        $this->template->blogs = $this->facade->getAll();
        // $this->template->blogs = $this->facade
        //     ->getPublicArticles()
        //     ->limit(5);
    }
}