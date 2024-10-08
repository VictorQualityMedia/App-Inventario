<?php

namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTimeZone;
use App\Entity\Usuario;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

class LoginController extends AbstractController
{
    
    #[Route("/", name: "ctrl_login")]
    public function login(AuthenticationUtils $authenticationUtils, SessionInterface $session)
    {
        if (!$this->getUser()) {
            return $this->render('login.html.twig');
        } 
        else {
            return $this->render('inicio.html.twig');
        }
    }

    #[Route("/registro", name: "registro")]
    public function registro()
    {
        return $this->render('registro.html.twig');
    }

    // EXISTE UN BUG CON LAS ENTIDADES DEL USUARIO, REVISAR Y SUSTITUIR POR UNA VÁLIDA
    #[Route("/procesar_registro", name: "procesar_registro")]
    public function procesar_registro(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em)
    {
        $repo_usuario = $em->getRepository(Usuario::class);
        $usuario = $request->request->get("email");
        $contrasena = $request->request->get("contrasena");

        // Creación de un nuevo usuario
        $nuevo_usuario = new Usuario();
        $nuevo_usuario->setEmail($usuario);
        $contrasena_hasheada = $passwordHasher->hashPassword($contrasena);
        $nuevo_usuario->setContrasena($contrasena_hasheada);
        $em->flush();


        return $this->render('inicio.html.twig', ["mensaje" => "Usuario: " . $usuario . " | Contraseña: " . $contrasena]);
    }
}