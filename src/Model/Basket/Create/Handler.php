<?php

declare(strict_types=1);

namespace App\Model\Basket\Create;


use App\Entity\Basket;
use App\Entity\Country;
use App\Entity\Product;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;

class Handler
{
    private EntityManagerInterface $entityManager;
    private CountryRepository $countryRepository;

    public function __construct(EntityManagerInterface $entityManager, CountryRepository $countryRepository)
    {
        $this->entityManager = $entityManager;
        $this->countryRepository = $countryRepository;
    }

    public function handle(Command $command): void
    {
        $code = preg_replace('/[0-9]+/', '', $command->tax);

        /** @var $country Country */
        if (!$country = $this->countryRepository->findByCode($code)) {
            throw new \DomainException('Country not found.');
        }

        $product = $this->entityManager->find(Product::class, $command->product);
        $tax = $country->getTax() * 100 / $product->getPrice();

        $basket = new Basket();
        $basket->setSum($tax + $product->getPrice());
        $basket->setProduct($product);
        $this->entityManager->persist($basket);
        $this->entityManager->flush();
    }
}