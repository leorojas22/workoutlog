<?php
namespace App\Service\Entity;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    private $passwordEncoder;
    private $jwt;
    private $encodeKey;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getEncodeKey()
    {

    }

}
