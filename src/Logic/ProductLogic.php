<?php

namespace App\Logic;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class ProductLogic
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * UserLogic constructor.
     * @param EntityManagerInterface $entityManager
     * @param RequestStack $requestStack
     */
    public function __construct(EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    public function addNewProduct()
    {
        $request = $this->requestStack->getCurrentRequest();
        $entityManager = $this->entityManager;
        //Requestten gelen verileri okuyoruz.
        $name = $request->request->get('name');
        // Yeni Ürün Kaydı
        $product = new Product();
        $product
            ->setName($name);
        $entityManager->persist($product);
        $entityManager->flush();
    }

}
