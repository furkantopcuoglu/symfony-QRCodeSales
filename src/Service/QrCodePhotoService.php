<?php

namespace App\Service;

class QrCodePhotoService
{
    /**
     * @param $qrCode
     * @return string
     */
    public function salesQrCodePhoto($qrCode)
    {
        // Gelen Base64 formatında Qrkodu png formatı için düzenliyorum.
        $qrCodeBase64 = str_replace('data:image/png;base64,', '', $qrCode);
        // Düzenlenen Qr kodu base64 decode ederek png data formatına çeviriyorum.
        $qrCode = base64_decode($qrCodeBase64);
        // Rastgele bir uniqid ile png formatında dosya oluşturuyorum.
        $file = uniqid().'.png';
        // Üretilen png dosyasına Qr koddan gelen base64 datasının decode edilmiş png
        // format halini yüklüyorum.
        file_put_contents($file, $qrCode);
        // geriye $file olarak kaydettiğimiz dosyanın uzantısını gönderiyorum.
        return $file;
    }
}
