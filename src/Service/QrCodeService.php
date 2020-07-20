<?php

namespace App\Service;

use Endroid\QrCode\Factory\QrCodeFactoryInterface;

class QrCodeService
{
    /**
     * @var QrCodeFactoryInterface
     */
    private $qrCodeFactory;

    /**
     * @var QrCodePhotoService
     */
    public $qrCodePhotoService;

    /**
     * QrCodeService constructor.
     */
    public function __construct(QrCodePhotoService $qrCodePhotoService, QrCodeFactoryInterface $qrCodeFactory)
    {
        $this->qrCodeFactory = $qrCodeFactory;
        $this->qrCodePhotoService = $qrCodePhotoService;
    }

    /**
     * @var array
     *
     * @ORM\Column(name="options", type="array", nullable=true)
     */
    public $options;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", nullable=true)
     */
    public $text;

    /**
     * @param $createSalesId
     * @param $createSalesUsername
     * @param $createSalesProduct
     * @param $kredi
     *
     * @return string
     */
    public function salesQrCode($createSalesId, $createSalesUsername, $createSalesProduct, $kredi)
    {
        $text = 'İşlem ID= '.$createSalesId.' İşlemi Yapan Kullanıcı= '.$createSalesUsername.
            ' Satılan Ürün= '.$createSalesProduct.' Satış Yapılan Keredi Miktari= '.$kredi.'';
        $options = ['margin' => 0, 'writer' => 'png', 'size' => 720, 'logo_path' => 'logo.png', 'logo_width' => 150, 'logo_height' => 100];
        $qrCode = $this->qrCodeFactory->create($text, $options);
        // qr Code base64 datasi
        $qrCode = $qrCode->writeDataUri();
        // QR Code photo servisi ile base64 data yeni bir
        // png resmi oluşturuyor , Oluşturulan base64 datası servise gönderiliyor.
        $qrCodePhotoService = $this->qrCodePhotoService;

        return $qrCodePhotoService->salesQrCodePhoto($qrCode);
    }
}
