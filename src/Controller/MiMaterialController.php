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
class MiMaterialController extends AbstractController
{
    
        // Para que el usuario pueda ver sus solicitudes y ver si está aprobadas o no.
        #[Route("/mi_material", name: "mi_material")]
        public function mi_material(Request $request, EntityManagerInterface $em)
        {
            $user = $this->getUser();
    
            $prestamos = $em->getRepository(Prestamo::class)->findBy(['usuarioPrestamo' => $user]);
    
            // Proseguimos con pasar los datos al controlador y mandárselos a la plantilla
            return $this->render('mi_material.html.twig', [
                "prestamos" => $prestamos
            ]);
        }

        // Para que el usuario pueda ver sus solicitudes y ver si está aprobadas o no.
        #[Route("/devolucion_parcial", name: "devolucion_parcial")]
        public function devolucion_parcial(Request $request, EntityManagerInterface $em)
        {
            // En lugar de decodificar el JSON, obtén los valores directamente del request POST
            $id_prestamo = $request->request->get('id_prestamo');
            $cantidad_devolver = $request->request->get('cantidad');

            // Buscar el préstamo original
            $prestamo = $em->getRepository(Prestamo::class)->find($id_prestamo);

            // Si la cantidad a devolver es igual a la cantidad del préstamo original
            if ($cantidad_devolver == $prestamo->getCantidad()) {
                $prestamo->setEstado("ESPERA DEVOLUCION");
                $em->persist($prestamo);
            } else {
                // Crear un nuevo préstamo para la devolución parcial
                $nuevo_prestamo = new Prestamo();
                
                // Copiar las propiedades del préstamo original
                $nuevo_prestamo->setUsuarioPrestamo($prestamo->getUsuarioPrestamo());
                $nuevo_prestamo->setCodProducto($prestamo->getCodProducto());
                $nuevo_prestamo->setFechaPrestamo($prestamo->getFechaPrestamo());
                $nuevo_prestamo->setFechaDevolucion($prestamo->getFechaDevolucion());
                $nuevo_prestamo->setEvento($prestamo->getEvento());
                $nuevo_prestamo->setEstado("ESPERA DEVOLUCION");

                // Asignar la cantidad a devolver al nuevo préstamo
                $nuevo_prestamo->setCantidad($cantidad_devolver);

                // Reducir la cantidad del préstamo original
                $prestamo->setCantidad($prestamo->getCantidad() - $cantidad_devolver);

                // Persistir ambos objetos
                $em->persist($nuevo_prestamo);
                $em->persist($prestamo);
            }

            // Guardar los cambios en la base de datos
            $em->flush();

            return new JsonResponse(['success' => true]);
        }

}