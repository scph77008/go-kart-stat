<?php

namespace App\Contracts;

interface EntrantInterface
{
    public function getDisplayName(): string;

    public function getId(): int;
}
