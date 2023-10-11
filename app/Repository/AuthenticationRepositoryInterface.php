<?php

namespace App\Repository;

interface AuthenticationRepositoryInterface
{
    public function signIn($email, $password);
    public function signOut();
}