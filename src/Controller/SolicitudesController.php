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
use App\Entity\Prestamo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

// Aunque reciba el nombre de LoginController, controla tanto el login como el registro de los usuarios y el cierre de sesión. Esto para englobar
// las funcionalidades similares y evitar las divisiones innecesarias.
class SolicitudesController extends AbstractController
{
    
    #[Route("/mostrar_solicitudes", name: "mostrar_solicitudes")]
    public function mostrar_solicitudes(Request $request, EntityManagerInterface $em)
    {
        $prestamos = $em->getRepository(Prestamo::class)->findBy(['estado' => "EN ESPERA"]);
        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('administrar_solicitudes.html.twig', [
            "prestamos" => $prestamos
        ]);
    }

    #[Route("/mostrar_solicitudes_user", name: "mostrar_solicitudes_user")]
    public function mostrar_solicitudes_user(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $prestamos = $em->getRepository(Prestamo::class)->findBy(['usuarioPrestamo' => $user]);
        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('administrar_solicitudes.html.twig', [
            "prestamos" => $prestamos
        ]);
    }

    #[Route("/procesar_aceptar", name: "procesar_aceptar", methods: ["POST"])]
    public function procesarAceptar(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['ids']) || !is_array($data['ids'])) {
            return new JsonResponse(['success' => false, 'message' => 'Datos inválidos'], 400);
        }

        $ids = $data['ids'];

        foreach ($ids as $id) {
            $prestamo = $em->getRepository(Prestamo::class)->find($id);
            if ($prestamo) {
                $prestamo->setEstado('Aceptado');
                $prestamo->setPrestamista($this->getUser());
                // Se puede añadir más lógica si es necesario, como enviar correos, etc.
            }
        }

        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route("/procesar_denegar", name: "procesar_denegar", methods: ["POST"])]
    public function procesarDenegar(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['ids']) || !is_array($data['ids'])) {
            return new JsonResponse(['success' => false, 'message' => 'Datos inválidos'], 400);
        }

        $ids = $data['ids'];

        foreach ($ids as $id) {
            $prestamo = $em->getRepository(Prestamo::class)->find($id);
            if ($prestamo) {
                $prestamo->setEstado('Denegado');
                $prestamo->setPrestamista($this->getUser());

                // Revertir los cambios en el stock y la cantidad prestada
                $producto = $prestamo->getCodProducto();
                if ($producto) {
                    $producto->setStock($producto->getStock() + $prestamo->getCantidad());
                    $producto->setPrestado($producto->getPrestado() - $prestamo->getCantidad());
                }
            }
        }

        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    #[Route("/procesar_modificar_solicitud", name: "procesar_modificar_solicitud")]
    public function procesar_modificar_solicitud(Request $request, EntityManagerInterface $em)
    {
        $prestamos = $em->getRepository(Prestamo::class)->findBy(['estado' => "EN ESPERA"]);
        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('administrar_solicitudes.html.twig', [
            "prestamos" => $prestamos
        ]);
    }

    #[Route("/dirigir_a_editar", name: "dirigir_a_editar")]
    public function dirigir_a_editar(Request $request, EntityManagerInterface $em)
    {
        $id_prestamo = $request->request->get("id_prestamo");
        $productos = $em->getRepository(Producto::class)->findAll();
        $prestamo = $em->getRepository(Prestamo::class)->find($id_prestamo);
        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('editar_prestamo.html.twig', [
            "prestamo" => $prestamo,
            "productos" => $productos
        ]);
    }

}