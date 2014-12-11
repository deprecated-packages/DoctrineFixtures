<?php

namespace ZenifyTests\DoctrineFixtures\Entities;

use Doctrine\ORM\Mapping as ORM;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Nette\Object;


/**
 * Class User
 *
 * @ORM\Entity
 * @ORM\Table(name="user")
 *
 * @method  string  getEmail()
 * @method  User    setEmail()
 *
 * @package ZenifyTests\DoctrineFixtures\Entities
 * @author Milan Blažek <blazekm1lan@seznam.cz>
 */
class User extends Object
{

	use Identifier;

	/**
	 * @ORM\Column(type="string", nullable=TRUE)
	 * @var string
	 */
	protected $email;

}
