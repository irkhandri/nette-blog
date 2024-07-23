<?php

declare(strict_types=1);

namespace App\Module\Blog\Presenters;

use App\Model\BlogFacade;
use App\Model\User;
use App\Model\UserFacade;
use Doctrine\ORM\EntityManagerInterface;
use Nette;
use Nette\Application\UI\Form;

final class UserPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private UserFacade $facade,
	) 
    {}

    public function renderProfile(): void
    {   
        $user = $this->facade->findUser($this->getUser()->getId());
        $this->template->profile = $user;
        $this->template->interests = $user->getInterests();
        $this->template->comments = $user->getComments();

    }



    public function renderProfiles(): void
    {
        $profiles = $this->facade->getAll();
        $this->template->profiles = $profiles;
    }

    public function renderShow(int $id): void
    {
        $profile = $this->facade->findUser($id);
        $this->template->profile = $profile;

    }



}