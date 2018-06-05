<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class FAQController extends Controller
{
    /**
     * @Route("/faq", name="faq")
     * @Method("GET")
     */
    public function index(): Response
    {
        return $this->render('faq/index.html.twig', [
        ]);
    }


}
