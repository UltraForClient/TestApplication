<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\FAQ;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\EntityManagerInterface;

class FAQAdminController extends Controller
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin/faq", name="faq-admin")
     */
    public function index(): Response
    {
        $faqs = $this->em->getRepository(FAQ::class)->findAll();

        return $this->render('admin/faq.html.twig', [
            'faqs' => $faqs
        ]);
    }

    /**
     * @Route("/admin/faq/new", name="faq-admin-new")
     */
    public function new(): Response
    {
        return $this->render('admin/new.html.twig', [

        ]);
    }

}
