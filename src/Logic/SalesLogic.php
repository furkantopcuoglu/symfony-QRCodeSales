<?php

namespace App\Logic;

use App\Entity\Product;
use App\Entity\Sales;
use App\Entity\User;
use App\Service\QrCodeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SalesLogic
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
     * @var QrCodeService
     */
    public $qrCodeService;

    /**
     * UserLogic constructor.
     * @param QrCodeService $qrCodeService
     * @param EntityManagerInterface $entityManager
     * @param RequestStack $requestStack
     */
    public function __construct(QrCodeService $qrCodeService, EntityManagerInterface $entityManager, RequestStack $requestStack)
    {
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
        $this->qrCodeService = $qrCodeService;
    }

    public function addNewSales()
    {
        $request = $this->requestStack->getCurrentRequest();
        $entityManager = $this->entityManager;
        //Requestten gelen verileri okuyoruz.
        $user_id = $request->request->get('user_id');
        $product_id = $request->request->get('product_id');
        $kredi = abs((int) $request->request->get('kredi'));

        // Yeni Satış Kaydı
        $sales = new Sales();
        $sales
            ->setUser($entityManager->find(User::class, $user_id))
            ->setProduct($entityManager->find(Product::class, $product_id))
            ->setSalePrice($kredi)
        ;
        $entityManager->persist($sales);
        $entityManager->flush();
        if (null != $sales->getId()) {
            // Userin kredisi Eğer Satış gerçekleşmişse satış sonrası azaltılıyor.
            $user = $entityManager->find(User::class, $user_id);
            $product = $entityManager->find(Product::class, $product_id);
            $userKredi = $user->getKredi();
            $hesapla = ((int) $userKredi - $kredi);
            $user->setKredi($hesapla);
            // Burada qr kod servisini çağırcağız.
            // Satış yapılan bilgiler değişkenlere atanıyor.
            $createSalesId = $sales->getId();
            $createSalesUsername = $user->getUsername();
            $createSalesProduct = $product->getName();
            // QR kod servisi çağrılarak qr kod uretiliyor.
            $qrCodeService = $this->qrCodeService;
            $qrCodeGenerate = $qrCodeService->salesQrCode($createSalesId, $createSalesUsername, $createSalesProduct, $kredi);
            $sales->setQrCode($qrCodeGenerate);
            $entityManager->flush();
        }
    }
}
