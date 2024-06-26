<?php

declare(strict_types=1);

namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;
use Nette;
use Nette\Security\Passwords;


/**
 * Users management.
 */
final class UserFacade implements Nette\Security\Authenticator
{
	use Nette\SmartObject;

	public const PasswordMinLength = 7;

	private const
		TableName = 'User',
		ColumnId = 'id',
		ColumnName = 'username',
		ColumnBio = 'bio',
		ColumnImage = 'image_url',
		ColumnPasswordHash = 'password_hash',
		ColumnEmail = 'email',
		ColumnCreated = 'created_at',
		ColumnRole = 'roles',
		DefaultRole = 'user';


	private Nette\Database\Explorer $database;

	private Passwords $passwords;

    private $userRepo;

	public function __construct (
		Nette\Database\Explorer $database, 
		Passwords $passwords,
		private EntityManagerInterface $em

		)
	{
		$this->database = $database;
		$this->passwords = $passwords;
		$this->userRepo = $this->em->getRepository(User::class);
	}


	/**
	 * Performs an authentication.
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(string $username, string $password): Nette\Security\SimpleIdentity
	{
		$row = $this->database->table(self::TableName)
			->where(self::ColumnName, $username)
			->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!$this->passwords->verify($password, $row[self::ColumnPasswordHash])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif ($this->passwords->needsRehash($row[self::ColumnPasswordHash])) {
			$row->update([
				self::ColumnPasswordHash => $this->passwords->hash($password),
			]);
		}

		$arr = $row->toArray();
		unset($arr[self::ColumnPasswordHash]);
		return new Nette\Security\SimpleIdentity($row[self::ColumnId], $row[self::ColumnRole], $arr);
	}


	/**
	 * Adds new user.
	 * @throws DuplicateNameException
	 */
	public function add(\stdClass $data): void
	{
		Nette\Utils\Validators::assert($data->email, 'email');
		try {
			$this->database->table(self::TableName)->insert([
				self::ColumnName => $data->username,
				// self::ColumnBio => $data->bio,
				self::ColumnImage => $data->imageUrl,
				self::ColumnPasswordHash => $this->passwords->hash($data->password),
				self::ColumnEmail => $data->email,
				self::ColumnRole => json_encode(['user']),
				self::ColumnCreated => new \DateTime()
			]);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}

	public function findByUsername (string $username)
	{
		return $this->database->table(self::TableName)
			->where(self::ColumnName, $username)
			->fetch();
	}

	public function findUser (int $id)
	{
		return $this->userRepo->find($id);
	}

	public function getAll()
	{
		$profiles = $this->userRepo->findAll();
		return $profiles;
	}


}



class DuplicateNameException extends \Exception
{
}
