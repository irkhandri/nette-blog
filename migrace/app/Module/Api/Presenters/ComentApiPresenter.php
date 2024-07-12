<?php

declare(strict_types=1);

namespace App\Module\Api\Presenters;

use App\Model\Blog;
use App\Model\Comment;
use App\Model\User;
use App\Module\Api\Presenters\ApiPresenter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Nette;
use Nette\Application\UI\Form;
use JMS\Serializer\SerializerBuilder;

final class CommentApiPresenter extends ApiPresenter
{

    private $commentRepo;

    public function __construct(
        EntityManagerInterface $em,
    )
    {
        parent::__construct($em, SerializerBuilder::create());
        $this->commentRepo = $em->getRepository(Comment::class);
    }


    public function actionShow(string $blogId)
    {
        // $request=$this->getHttpRequest();
        $blog = $this->em->find(Blog::class, $blogId);
        $user = $blog->getUser();
        $comments = $blog->getComments();

        // dd($blog->getComments());

        $jsonConntent = $this->serializeJson(['comments' => $comments], ['comment', 'user']);

        $this->sendJson(json_decode($jsonConntent));

        
    }



    public function actionCreate(string $id)
    {
        $request=$this->getHttpRequest();
        $blog = $this->em->find(Blog::class, $id);

        if (!$blog){
            $this->sendJsonError('Blog does nor exist', 401);
            return;
        }

        $jwtToken = $request->getHeader('bearer');

        try {
            $decodedToken = JWT::decode($jwtToken, new Key($this->jwtSecret, 'HS256'));
            $userId = $decodedToken->sub;

        } catch (\Exception $e) {
            $this->sendJsonError('Invalid token', 401);
            return;
        }

        $data=json_decode($request->getRawBody(), true);

        $user = $this->em->find(User::class, $userId);
        if (!$user){
            $this->sendJsonError('User doesnt exist');
            return;
        }

        if ( !isset($data['content']) || !isset($data['is_liked'])) {
            $this->sendJsonError('Invalid input', 400);
            return;
        }

        $comment = new Comment();
        $comment->setContent($data['content']);
        $result = $data['is_liked'] === '0' ? false : true;
        $comment->setIsLiked($result);
        $comment->setUser($user);
        $comment->setBlog($blog);
        $blog->addComments($comment);
        $user->addComments($comment);

        
        // dd($comment);

        $this->em->persist($comment);
        $this->em->flush();
      

        $this->sendJson("Comment creates  successffull;;;");


    }







}