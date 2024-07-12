<?php

declare(strict_types=1);

namespace App\Router;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;

		// API

		// comment
		$router->addRoute('/api/blogs/[<blogId>]/comments', 'Api:CommentApi:show');

		$router->addRoute('/api/blogs/[<id>]/create-comment', 'Api:CommentApi:create');


		// blog
		$router->addRoute('/api/blogs', 'Api:BlogApi:default');
		$router->addRoute('/api/blogs/create', 'Api:BlogApi:create');
		$router->addRoute('/api/blogs/[<id>]/delete', 'Api:BlogApi:delete');
		$router->addRoute('/api/blogs/[<id>]', 'Api:BlogApi:show');


		// user
		$router->addRoute('api/users/registration', 'Api:Registration:create');
		$router->addRoute('api/users/auth', 'Api:Registration:login');
		$router->addRoute('/api/users', 'Api:UserApi:default');
		$router->addRoute('/api/users/[<id>]', 'Api:UserApi:show');

		// message
		$router->addRoute('api/create-messege', 'Api:MessageApi:create');

	

		// templates 
		$router->addRoute('/', 'Blog:Home:default');
		$router->addRoute('blog/<action>[/<id>]', 'Blog:Blog:default');
		$router->addRoute('blog/edit[/<blogid>]', 'Blog:Blog:edit');
		

		$router->addRoute('signin', 'Blog:Sign:in');
		$router->addRoute('register', 'Blog:Sign:register');
		$router->addRoute('logout', 'Blog:Sign:out');


		$router->addRoute('/profile/my-profile', 'Blog:User:profile');
		$router->addRoute('/profiles/', 'Blog:User:profiles');
		$router->addRoute('/profiles/[<id>]', 'Blog:User:show');



		$router->addRoute('interest/<action>[/<id>]', 'Blog:Interest:default');

		$router->addRoute('/profile/create-interest', 'Blog:Interest:create');
		$router->addRoute('/profile/edit-interest/[<id>]', 'Blog:Interest:edit');

		
		$router->addRoute('/messages/inbox', 'Blog:Message:inbox');
		$router->addRoute('/messages/outbox', 'Blog:Message:outbox');
		$router->addRoute('/messages-create[/<id>]', 'Blog:Message:create');
		$router->addRoute('/messages/<id>', 'Blog:Message:show');


		return $router;
	}
}
