<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\FAQ;
use App\Entity\FAQCategory;
use App\Form\FAQCategoryType;
use App\Form\FAQType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/admin/faq-category")
 */
class FAQCategoryController extends Controller
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="faq-category-admin")
     */
    public function index(): Response
    {
        $faqCategories = $this->em->getRepository(FAQCategory::class)->findAll();

        return $this->render('admin/category.html.twig', [
            'faqCategories' => $faqCategories
        ]);
    }

    /**
     * @Route(
     *     "/change-position/{id}/{position}",
     *     name="faq-category-change-position",
     *     methods={"POST"},
     *     requirements={"id": "[1-9]\d*", "position": "[0-9]\d*"}
     * )
     */
    public function changePosition(FAQCategory $category, int $position): JsonResponse
    {
        $category->setPosition($position);
        $this->em->flush();

        return $this->json('Changed position');
    }

    /**
     * @Route(
     *     "/enable/{id}",
     *     name="faq-category-enable",
     *     methods={"POST"},
     *     requirements={"id": "[1-9]\d*"}
     * )
     */
    public function enable(FAQCategory $category): JsonResponse
    {
        $category->setEnable(!$category->getEnable());
        $this->em->flush();

        return $this->json('Enable');
    }

    /**
     * @Route("/new", name="faq-category-admin-new")
     */
    public function new(Request $request): Response
    {
        $faqCategories = new FAQCategory();

        $form = $this->createForm(FAQCategoryType::class, $faqCategories);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $this->em->persist($faqCategories);
            $this->em->flush();

            return $this->redirectToRoute('faq-category-admin');
        }

        return $this->render('admin/newCategory.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit/{id}", requirements={"id": "\d+"}, methods={"GET", "POST"}, name="faq-category-edit")
     */
    public function edit(Request $request, FAQCategory $faq): Response
    {
        $form = $this->createForm(FAQCategoryType::class, $faq);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            return $this->redirectToRoute('faq-admin');
        }

        $categories = $this->em->getRepository(FAQCategory::class)->findAll();

        return $this->render('admin/editCategory.html.twig', [
            'faq' => $faq,
            'form' => $form->createView(),
            'categories' => $categories
        ]);
    }
}
