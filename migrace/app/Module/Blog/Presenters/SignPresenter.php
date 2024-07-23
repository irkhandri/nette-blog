<?php
namespace App\Module\Blog\Presenters;

use App\Model\UserFacade;
use Nette;
use Nette\Application\UI\Form;

final class SignPresenter extends Nette\Application\UI\Presenter
{

    public function __construct(
        private UserFacade $userFacade
    )
    {
        
    }


    protected function createComponentRegisterForm(): Form
	{
		$form = new Form;
		$form->addText('username', 'Username:')
			->setRequired('Please fill username');

        $form->addText('email', 'Email:')
        ->setRequired('Please fill email')
        ->addRule($form::EMAIL, 'Please enter a valid email address.');
        
        $form->addText('imageUrl', 'ImageUrl');


		$form->addPassword('password', 'Password:')
			->setRequired('Please fill password.');

		$form->addSubmit('send', 'SignUp');

		$form->onSuccess[] = $this->registerFormSucceeded(...);
		return $form;
	}


    private function registerFormSucceeded(Form $form, \stdClass $data): void
    {
		Nette\Utils\Validators::assert($data->email, 'email');

        if ($this->userFacade->findByUsername($data->username))
            $form->addError('User already exists');
        else {
            $this->userFacade->add($data);
            $this->getUser()->login($data->username, $data->password);
            $this->redirect('Home:');
        }
    }



	protected function createComponentSignInForm(): Form
	{
		$form = new Form;
		$form->addText('username', 'Username:')
			->setRequired('Please fill username');

		$form->addPassword('password', 'Password:')
			->setRequired('Please fill password.');

		$form->addSubmit('send', 'SignIn');

		$form->onSuccess[] = $this->signInFormSucceeded(...);
		return $form;
	}



    private function signInFormSucceeded(Form $form, \stdClass $data): void
    {
        try {
            $this->getUser()->login($data->username, $data->password);
            $this->redirect('Home:');

        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Wrong username or password');
        }
    }

    public function actionOut(): void
    {
        $this->getUser()->logout();
        $this->flashMessage('Logout success');
        $this->redirect('Home:');
    }



}
