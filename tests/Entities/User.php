<?php

namespace Zenify\DoctrineFixtures\Tests\Entities;

use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User
{

	/**
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @var int
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", nullable=TRUE)
	 * @var string
	 */
	private $email;


	/**
	 * @param $email
	 */
	public function __construct($email)
	{
		$this->email = $email;
	}


	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}


	/**
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

}
