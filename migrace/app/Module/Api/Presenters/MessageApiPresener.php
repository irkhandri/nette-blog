<?php

declare(strict_types=1);

namespace App\Module\Api\Presenters;

use App\Model\Message;
use App\Model\User;
use App\Module\Api\Presenters\ApiPresenter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Nette;
use Nette\Application\UI\Form;
use JMS\Serializer\SerializerBuilder;

final class MessageApiPresenter extends ApiPresenter
{

    private $messageRepo;

    public function __construct(
        EntityManagerInterface $em,
    )
    {
        parent::__construct($em, SerializerBuilder::create());
        $this->messageRepo = $em->getRepository(Message::class);
    }


    public function actionCreate()
    {
        $request=$this->getHttpRequest();
        
        $recipient_id=$request->getQuery('recipient_id');
        $jwtToken = $request->getHeader('bearer');


        try {
            $decodedToken = JWT::decode($jwtToken, new Key($this->jwtSecret, 'HS256'));
            $userId = $decodedToken->sub;

        } catch (\Exception $e) {
            $this->sendJsonError('Invalid token', 401);
            return;
        }

        $data=json_decode($request->getRawBody(), true);


        $recipient = $this->em->find(User::class, $recipient_id);
        $sender = $this->em->find(User::class, $userId);

        if (!$recipient || !$sender){
            $this->sendJsonError('User doesnt exist');
            return;
        }

        if ( !isset($data['subject']) || !isset($data['content'])) {
            $this->sendJsonError('Invalid input', 400);
            return;
        }
        $message = new Message();
        $message->setSubject($data['subject']);
        $message->setContent($data['content']);
        $message->setReciever($recipient);
        $message->setSender($sender);
        $sender->addOutMessages($message);
        $recipient->addInMessages($message);

        $this->em->persist($message);
        $this->em->flush();


        $this->sendJson("Send successffull;;;");


    }




}