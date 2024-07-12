<?php
namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use Nette;

final class BlogFacade
{

    private $blogRepo;
   

    public function __construct(
        private Nette\Database\Explorer $database,
        // private User $user,
        private EntityManagerInterface $em
    )
    {
        $this->blogRepo = $this->em->getRepository(Blog::class);
        
    }



    public function create(array $data, int $userId): void
    {
        // $blog = $this->database
        //     ->table('Blog')
        //     ->insert($data);
        $blog = new Blog();
        $blog->setTitle($data['title']);
        $blog->setContent($data['content']);

        if ($data['imageUrl'] != '' )
            $blog->setImageUrl($data['imageUrl']);

        $user = $this->em->getRepository(User::class)->find($userId);
        
        $blog->setUser($user);
        $user->addBlogs($blog);
        // dd($blog);
        $this->em->persist($blog);
        $this->em->flush();
        

    }



    public function getAll ()
    {
        // $blogRepo = $this->em->getRepository(Blog::class);
        $blogs = $this->blogRepo->findAll();
        return $blogs;

    }

    public function findById(int $id)
    {
        return $this->blogRepo->find($id);
    }


    



    // public function getPublicArticles()
    // {
    //     return $this->database
    //         ->table('Blog')
    //         ->where('created_at < ', new \DateTime )
    //         ->order('created_at DESC');
    // }
}

