<?php

declare(strict_types=1);

namespace App\Module\Blog\Presenters;

use App\Model\BlogFacade;
use App\Model\Interest;
use App\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Nette;
use Nette\Application\UI\Form;

final class InterestPresenter extends Nette\Application\UI\Presenter
{
        private $interestRepo;

        public function __construct(
        private EntityManagerInterface $em,

        ) {
        $this->interestRepo = $this->em->getRepository(Interest::class);
        }



        public function createComponentInterestForm() :Form
        {
                $form = new Form();
                $form->addText('title', 'Title')->setRequired();
                $form->addTextArea('content', 'Content')->setRequired();

                $form->addSubmit('send', 'Create Interest');
                $form->onSuccess[] = $this->interestFormSucceeded(...);
                return $form;
        }

        public function interestFormSucceeded(array $data): void
        {
                $userId = $this->getUser()->getId();
                $interestId = $this->getParameter('interestId');

                if (!$interestId){
                        $interest = new Interest();
                        $user = $this->em->getRepository(User::class)->find($userId);
                        $interest->setUser($user);
                        $user->addInterest($interest);
                }
                else 
                        $interest = $this->em->find(Interest::class, $interestId);

                
                $interest->setTitle($data['title']);
                $interest->setContent($data['content']);


                $this->em->persist($interest);
                $this->em->flush();



                $this->flashMessage('Added successfullly');
                $this->redirect('User:profile');

              
        }

        public function renderEdit(int $interestId): void
        {

                $interest = $this->em->find(Interest::class, $interestId);

                if (!$interest)
                        $this->error('Interest not found');

                $this['interestForm']->setDefaults([
                        'title' => $interest->getTitle(),
                        'content' => $interest->getContent(),
                        
                ]);

        }


        public function actionDelete(int $interestId): void
        {
                $interest = $this->em->find(Interest::class, $interestId);

                if (!$interest) 
                        $this->error('Interest not found');
                
                $this->em->remove($interest);
                $this->em->flush();

                $this->flashMessage('Interest was deleted successfully.', 'success');
                $this->redirect('User:profile');
        }




}
