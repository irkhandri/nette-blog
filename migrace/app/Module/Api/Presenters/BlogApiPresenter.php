<?php

declare(strict_types=1);

namespace App\Module\Api\Presenters;

use App\Model\Blog;
use App\Model\BlogFacade;
use App\Model\Interest;
use App\Model\BlogRepository;
use App\Model\User;
use App\Module\Api\Presenters\ApiPresenter;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Nette;
use Nette\Application\UI\Form;
use JMS\Serializer\SerializerBuilder;

final class BlogApiPresenter extends ApiPresenter
{

    private $blogRepo;
    // private $facade;

    public function __construct(
        // private BlogRepository $blogRepository,
        EntityManagerInterface $em,
        BlogFacade $facade
    )
    {
        parent::__construct($em, SerializerBuilder::create());
        $this->blogRepo = $em->getRepository(Blog::class);
        // $this->facade = $facade;
    }


    /**
     * My own serializer for Blog model
     */
    static public function convertBlog (Blog $blog): array
    {
        return [
                'blog_id' => $blog->getId(),
                'image' => $blog->getImageUrl(),
                'title' => $blog->getTitle(),
                'content' => $blog->getContent(),
                'created_at' => $blog->getCreatedAt(),
        ];
    }




    public function actionDefault($title, $name)
    {
        $request = $this->getHttpRequest();
        $title = $request->getQuery('title');
        $name = $request->getQuery('name');

        if ($title || $name){
            $blogs = $this->searchBlogs($title, $name);
            
        } else 
            $blogs = $this->blogRepo->findAll();

        $jsonContent = $this->serializeJson(["blogs" =>$blogs], ['blog']);


        // With my serializer
        //    $data = [];

        // foreach ($blogs as $blog){
        //     $data['blogs'][] = [
        //         'blog' => $this->convertBlog($blog),
        //         'user' => UserApiPresenter::convertUser($blog->getUser())
        //     ];
        // }
        // $jsonContent = $this->serializeJson($data);


        $this->sendJson(json_decode($jsonContent));
    }


    public function actionShow(string $id)
    {
        
        $blog = $this->blogRepo->find($id);
        
        if (!$blog)
            $this->sendJsonError("Blog doesn't exist");

        $likes = $this->counLikesForBlog($id);
        dd($likes);
        
        $jsonContent = $this->serializeJson(['blog'=>$blog], ['blog','user']);

        $this->sendJson(json_decode($jsonContent));

    }



    public function actionCreate()
    {
        $request = $this->getHttpRequest();

        $jwtToken = $request->getHeader('bearer');

        try {
            $decodedToken = JWT::decode($jwtToken, new Key($this->jwtSecret, 'HS256'));
            $userId = $decodedToken->sub;

        } catch (\Exception $e) {
            $this->sendJsonError('Invalid token', 401);
            return;
        }

        $data = json_decode($request->getRawBody(), true);

        if ( !isset($data['title']) || !isset($data['content'])) {
            $this->sendJsonError('Invalid input', 400);
            return;
        }

        $user = $this->em->find(User::class, $userId);
        
        $blog = new Blog();
        $blog->setTitle($data['title']);
        
        if ($data['imageUrl'] != '')
            $blog->setImageUrl($data['imageUrl']);
        
        $blog->setContent($data['content']);
        $blog->setUser($user);

        $this->em->persist($blog);
        $this->em->flush();
        
        $user->addBlogs($blog);



        // $this->facade->create($data, $userId);
        $this->sendJson(["message" => 'Create Successfful']);

    }




    public function actionDelete(string $id)
    {
        $blog = $this->blogRepo->find($id);

        if (!$blog){
            $this->sendJsonError('Blog does not exist');
            return;
        }

        $request = $this->getHttpRequest();
        $jwtToken = $request->getHeader('bearer');

        try {
            $decodedToken = JWT::decode($jwtToken, new Key($this->jwtSecret, 'HS256'));
            $userId = $decodedToken->sub;

        } catch (\Exception $e) {
            $this->sendJsonError('Invalid token', 401);
            return;
        }

        if ($userId !== $blog->getUser()->getId()){
            $this->sendJsonError('You are not author');
            return;
        } 

        $this->em->remove($blog);
        $this->em->flush();

        $this->sendJson('Deleted successffulllllllyly');

    }


    // get blogs by title, authors name, 
    private function searchBlogs(?string $title, ?string $name)
    {
        $qb = $this->em->createQueryBuilder();
        // $qb->select(array('b', 'u.id', 'u.username', 'u.imageUrl', 'u.email'))
        //     ->from('App\Model\Blog', 'b')
        //     ->innerJoin('b.user', 'u');

        $qb->select('b.id', 'b.imageUrl',  'b.title', 'b.content', 'b.created_at', 'u.username', 'u.email')
            ->from('App\Model\Blog', 'b')
            ->innerJoin('b.user', 'u');

        if ($title){
            $qb->andWhere($qb->expr()->like('b.title', ':title'))
                ->setParameter('title', '%' . $title . '%');
        }

        if ($name) {
            $qb->andWhere($qb->expr()->like('u.username', ':name'))
               ->setParameter('name', '%' . $name . '%');
        }

        $qb->orderBy('b.created_at', 'DESC');

        $query = $qb->getQuery();
        $results = $query->getArrayResult();

        $blogs = array_map(function($result) {
            return [
                'id' => $result['id'],
                'image_url' => $result['imageUrl'],
                'title' => $result['title'],
                'content' => $result['content'],
                'created_at' => $result['created_at'],
                'user' => [
                    'id' => $result['id'],
                    'username' => $result['username'],
                    'email' => $result['email'],
                ],
            ];
        }, $results);


        return $blogs;
    }

    public function counLikesForBlog ($blogId)
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select('COUNT(c.id) as likes_count')
            ->from('App\Model\Blog', 'b')
            ->innerJoin('b.comments', 'c')
            ->where('b.id = :blogId')
            ->andWhere('c.is_liked = :isLiked')
            ->setParameter('blogId', $blogId)
            ->setParameter('isLiked', true);
    
        return $qb->getQuery()->getSingleScalarResult();

        
    }
    


}