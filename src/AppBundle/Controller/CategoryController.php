<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryController
 * @package AppBundle\Controller
 * @Route("/api")
 */
class CategoryController extends DefaultController
{

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/categories", name="category_add", requirements={"_format" = "json"})
     * @Method({"POST"})
     */
    public function addAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $em = $this->getDoctrine()->getManager();
        if (!array_key_exists('name', $data)) {
            return $this->createParameterMissingResponse('name');
        }
        foreach (array_keys($data) as $key) {
            if (!in_array($key, Category::getAvailableParameters())) {
                return $this->createBadParametersResponse(Category::getAvailableParameters());
            }
        }
        $category = new Category();
        $category->setName($data['name']);
        $em->persist($category);
        $em->flush();
        return new JsonResponse($category, 201);
    }

    /**
     * @return JsonResponse
     *
     * @Route("/categories", name="category_get_all", requirements={"_format" = "json"})
     * @Method({"GET"})
     */
    public function getAllAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findAll();
        return new JsonResponse($categories);
    }

    /**
     * @param $id
     * @return JsonResponse
     *
     * @Route("/categories/{id}", name="category_get_one", requirements={"_format" = "json", "id" = "\d+"})
     * @Method({"GET"})
     */
    public function getOneAction($id)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        if (!$category) {
            return $this->createNotFoundResponse($id, 'Category');
        }
        return new JsonResponse($category);
    }

    /**
     * @return JsonResponse
     *
     * @Route("/categories/products", name="category_products", requirements={"_format" = "json"})
     * @Method({"GET"})
     */
    public function getWithProductsAction()
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->getAllWithProducts();
        return new JsonResponse($categories);
    }

    /**
     * @param $id
     * @return JsonResponse
     *
     * @Route("/categories/{id}/products", name="category_one_products", requirements={"_format" = "json", "id" = "\d+"})
     * @Method({"GET"})
     */
    public function getOneWithProductsAction($id)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->getOneWithProducts($id);
        if (!$category) {
            return $this->createNotFoundResponse($id, 'Category');
        }
        return new JsonResponse($category);
    }

    /**
     * @param Request $request
     * @return JsonResponse|JsonResponse
     *
     * @Route("/categories/{id}", name="category_update", requirements={"_format" = "json", "id" = "\d+"})
     * @Method({"PUT"})
     */
    public function updateCategory(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($request->attributes->get('id'));
        if (!$category) {
            return $this->createNotFoundResponse($request->attributes->get('id'), 'Category');
        }
        foreach (array_keys($data) as $key) {
            if (!in_array($key, Category::getAvailableParameters())) {
                return $this->createBadParametersResponse(Category::getAvailableParameters());
            }
        }

        $em = $this->getDoctrine()->getManager();
        if (array_key_exists('name', $data)) {
            $category->setName($data['name']);
        }
        $em->persist($category);
        $em->flush();
        return new JsonResponse($category);
    }

    /**
     * @param $id
     * @return JsonResponse|Response
     *
     * @Route("/categories/{id}", name="category_delete", requirements={"_format" = "json", "id" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteCategory($id)
    {
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($id);
        if (!$category) {
            return $this->createNotFoundResponse($id, 'Category');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        return new Response(null,204);
    }



}