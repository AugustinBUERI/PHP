<?php

namespace App\Controller;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Entity\Order;
use App\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class UserController extends AbstractController 
{
    /**
     * @Route("/user", name="homepage")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
     * @Route("/api/login", name="user_login", methods={"POST"})
     */
    public function log(Request $request): Response
    {
        return new JsonResponse(['message' => 'Invalid inputs, Please check your informations'], 400);
    }

    /**
     * @Route("/api/register", name="user_register", methods={"POST"})
     */
    public function newUser(UserPasswordEncoderInterface  $passwordEncoder, Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();

        $login = $request->request->get('login');
        $password = $request->request->get('password');
        $email = $request->request->get('email');
        $firstname = $request->request->get('firstname');
        $lastname = $request->request->get('lastname');
        
        $user->setLogin($login);
        $user->setPassword($passwordEncoder->encodePassword($user, $password));
        $user->setEmail($email);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
    
        return $this->json(['user' => $user], 200);
    }
}