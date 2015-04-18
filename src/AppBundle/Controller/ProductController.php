<?php
namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class ProductController
 * @package AppBundle\Controller
 * @Route("/api")
 */
class ProductController extends DefaultController
{
    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/categories/{catid}/products", name="product_add", requirements={"_format" = "json", "catid" = "\d+"})
     * @Method({"POST"})
     */
    public function addAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $allowedParams = Product::getAvailableParameters();
        foreach ($allowedParams as $param) {
            if (!array_key_exists($param, $data)) {
               return $this->createParameterMissingResponse($param);
            }
        }
        foreach (array_keys($data) as $key) {
            if (!in_array($key,$allowedParams)) {
                return $this->createBadParametersResponse($allowedParams);
            }
        }
        $catid = $request->attributes->get('catid');
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($catid);
        if (!$category) {
            return $this->createNotFoundResponse($catid, 'Category');
        }
        $product = new Product();
        $product->setTitle($data['title']);
        $product->setDescription($data['description']);
        $product->setCategory($category);
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        return new JsonResponse($product, 201);
    }

    /**
     * @return JsonResponse
     *
     * @Route("/products", name="products_get_all", requirements={"_format" = "json"})
     * @Method({"GET"})
     *
     */
    public function getAllAction()
    {
        $products = $this->getDoctrine()->getRepository('AppBundle:Product')->getAllWithCategories();
        return new JsonResponse($products, 200);
    }

    /**
     * @param $id
     * @return JsonResponse
     *
     * @Route("/products/{id}", name="products_get_one", requirements={"_format" = "json", "id" = "\d+"})
     * @Method({"GET"})
     */
    public function getOneAction($id)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        if (!$product) {
            return $this->createNotFoundResponse($id, 'Product');
        }
        return new JsonResponse($product);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/product/{id}", name="products_update", requirements={"_format" = "json", "id" = "\d+"})
     * @Method({"PUT"})
     */
    public function updateAction(Request $request)
    {
        $data = json_decode($request->getContent());
        $allowed = Product::getAvailableParameters(true);
        foreach (array_keys($data) as $key) {
            if (!in_array($key, $allowed)) {
                return $this->createBadParametersResponse($allowed);
            }
        }
        $prodid = $request->attributes->get('id');
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($prodid);
        if (!$product) {
            return $this->createNotFoundResponse($prodid, 'Product');
        }
        if (array_key_exists('category', $data)) {
            if (!ctype_digit($data['category'])) {
                return $this->createInvalidTypeResponse('category', 'integer');
            }
            $category = $this->getDoctrine()->getRepository('AppBundle:Category')->find($data['category']);
            if (!$category) {
                return $this->createNotFoundResponse($data['category'], 'Category');
            }
            $product->setCategory($category);
        }
        if (array_key_exists('title', $data)) {
            $product->setTitle($data['title']);
        }
        if (array_key_exists('description', $data)) {
            $product->setDescription('description');
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();
        return new JsonResponse($product);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @Route("/product/{id}", name="products_delete", requirements={"_format" = "json", "id" = "\d+"})
     * @Method({"DELETE"})
     */
    public function deleteAction($id)
    {
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($id);
        if (!$product) {
            return $this->createNotFoundResponse($id, 'Product');
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();
        return new JsonResponse(null, 204);
    }
}