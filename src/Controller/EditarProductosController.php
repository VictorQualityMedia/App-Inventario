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

// Aunque reciba el nombre de LoginController, controla tanto el login como el registro de los usuarios y el cierre de sesión. Esto para englobar
// las funcionalidades similares y evitar las divisiones innecesarias.
class EditarProductosController extends AbstractController
{
    
    #[Route("/mostrar_productos", name: "mostrar_productos")]
    public function mostrar_productos(Request $request, EntityManagerInterface $em)
    {
        $productos = $em->getRepository(Producto::class)->findAll();
        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('administrar_productos.html.twig', [
            "productos" => $productos
        ]);
    }

    // Redirige al usuario a editar un producto
    #[Route("/dirigir_editar_producto", name: "dirigir_editar_producto")]
    public function dirigir_editar_producto(Request $request, EntityManagerInterface $em)
    {
        $producto_sn = $request->request->get('sn');
        $producto = $em->getRepository(Producto::class)->find($producto_sn);
        // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
        return $this->render('editar_producto.html.twig', [
            'producto' => $producto
        ]);
    }

    #[Route("/editar_producto", name: "editar_producto")]
    public function editar_producto(Request $request, EntityManagerInterface $em)
    {
        // Recolectamos los datos.
        $sn = $request->request->get('sn_producto');
        $nombre = $request->request->get('nombre');
        $precio = $request->request->get('precio');
        $fecha_compra_input = $request->request->get('fecha_compra');
        $categoria = $request->request->get('categoria');
        $cantidad = $request->request->get('cantidad');
        $stock = $request->request->get('stock');
        $prestado = $request->request->get('prestado');
        $malgastado = $request->request->get('malgastado');

        // Buscar el producto basado en el id
        $producto = $em->getRepository(Producto::class)->find($sn);

        // Formatear la fecha desde el formato YYYY-MM-DD en caso de que el administrador quiera modificar la fecha.
        if ($fecha_compra_input != "") {
            $fecha_compra = DateTime::createFromFormat('Y-m-d', $fecha_compra_input);
        
            // Verificar si la fecha es válida
            if (!$fecha_compra) {
                // Si la fecha es inválida, puedes lanzar una excepción o manejar el error de alguna otra manera
                return $this->render('error.html.twig', [
                    "titulo" => "¡Vaya!",
                    "mensaje" => "La fecha proporcionada no es válida. Verifica el formato e intenta de nuevo: " . $fecha_compra_input . " | " . $fecha_compra
                ]);
            }
            else {
                $producto->setFechaCompra($fecha_compra);
            }
        }

        // Controlar dependiendo de lo que introduzca, guardar los cambios
        if ($nombre != null) {
            $producto->setNombre($nombre);
        }
        if ($precio != null) {
            $producto->setPrecio($precio);
        }
        if ($categoria != null) {
            $producto->setCategoria($categoria);
        }
        if ($cantidad != null) {
            $producto->setCantidad($cantidad);
        }
        if ($stock != null) {
            $producto->setStock($stock);
        }
        if ($prestado != null) {
            $producto->setPrestado($prestado);
        }
        if ($malgastado != null) {
            $producto->setMalgastado($malgastado);
        }
        
        $em->persist($producto);
        $em->flush();

        return $this->render("editar_producto.html.twig", [
            "producto" => $producto
        ]);

    }

    #[Route('/eliminar_productos', name: 'eliminar_productos', methods: ['POST'])]
    public function eliminar_productos(Request $request, EntityManagerInterface $em)
    {
        // Cambiado para decodificar el JSON
        $data = json_decode($request->getContent(), true);
        $productosIds = $data['productos'] ?? null;

        if ($productosIds) {
            // Obtener los productos que coincidan con los IDs
            $productos = $em->getRepository(Producto::class)->findBy(['sn' => $productosIds]);

            foreach ($productos as $producto) {
                $em->remove($producto);
            }

            $em->flush();

            return new JsonResponse(['status' => 'success']);
        }

        return new JsonResponse(['status' => 'error', 'message' => 'No se enviaron productos.'], 400);
    }
}