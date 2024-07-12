<?php

declare(strict_types=1);

namespace App\Module\Api\Presenters;

use App\Model\Blog;
use App\Model\BlogFacade;
use App\Model\Interest;
use App\Model\User;
use App\Module\Api\Presenters\ApiPresenter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Nette;
use Nette\Application\UI\Form;
use JMS\Serializer\SerializerBuilder;

final class UserApiPresenter extends ApiPresenter
{

    private $userRepo;

    public function __construct(
        EntityManagerInterface $em
    )
    {
        parent::__construct($em, SerializerBuilder::create());
        $this->userRepo = $em->getRepository(User::class);
    }

    /**
     * My own serializer for User model
     */
    // static public function convertUser (User $user): array
    // {
    //     return [
    //         'user_id' => $user->getId(),
    //         'username' => $user->getUsername(),
    //         'email' => $user->getEmail(),
    //         'create_at' => $user->getCreatedAt(),

    //     ];
    // }

    public function actionDefault()
    {
        $users = $this->userRepo->findAll();

        $jsonContent = $this->serializeJson(['users'=>$users], ['default']);

        $this->sendJson(json_decode($jsonContent));
    }

    public function actionShow(string $id)
    {
        $user = $this->userRepo->find($id);
        
        if (!$user)
            $this->sendJsonError("User doesn't exist");
        
        $jsonContent = $this->serializeJson(['user'=>$user], ['default']);

        $this->sendJson(json_decode($jsonContent));

    }




}
