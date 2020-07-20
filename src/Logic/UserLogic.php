<?php

namespace App\Logic;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class UserLogic
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

    public function addNewUser()
    {
        $request = $this->requestStack->getCurrentRequest();
        $entityManager = $this->entityManager;
        //Requestten gelen verileri okuyoruz.
        $username = $request->request->get('username');
        // Yeni User Kaydı
        $user = new User();
        $user
            ->setUsername($username)
            ->setKredi(0);
        $entityManager->persist($user);
        $entityManager->flush();
    }

    public function updateUserKredi()
    {
        $request = $this->requestStack->getCurrentRequest();
        $entityManager = $this->entityManager;
        //Requestten gelen verileri okuyoruz.
        $id = ((int) $request->request->get('id'));
        $kredi = abs((int) $request->request->get('kredi_miktari'));
        // Kredi Ekleme
        $user = $entityManager->getRepository(User::class)->find($id);
        $userKredi = $user->getKredi();
        $hesapla = abs((int) $userKredi + $kredi);
        $user->setKredi($hesapla);
        $entityManager->flush();
    }
}
