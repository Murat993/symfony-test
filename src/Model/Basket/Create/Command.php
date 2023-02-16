<?php

declare(strict_types=1);

namespace App\Model\Basket\Create;

use Symfony\Component\Validator\Constraints as Assert;

class Command
{
    /**
     * @Assert\NotBlank()
     */
    public $product;
    /**
     * @Assert\NotBlank()
     * @Assert\Regex(pattern="/([A-Za-z]){2,}\d+/", message="tax doesn't match rules")
     */
    public $tax;

}