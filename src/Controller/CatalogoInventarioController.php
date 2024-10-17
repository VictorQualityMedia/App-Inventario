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

    // Simplemente procesa la solicitud del usuario y pasa los datos del producto seleccionado
    #[Route("/procesar_dar_info", name: "procesar_dar_info")]
    public function procesar_dar_info(Request $request, EntityManagerInterface $em)
    {
        $producto_sn = $request->request->get('sn');
        $producto = $em->getRepository(Producto::class)->find($producto_sn);
        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('info_extendida.html.twig', [
            'producto' => $producto
        ]);
    }

    // Esta ruta crea una solicitud de préstamo por parte del usuario, la orden tendrá el campo de email_prestamista como nulo y el estado
    // "EN ESPERA". Para que dicha orden cambie, el administrador tendrá que validar la solicitud de forma manual.
    #[Route("/procesar_solicitud_ajax", name: "procesar_solicitud_ajax", methods: ["POST"])]
    public function procesarSolicitudAjax(Request $request, EntityManagerInterface $em)
    {
        $data = json_decode($request->getContent(), true); // Obtener el contenido en formato JSON
        $usuario = $this->getUser(); // Usuario logueado
        $productosSeleccionados = $data['productos'];

        $errores = [];
        
        foreach ($productosSeleccionados as $productoData) {
            $snProducto = $productoData['sn'];
            $cantidad = $productoData['cantidad'];
            $evento = $productoData['evento'];

            $producto = $em->getRepository(Producto::class)->find($snProducto);

            if (!$producto) {
                $errores[] = "Producto con SN $snProducto no encontrado.";
                continue;
            }

            if ($cantidad > $producto->getStock()) {
                $errores[] = "No hay suficiente stock para el producto con SN $snProducto.";
                continue;
            }

            // Crear el préstamo
            $prestamo = new Prestamo();
            $prestamo->setCodProducto($producto);
            $prestamo->setCantidad($cantidad);
            $prestamo->setFechaPrestamo(new \DateTime());
            $prestamo->setFechaDevolucion((new \DateTime())->modify('+1 month'));
            $prestamo->setUsuarioPrestamo($usuario);
            $prestamo->setEstado("EN ESPERA");
            if (isset($evento)) {
                $prestamo->setEvento($evento);
            }
            
            // Actualizar el stock del producto
            $producto->setStock($producto->getStock() - $cantidad);
            $producto->setPrestado($producto->getPrestado() + $cantidad);

            // Persistir el préstamo y actualizar el producto
            $em->persist($prestamo);
        }

        $em->flush();

        if (!empty($errores)) {
            return new JsonResponse(['status' => 'error', 'errors' => $errores], 400);
        }

        return new JsonResponse(['status' => 'success'], 200);
    }

}