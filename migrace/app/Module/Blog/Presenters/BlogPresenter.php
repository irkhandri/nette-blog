<?php

declare(strict_types=1);

namespace App\Module\Blog\Presenters;

use App\Model\Blog;
use App\Model\BlogFacade;
use App\Model\Interest;
use App\Model\User;
use Doctrine\ORM\EntityManagerInterface;
use Nette;
use Nette\Application\UI\Form;
use Nette\Security\User as NetteUser;

final class BlogPresenter extends Nette\Application\UI\Presenter
{



    public function __construct(
		// private Nette\Database\Explorer $database,
        private BlogFacade $facade,
        private EntityManagerInterface $em,
        // private NetteUser $user

	) {
	}
    public function startup(): void
    {
        parent::startup();

        $allowedActions = ['show'];

        if (!$this->getUser()->isLoggedIn() && !in_array($this->getAction(), $allowedActions, true) ) {
            $this->redirect('Sign:in');
        }
    }


    public function createComponentBlogForm(): Form
    {
        $form = new Form();
        $form->addText('title', 'Title')->setRequired();
        $form->addText('imageUrl', 'ImageUrl');
        $form->addTextArea('content', 'Content')->setRequired();

        $form->addSubmit('send', 'Create Blog');
        $form->onSuccess[] = $this->blogFormSucceeded(...);

        return $form;

    }

    public function blogFormSucceeded(array $data): void
    {
        // $user = $this->em->getRepository(User::class)->find($userId);
        // dd($this->facade->findById($id));
        // dd($user);
        $userId = $this->getUser()->getId();
        $blogId = $this->getParameter('blogId');

        if ($blogId){
            $blog = $this->em->find(Blog::class, $blogId);
            $blog->setTitle($data['title']);
            $blog->setContent($data['content']);
            $blog->setImageUrl($data['imageUrl']);

            $this->em->persist($blog);
            $this->em->flush();
        } else {
            $this->facade->create($data, $userId);
        }


       

        $this->flashMessage('Added successfullly');
        $this->redirect('User:profile');

    } 

    public function renderEdit(int $blogId): void
    {
        // $blog = $this->database
        //     ->table('posts')
        //     ->get($postId);

        $blog = $this->em->find(Blog::class, $blogId);
        // dd($blog);
        if (!$blog) {
            $this->error('Blog not found');
        }
    
        $this['blogForm']->setDefaults([
            'title' => $blog->getTitle(),
            'content' => $blog->getContent(),
            'imageUrl' => $blog->getImageUrl(),
        ]);
    }


    public function actionDelete(int $id): void 
    {
        $blog = $this->em->find(Blog::class, $id);

        if (!$blog) {
            $this->error('Blog not found');
        }

        $this->em->remove($blog);
        $this->em->flush();

        $this->flashMessage('Blog post was deleted successfully.', 'success');
        $this->redirect('User:profile');
    }



 
    public function renderShow(int $id): void
    {
        // dd($this->facade->findById($id));
       $this->template->blog = $this->facade->findById($id);

    }    


    public function renderDefault(): void
    {
        $this->template->blogs = $this->facade->getAll();
        // $this->template->blogs = $this->facade
        //     ->getPublicArticles()
        //     ->limit(5);
    }
}