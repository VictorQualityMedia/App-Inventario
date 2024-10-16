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
    
    // Para mostrar las solicitudes del administrador y que este pueda modificarlas.
    #[Route("/mostrar_solicitudes", name: "mostrar_solicitudes")]
    public function mostrar_solicitudes(Request $request, EntityManagerInterface $em)
    {
        $prestamos = $em->getRepository(Prestamo::class)->findBy(['estado' => "EN ESPERA"]);
        
        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('administrar_solicitudes.html.twig', [
            "prestamos" => $prestamos,
            "admin_soli" => 1,
            "calidad_admin" => 1,
            "devolver" => 0,
            "admin_devolver" => 0
        ]);
    }

    // Para que el usuario pueda ver sus solicitudes y ver si está aprobadas o no.
    #[Route("/mostrar_solicitudes_user", name: "mostrar_solicitudes_user")]
    public function mostrar_solicitudes_user(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();
        $prestamos = $em->getRepository(Prestamo::class)->findBy(['usuarioPrestamo' => $user]);

        // Forzar la inicialización de la entidad Usuario
        $em->initializeObject($user);

        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('administrar_solicitudes.html.twig', [
            "prestamos" => $prestamos,
            "admin_soli" => 0,
            "calidad_admin" => 0,
            "devolver" => 0,
            "admin_devolver" => 0
        ]);
    }

    // Para que el usuario pueda ver sus solicitudes y ver si está aprobadas o no.
    #[Route("/mi_material", name: "mi_material")]
    public function mi_material(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        // Forzar la inicialización de la entidad Usuario
        // $em->initializeObject($user);

        $prestamos = $em->getRepository(Prestamo::class)->findBy(['usuarioPrestamo' => $user, 'estado' => "ACEPTADO"]);

        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('administrar_solicitudes.html.twig', [
            "prestamos" => $prestamos,
            "admin_soli" => 0,
            "calidad_admin" => 0,
            "devolver" => 1,
            "admin_devolver" => 0
        ]);
    }

    // Para que el usuario pueda ver sus solicitudes y ver si está aprobadas o no.
    #[Route("/administrar_devoluciones", name: "administrar_devoluciones")]
    public function administrar_devoluciones(Request $request, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        // Forzar la inicialización de la entidad Usuario
        // $em->initializeObject($user);

        $prestamos = $em->getRepository(Prestamo::class)->findBy(['usuarioPrestamo' => $user, 'estado' => "ESPERA DEVOLUCION"]);

        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('administrar_solicitudes.html.twig', [
            "prestamos" => $prestamos,
            "admin_soli" => 0,
            "calidad_admin" => 1,
            "devolver" => 0,
            "admin_devolver" => 1
        ]);
    }

    // Para procesar la aceptación de una solicitud, cambia el estado a ACEPTADO y regsistra el nombre del administrador que lo ha validado.
    #[Route("/procesar_aceptar", name: "procesar_aceptar", methods: ["POST"])]
    public function procesarAceptar(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['ids']) || !is_array($data['ids'])) {
            return new JsonResponse(['success' => false, 'message' => 'Datos inválidos'], 400);
        }
        
        $usuario = $this->getUser(); // Usuario logueado
        $ids = $data["ids"];

        $errores = [];

        foreach ($ids as $id) {
            $prestamo = $em->getRepository(Prestamo::class)->find($id);
            if ($prestamo) {
                $prestamo->setEstado('Aceptado');
                $prestamo->setEmailPrestamista($usuario);
                $em->persist($prestamo);
                // Se puede añadir más lógica si es necesario, como enviar correos, etc.
            }
        }

        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    // Deniega el préstado, al igual que la de aceptar registra el email y cambia el estado, pero de paso también revierte la reserva del stock.
    #[Route("/procesar_denegar", name: "procesar_denegar", methods: ["POST"])]
    public function procesarDenegar(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['ids']) || !is_array($data['ids'])) {
            return new JsonResponse(['success' => false, 'message' => 'Datos inválidos'], 400);
        }

        $usuario = $this->getUser(); // Usuario logueado
        $ids = $data['ids'];

        foreach ($ids as $id) {
            $prestamo = $em->getRepository(Prestamo::class)->find($id);
            if ($prestamo) {
                $prestamo->setEstado('Denegado');
                $prestamo->setEmailPrestamista($usuario);

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

    // Función que permite al usuario devolver el producto o productos, no obstante, el administrador tendrá que confirmar dicha devolución para
    // que sea efectuada
    #[Route("/procesar_devolucion", name: "procesar_devolucion", methods: ["POST"])]
    public function procesar_devolucion(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['ids']) || !is_array($data['ids'])) {
            return new JsonResponse(['success' => false, 'message' => 'Datos inválidos'], 400);
        }

        $usuario = $this->getUser(); // Usuario logueado
        $ids = $data['ids'];

        foreach ($ids as $id) {
            $prestamo = $em->getRepository(Prestamo::class)->find($id);
            if ($prestamo) {
                $prestamo->setEstado('Espera devolucion');
                $prestamo->setEmailPrestamista($usuario);
            }
        }

        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    // Así mismo, esta función permite al administrador confirmar la devolución del usuario.
    #[Route("/procesar_confirmar_devolucion", name: "procesar_confirmar_devolucion", methods: ["POST"])]
    public function procesar_confirmar_devolucion(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['ids']) || !is_array($data['ids'])) {
            return new JsonResponse(['success' => false, 'message' => 'Datos inválidos'], 400);
        }

        $usuario = $this->getUser(); // Usuario logueado
        $ids = $data['ids'];

        foreach ($ids as $id) {
            $prestamo = $em->getRepository(Prestamo::class)->find($id);
            if ($prestamo) {
                $prestamo->setEstado('DEVUELTO');
                $prestamo->setEmailPrestamista($usuario);

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

    // Lleva al adminsitrador a una ventana para modificar los registros.
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