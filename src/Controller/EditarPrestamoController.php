<?php

namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use DateTimeZone;
use \DateTime; // La barra invertida asegura que estás usando la clase nativa de PHP
use App\Entity\Usuario;
use App\Entity\Producto;
use App\Entity\Prestamo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

// Todas las rutas necesarias para controlar las funciones de editar_prestamo.html.twig
class EditarPrestamoController extends AbstractController
{
    // Esta función es para la función AJAX de la plantilla. Devuelve el stock de un producto.
    #[Route("/obtener_stock_producto", name: "obtener_stock_producto")]
    public function obtener_stock_producto(Request $request, EntityManagerInterface $em)
    {
        $sn = $request->request->get('sn');
        
        // Buscar el producto basado en el 'sn'
        $producto = $em->getRepository(Producto::class)->find($sn);
        
        if ($producto) {
            // Retornar el stock como respuesta en formato JSON
            return new JsonResponse(['stock' => $producto->getStock()]);
        }

        // Si no se encuentra el producto, retornar un error
        return new JsonResponse(['error' => 'Producto no encontrado'], 404);
    }

    // Esta función directamente edita el préstamo con los datos del formulario.
    // Función para editar el préstamo
    #[Route("/editar_prestamo", name: "editar_prestamo")]
    public function editar_prestamo(Request $request, EntityManagerInterface $em)
    {
        // Recolectamos los datos.
        $id_prestamo = $request->request->get('id_prestamo');
        $id_producto = $request->request->get('producto');
        $id_producto_antiguo = $request->request->get('producto_id_antiguo');
        $cantidad = $request->request->get('cantidad');
        $fecha_prestamo_input = $request->request->get('fecha_devolucion');

        // Formatear la fecha desde el formato YYYY-MM-DD
        $fecha_prestamo = DateTime::createFromFormat('Y-m-d', $fecha_prestamo_input);
        
        // Verificar si la fecha es válida
        if (!$fecha_prestamo) {
            // Si la fecha es inválida, puedes lanzar una excepción o manejar el error de alguna otra manera
            return $this->render('error.html.twig', [
                "titulo" => "¡Vaya!",
                "mensaje" => "La fecha proporcionada no es válida. Verifica el formato e intenta de nuevo."
            ]);
        }

        // Buscar el préstamo y el producto basado en el id
        $prestamo = $em->getRepository(Prestamo::class)->find($id_prestamo);
        $producto = $em->getRepository(Producto::class)->find($id_producto);
        $producto_antiguo = $em->getRepository(Producto::class)->find($id_producto_antiguo);

        // Antes de proceder, tenemos que controlar que se elimina el antiguo registro del stock-prestados del producto antiguo para modificar
        // el nuevo
        $producto_antiguo->setStock($producto_antiguo->getStock() + $prestamo->getCantidad());
        $producto_antiguo->setPrestado($producto_antiguo->getPrestado() - $prestamo->getCantidad());
        
        if ($prestamo) {
            // Y antes de finalizar los cambios, debemos actualizar los campos de stock-prestados en el producto correspondiente.
            $producto->setStock($producto->getStock() - $prestamo->getCantidad());
            $producto->setPrestado($producto->getPrestado() + $prestamo->getCantidad());

            // Modificamos los campos
            $prestamo->setCodProducto($producto);
            $prestamo->setCantidad($cantidad);
            $prestamo->setFechaDevolucion($fecha_prestamo);  // Asignar la fecha solo si es válida

            $em->persist($prestamo);
            $em->flush();

            return $this->render('error.html.twig', [
                "titulo" => "¡Genial!",
                "mensaje" => "¡El préstamo ha sido modificado con éxito!"
            ]);
        }

        // Si no se encuentra el producto, retornar un error
        return $this->render('error.html.twig', [
            "titulo" => "¡Vaya!",
            "mensaje" => "No hemos podido editar este préstamo porque no existe, contacta con algún técnico o el responsable de la base de datos."
        ]);
    }

}