<?php

namespace App\Controller;

use App\Logic\UserLogic;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/uye-ekle", name="uye_ekle", methods={"GET","POST"})
     * @param Request $request
     * @param UserLogic $userLogic
     * @param UserRepository $userRepository
     * @return Response
     */
    public function newAddUser(Request $request, UserLogic $userLogic, UserRepository $userRepository): Response
    {
        $username = $request->request->get('username');

        if (null != $username) {
            //UserLogic ile yeni üye kaydı.
            $userLogic->addNewUser();
        }

        return $this->render('/user.html.twig', [
            'user' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/uye-kredi", name="uye_kredi", methods={"GET","POST"})
     * @param Request $request
     * @param UserLogic $userLogic
     * @param UserRepository $userRepository
     * @return Response
     */
    public function newAddUserKredi(Request $request, UserLogic $userLogic, UserRepository $userRepository): Response
    {
        $id = $request->request->get('id');
        $kredi = $request->request->get('kredi_miktari');
        if (null != $kredi && $id) {
            //UserLogic ile üye kredi ekleme.
            $userLogic->updateUserKredi();
        }

        return $this->render('/user-kredi.html.twig', [
            'user' => $userRepository->findAll(),
        ]);
    }
}
