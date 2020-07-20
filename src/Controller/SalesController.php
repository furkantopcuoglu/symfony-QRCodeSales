<?php

namespace App\Controller;

use App\Entity\User;
use App\Logic\SalesLogic;
use App\Repository\ProductRepository;
use App\Repository\SalesRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SalesController extends AbstractController
{
    /**
     * @Route("/satinal", name="satinal", methods={"GET","POST"})
     * @param UserRepository $userRepository
     * @param SalesLogic $salesLogic
     * @param Request $request
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function newAddSales(UserRepository $userRepository, SalesLogic $salesLogic, Request $request, ProductRepository $productRepository): Response
    {
        $user_id = $request->request->get('user_id');
        $product_id = $request->request->get('product_id');
        $kredi = $request->request->get('kredi');
        if (null != $user_id && $product_id && $kredi) {
            // Seçilen user'ın yeterli düzeyde kredisi olup olmadığının kontrolü
            $user = $userRepository->find($user_id);
            $userKredi = $user->getKredi();
            if ($userKredi >= $kredi) {
                //SalesLogic ile yeni satın alma işlemi
                $salesLogic->addNewSales();

                return $this->redirectToRoute('satislar');
            } else {
                // Kullanıcının yeterli düzeyde kredisi yoksa
                // Kullanıcıya ne kadar daha kredi gerektiği gösteriliyor.
                $gerekenKredi = $kredi - $userKredi;

                return new Response('Kullanıcının Yeterli düzeyde kredisi yok '.$gerekenKredi.' krediye daha ihtiyacı var.');
            }
        }

        return $this->render('/satin-al.html.twig', [
            'product' => $productRepository->findAll(),
            'user' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/satislar", name="satislar", methods={"GET","POST"})
     * @param SalesRepository $salesRepository
     * @return Response
     */
    public function listSales(SalesRepository $salesRepository): Response
    {
        $salesAll = $salesRepository->findAll();
        foreach ($salesAll as $sales) {
            $salesAllShow = [
                'id' => $sales->getId(),
                'user' => $sales->getUser()->getUsername(),
                'product' => $sales->getProduct()->getName(),
                'salesprice' => $sales->getSalePrice(),
                'qrcode' => $sales->getQrCode(),
            ];

            $salesList[] = $salesAllShow;
        }
        if (null == $salesAll) {
            return new Response('Veri Bulunamadı');
        }

        return $this->render('/satislar.html.twig', [
            'sales' => $salesList,
        ]);
    }
}
