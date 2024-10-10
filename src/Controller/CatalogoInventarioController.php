<?php

namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTimeZone;
use App\Entity\Usuario;
use App\Entity\Producto;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

// Aunque reciba el nombre de LoginController, controla tanto el login como el registro de los usuarios y el cierre de sesión. Esto para englobar
// las funcionalidades similares y evitar las divisiones innecesarias.
class CatalogoInventarioController extends AbstractController
{
    
    // Controlador del login, se encarga de redirigir a home, o a la propia página del login dependiendo de si el usuario se ha logeado de forma
    // correcta o no.
    #[Route("/mostrar_inventario", name: "mostrar_inventario")]
    public function mostrar_inventario(Request $request, EntityManagerInterface $em)
    {
        $productos = $em->getRepository(Producto::class)->findAll();
        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('catalogo_inventario.html.twig', [
            "productos" => $productos
        ]);
    }

    #[Route("/procesar_dar_info", name: "procesar_dar_info")]
    public function procesar_dar_info(Request $request, EntityManagerInterface $em)
    {
        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('catalogo_inventario.html.twig');
    }

    #[Route("/procesar_solicitud", name: "procesar_solicitud")]
    public function procesar_solicitud(Request $request, EntityManagerInterface $em)
    {
        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('catalogo_inventario.html.twig');
    }
}