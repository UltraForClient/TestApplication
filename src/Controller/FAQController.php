<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\FAQ;
use App\Entity\FAQCategory;
use App\Form\FAQType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;

class FAQController extends Controller
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
        $faqs = $this->em->getRepository(FAQ::class)->findAllByPosition();

        return $this->render('admin/faq.html.twig', [
            'faqs' => $faqs
        ]);
    }

    /**
     * @Route(
     *     "/admin/faq/change-position/{id}/{position}",
     *     name="faq-admin-change-position",
     *     methods={"POST"},
     *     requirements={"id": "[1-9]\d*", "position": "[0-9]\d*"}
     * )
     */
    public function changePosition(FAQ $faq, int $position): JsonResponse
    {
        $faq->setPosition($position);
        $this->em->flush();

        return $this->json('Changed position');
    }

    /**
     * @Route("/admin/faq/new", name="faq-admin-new")
     */
    public function new(Request $request): Response
    {
        $faq = new FAQ();

        $form = $this->createForm(FAQType::class, $faq);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $category = $this->em->getRepository(FAQCategory::class)->find($request->request->get('categories'));

            $faq->setCategory($category);

            $this->em->persist($faq);
            $this->em->flush();

            return $this->redirectToRoute('faq-admin');
        }

        $categories = $this->em->getRepository(FAQCategory::class)->findAll();

        return $this->render('admin/newFaq.html.twig', [
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }

    

}
