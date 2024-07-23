<?php

declare(strict_types=1);

namespace App\Module\Api\Presenters;

use App\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use JMS\Serializer\SerializerBuilder;
use Nette\Utils\Json;
use Nette\Security\Passwords;

final class RegistrationPresenter extends ApiPresenter
{
    private $userRepo;
    // private $jwtSecret;
    private $passwords;
    // private $em;

    public function __construct(
        EntityManagerInterface $em,
        Passwords $passwords
        )
    {
        parent::__construct($em, SerializerBuilder::create());
        $this->userRepo = $em->getRepository(User::class);
        // $this->jwtSecret = '123123123';
        $this->passwords = $passwords;
        $this->em = $em;
    }

    public function actionCreate()
    {
        $request = $this->getHttpRequest();

        $data = json_decode($request->getRawBody(), true);

        if ( !isset($data['username']) || !isset($data['email']) || !isset($data['password'])) {
            $this->sendJsonError('Invalid input', 400);
            return;
        }

        if ($this->userRepo->findOneBy([ 'username' => $data["username"]])){
            $this->sendJsonError('User with this USERNAME already exists', 400);
            return;
        }


        $user = new User();
        $user->setEmail($data['email']);
        $user->setUsername($data['username']);
        $user->setPasswordHash($this->passwords->hash($data['password']));

        // dd($user);
             
        $this->em->persist($user);
        $this->em->flush();

        $token = $this->generateJwtToken($user);

        $this->sendJson(['token' => $token]);
    }


    public function actionLogin() 
    {
        $request = $this->getHttpRequest();

        $data = json_decode($request->getRawBody(), true);

        if ( !isset($data['username']) || !isset($data['password'])) {
            $this->sendJsonError('Invalid input', 400);
            return;
        }

        $user = $this->userRepo->findOneBy(['username'=>$data['username']]);

                
        if ( !$user || !password_verify($data['password'], $user->getPasswordHash())) {
            $this->sendJsonError('Invalid credentials', 401);
            return;
        }

        // if (!$user || !$userPassword ){
        //     $this->sendJsonError('Wrong username or password', 400);
        //     return;
        // }


        $token = $this->generateJwtToken($user);

        $this->sendJson(['token' => $token]);
    }



    private function generateJwtToken(User $user): string
    {
        $content = [
            'sub' => $user->getId(),
            'username' => $user->getUsername(),
            'iat' => time(),
            'exp' => time() + 3600 * 12 
        ];

        return JWT::encode($content, $this->jwtSecret, 'HS256');
    }

    
}
