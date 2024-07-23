<?php

declare(strict_types=1);

namespace App\Module\Blog\Presenters;

use App\Model\BlogFacade;
use App\Model\Comment;
use App\Model\Interest;
use App\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Nette;
use Nette\Application\UI\Form;

final class CommentPresenter extends Nette\Application\UI\Presenter
{
        private $commentRepo;

        public function __construct(
        private EntityManagerInterface $em,

        ) {
            $this->commentRepo = $this->em->getRepository(Comment::class);
        }



        public function createComponentCommentForm() :Form
        {
                $form = new Form();
                $form->addTextArea('content', 'Content')->setRequired();

                $form->addSubmit('send', 'Add Comment');
                $form->onSuccess[] = $this->commentFormSucceeded(...);
                return $form;
        }

        public function commentFormSucceeded(array $data): void
        {
                $userId = $this->getUser()->getId();
                $commentId = $this->getParameter('commentId');

                
                $comment = new Comment();
                $user = $this->em->getRepository(User::class)->find($userId);
                $comment->setUser($user);
                $user->addComments($comment);
        
                $comment->setContent($data['content']);


                $this->em->persist($comment);
                $this->em->flush();



                $this->flashMessage('Added successfullly');
                $this->redirect('User:profile');

              
        }

       


        // public function actionDelete(int $interestId): void
        // {
        //         $interest = $this->em->find(Interest::class, $interestId);

        //         if (!$interest) 
        //                 $this->error('Interest not found');
                
        //         $this->em->remove($interest);
        //         $this->em->flush();

        //         $this->flashMessage('Interest was deleted successfully.', 'success');
        //         $this->redirect('User:profile');
        // }




}
