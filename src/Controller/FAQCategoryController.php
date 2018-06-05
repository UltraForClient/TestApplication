<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\FAQCategory;
use App\Form\FAQType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;

class FAQCategoryController extends Controller
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/admin/faq-category", name="faq-category-admin")
     */
    public function index(): Response
    {
        $faqCategories = $this->em->getRepository(FAQCategory::class)->findAll();

        return $this->render('admin/faq.html.twig', [
            'faqCategories' => $faqCategories
        ]);
    }

    /**
     * @Route("/admin/faq-category/new", name="faq-category-admin-new")
     */
    public function new(Request $request): Response
    {
        $faqCategories = new FAQCategory();

        $form = $this->createForm(FAQType::class, $faqCategories);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($faqCategories);
            $this->em->flush();

            return $this->redirectToRoute('faq-category-admin');
        }

        return $this->render('admin/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
