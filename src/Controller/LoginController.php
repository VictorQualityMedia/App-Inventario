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
class LoginController extends AbstractController
{
    
    // Controlador del login, se encarga de redirigir a home, o a la propia página del login dependiendo de si el usuario se ha logeado de forma
    // correcta o no.
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

    // Redirige a la página del registro.
    #[Route("/registro", name: "registro")]
    public function registro()
    {
        return $this->render('registro.html.twig');
    }

    // Se encarga de recibir los datos del registro y procesar la creación de un nuevo usuario, el usuario es estándar por defecto.
    #[Route("/procesar_registro", name: "procesar_registro")]
    public function procesar_registro(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em)
    {
        $repo_usuario = $em->getRepository(Usuario::class);
        $usuario = $request->request->get("email");
        $contrasena = $request->request->get("contrasena");

        // Creación de un nuevo usuario
        $nuevo_usuario = new Usuario();
        $nuevo_usuario->setEmail($usuario);

        // Hashear la contraseña correctamente
        $contrasena_hasheada = $passwordHasher->hashPassword($nuevo_usuario, $contrasena); // Aquí el cambio
        $nuevo_usuario->setContrasena($contrasena_hasheada);
        // El usuario es estándar por defecto.
        $nuevo_usuario->setRol(0);

        // Persistir el nuevo usuario en la base de datos
        $em->persist($nuevo_usuario); // No olvides persistir el nuevo usuario
        $em->flush();

        return $this->render('error.html.twig', [
            "titulo" => "¡Genial!",
            "mensaje" => "¡El usuario ha sido registrado con éxito!"
        ]);
    }

    #[Route("/cerrar_sesion", name: "ctrl_logout")]
    public function cerrar_sesion(AuthenticationUtils $authenticationUtils, SessionInterface $session) {
        // Este controlador nunca será ejecutado, Symfony lo maneja automáticamente
        throw new \Exception('Hay un problema con el logout, el controlador ha colapsado.');
    }
}