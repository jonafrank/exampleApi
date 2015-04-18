<?php
namespace AppBundle\Controller;

use AppBundle\Entity\ProductList;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ProductListController
 * @package AppBundle\Controller
 * @Route("/api")
 */
class ProductListController extends DefaultController
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @Route("/productLists", name="product_list_add", requirements={"_format" = "json"})
     * @Method({"POST"})
     */
    public function addAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $allowed = ProductList::getAvailableParameters();
        foreach ($allowed as $param) {
            if (!array_key_exists($param, $data)) {
                return $this->createParameterMissingResponse($param);
            }
        }
        foreach (array_keys($data) as $key) {
            if (!in_array($key, $allowed)) {
                return $this->createBadParametersResponse($allowed);
            }
        }
        $productList = new ProductList();
        $productList->setName($data['name']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($productList);
        $em->flush();
        return new JsonResponse($productList, 201);
    }

    /**
     * @param $listid
     * @param $prodid
     * @return JsonResponse
     * @Route("/productLists/{listid}/products/{prodid}", name="product_list_add_product", requirements={"_format" = "json", "listid" = "\d+", "prodid" = "\d+"})
     * @Method({"PUT"})
     */
    public function addProductAction($listid, $prodid)
    {
        $productList = $this->getDoctrine()->getRepository('AppBundle:ProductList')->find($listid);
        if (!$productList) {
            return $this->createNotFoundResponse($listid, 'ProductList');
        }
        $product = $this->getDoctrine()->getRepository('AppBundle:Product')->find($prodid);
        if (!$product) {
            return $this->createNotFoundResponse($prodid, 'Product');
        }
        $category = $product->getCategory();
        $productList->addProduct($product);
        $productList->addCategory($category);
        $em = $this->getDoctrine()->getManager();
        $em->persist($productList);
        $em->flush();
        return new JsonResponse($productList);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     *
     * @Route("/productLists/{id}", name="product_list_update", requirements={"_format" = "json", "id" = "\d+"})
     * @Method({"PUT"})
     */
    public function updateListAction(Request $request)
    {
        $productList = $this->getDoctrine()->getRepository('AppBundle:ProductList')->find($request->attributes->get('id'));
        if(!$productList) {
            return $this->createNotFoundResponse($request->attributes->get('id'), 'ProductList');
        }
        $data = json_decode($request->getContent(), true);
        $available = ProductList::getAvailableParameters();
        foreach (array_keys($data) as $param) {
            if (!in_array($param, $available)) {
                return $this->createBadParametersResponse($available);
            }
        }
        foreach ($available as $param) {
            if (!array_key_exists($param, $data)) {
                return $this->createParameterMissingResponse($param);
            }
        }
        $productList->setName($data['name']);
        $em = $this->getDoctrine()->getManager();
        $em->persist($productList);
        $em->flush();
        return new JsonResponse($productList);
    }

    /**
     * @return JsonResponse
     * @Route("/productLists", name="product_list_get", requirements={"_format" = "json"})
     * @Method({"GET"})
     */
    public function getAllAction()
    {
        $lists = $this->getDoctrine()->getRepository('AppBundle:ProductList')->findAll();
        return new JsonResponse($lists);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @Route("/productLists/{id}", name="product_list_get_one", requirements={"_format" = "json", "id" = "\d+"})
     * @Method({"GET"})
     */
    public function getOneAction($id)
    {
        $list = $this->getDoctrine()->getRepository('AppBundle:ProductList')->find($id);
        if (!$id) {
            return $this->createNotFoundResponse($id, 'ProductList');
        }
        return new JsonResponse($list);
    }
}