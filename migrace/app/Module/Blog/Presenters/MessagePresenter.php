<?php

declare(strict_types=1);

namespace App\Module\Blog\Presenters;

use App\Model\Message;
use App\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Nette;
use Nette\Application\UI\Form;

final class MessagePresenter extends Nette\Application\UI\Presenter
{


    public function __construct(
        private EntityManagerInterface $em,

        ) {
        }


    public function renderInbox():void
    {
        $userId = $this->getUser()->getId();
        $user = $this->em->getRepository(User::class)->find($userId);

        $this->template->messages = $user->getInMessages();
        $this->template->page = 'in';

    }

    public function renderOutbox():void
    {
        $userId = $this->getUser()->getId();
        $user = $this->em->getRepository(User::class)->find($userId);

        $this->template->messages = $user->getOutMessages();
        $this->template->page = 'out';

    }


    public function createComponentMessageForm(): Form
    {
        $form = new Form();
        $form->addText('subject', 'Subject')->setRequired();
        $form->addTextArea('content', 'Content')->setRequired();

        $form->addSubmit('send', 'Send Message');
        $form->onSuccess[] = $this->messageFormSucceeded(...);

        return $form;

    }

    public function messageFormSucceeded(array $data): void
    {
        $userId = $this->getUser()->getId();

        $message = new Message();
        $sender = $this->em->getRepository(User::class)->find($userId);

        $message->setSubject($data['subject']);
        $message->setContent($data['content']);
        $message->setSender($sender);

        $recipientId = $this->getParameter('id');
        $recipient = $this->em->getRepository(User::class)->find($recipientId);

        $message->setReciever($recipient);

        $sender->addOutMessages($message);
        $recipient->addInMessages($message);


        $this->em->persist($message);
        $this->em->flush();


        $this->flashMessage('Added successfullly');
        $this->redirect('User:profiles');
        


    }

    public function renderCreate (int $id)
    {

    }


    public function renderShow (int $id)
    {
        $mess = $this->em->find(Message::class, $id);

        if (!$mess)
            $this->error('Message not found');
        
        $mess->setIsRead(true);
        $this->em->persist($mess);
        $this->em->flush();
        
        $this->template->mess = $mess;
       



    }




}