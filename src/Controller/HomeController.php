<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\FAQCategory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class HomeController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Method("GET")
     */
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();

        $faqCategories = $em->getRepository(FAQCategory::class)->findAllByPosition();

        return $this->render('home/index.html.twig', [
            'faqCategories' => $faqCategories
        ]);
    }


}
