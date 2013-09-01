<?php

namespace RCS\Bundle\DataBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="rcs_user")
 * @ORM\Entity()
 */
class User extends \FOS\UserBundle\Model\User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
    }

    public function setUsername($email)
    {
        $this->username = $email;
        $this->email = $email;
    }

    public function setUsernameCanonical($emailCanonical)
    {
        $this->usernameCanonical = $emailCanonical;
        $this->emailCanonical = $emailCanonical;
    }
}