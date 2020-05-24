<?php
namespace App\Traits;

use App\Entity\User;

trait UserOwnedEntity
{
    public function belongsTo(User $user)
    {
        return $this->getUser() === $user;
    }
}
