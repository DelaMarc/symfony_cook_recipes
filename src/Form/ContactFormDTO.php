<?php

namespace App;

use Symfony\Component\Validator\Constraints as Assert;

class ContactFormDTO{

    #[Assert\NotBlank]
    #[Assert\Length(min:3, max:35)]
    public string $name = '';

    #[Assert\NotBlank]
    #[Assert\Email]
    public string $email = '';

    #[Assert\NotBlank]
    #[Assert\Length(min:3, max:350)]
    public string $message = '';

    #[Assert\NotBlank]
    #[Assert\Length(min:3, max:35)]
    public string $service = '';
}

