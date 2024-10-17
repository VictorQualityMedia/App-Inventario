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
class CrearProductosController extends AbstractController
{
    
    #[Route("/dirigir_a_crear", name: "dirigir_a_crear")]
    public function dirigir_a_crear(Request $request, EntityManagerInterface $em)
    {
        return $this->render('crear_producto.html.twig');
    }

    #[Route("/crear_producto", name: "crear_producto")]
    public function crear_producto(Request $request, EntityManagerInterface $em)
    {
        // Recolectamos los datos.
        $sn = $request->request->get('sn_producto');
        $nombre = $request->request->get('nombre');
        $precio = $request->request->get('precio');
        $fecha_compra_input = $request->request->get('fecha_compra'); // Recibir como string
        $categoria = $request->request->get('categoria');
        $cantidad = $request->request->get('cantidad');
        $stock = $request->request->get('stock');
        $prestado = $request->request->get('prestado');
        $malgastado = $request->request->get('malgastado');

        // Convertir la fecha de compra a un objeto DateTime
        $fecha_compra = DateTime::createFromFormat('Y-m-d', $fecha_compra_input);

        // Verificar si la conversión fue exitosa
        if (!$fecha_compra) {
            return new JsonResponse(['status' => 'error', 'message' => 'La fecha de compra es inválida.'], 400);
        }

        // Buscar el producto basado en el id
        $producto = new Producto();
        $producto->setSn($sn);
        $producto->setNombre($nombre);
        $producto->setPrecio($precio);
        $producto->setCategoria($categoria);
        $producto->setCantidad($cantidad);
        $producto->setStock($stock);
        $producto->setPrestado($prestado);
        $producto->setMalgastado($malgastado);
        $producto->setFechaCompra($fecha_compra); // Usar el objeto DateTime

        $em->persist($producto);
        $em->flush();

        return $this->render("crear_producto.html.twig");
    }

}