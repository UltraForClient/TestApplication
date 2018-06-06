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

/**
 * @Route("/admin/faq")
 */
class FAQController extends Controller
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="faq-admin")
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
     *     "/change-position/{id}/{position}",
     *     name="faq-change-position",
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
     * @Route(
     *     "/enable/{id}",
     *     name="faq-enable",
     *     methods={"POST"},
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function enable(FAQ $faq): JsonResponse
    {
        $faq->setEnable(!$faq->getEnable());
        $this->em->flush();

        return $this->json('Enable');
    }

    /**
     * @Route("/new", name="faq-new")
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

    /**
     * @Route("/edit/{id}", requirements={"id": "\d+"}, methods={"GET", "POST"}, name="faq-edit")
     */
    public function edit(Request $request, FAQ $faq): Response
    {
        $form = $this->createForm(FAQType::class, $faq);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('faq-admin');
        }

        $categories = $this->em->getRepository(FAQCategory::class)->findAll();

        return $this->render('admin/editFaq.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/delete/{id}", requirements={"id": "\d+"}, name="faq-delete")
     */
    public function delete(FAQ $faq): Response
    {
        $faq->setDeletedAt(new \DateTime());

        $this->em->flush();

        return $this->redirectToRoute('faq-admin');
    }
}
