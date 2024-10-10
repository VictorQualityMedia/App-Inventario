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

// Aunque reciba el nombre de LoginController, controla tanto el login como el registro de los usuarios y el cierre de sesión. Esto para englobar
// las funcionalidades similares y evitar las divisiones innecesarias.
class PruebaController extends AbstractController
{
    
    // Controlador del login, se encarga de redirigir a home, o a la propia página del login dependiendo de si el usuario se ha logeado de forma
    // correcta o no.
    #[Route("/prueba", name: "prueba")]
    public function prueba(Request $request)
    {
        return $this->render('error.html.twig', [
            "titulo" => "¡Whoops!",
            "mensaje" => "¡Ha habido un fallo!"
        ]);
    }

}