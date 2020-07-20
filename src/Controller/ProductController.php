<?php

namespace App\Controller;

use App\Logic\ProductLogic;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/urun-ekle", name="urun_ekle", methods={"GET","POST"})
     *
     * @param ProductLogic $productLogic
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function newAddProduct(ProductLogic $productLogic, Request $request, ProductRepository $productRepository): Response
    {
        $name = $request->request->get('name');
        if (null != $name) {
            //ProductLogic ile yeni ürün kaydı
            $productLogic->addNewProduct();
        }

        return $this->render('/urun-ekle.html.twig', [
            'product' => $productRepository->findAll(),
        ]);
    }
}
