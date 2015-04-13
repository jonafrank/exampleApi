<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    protected function createParameterMissingResponse($parameter)
    {
        $content = array(
            'error' => array(
                'type'    => 'Bad Request - Missing Parameter',
                'message' => 'Parameter \''. $parameter . '\' missing'
            )
        );
        return new JsonResponse($content, 400);
    }

    protected function createBadParametersResponse($paramters)
    {
        $content = array(
            'error' => array(
                'type'  => 'Bad Request - Wrong Parameters',
                'message' => 'Wrong parameters provided. Allowed Parammeters are [' . implode('|', $paramters ). ']'
            )
        );
        return new JsonResponse($content, 404);
    }

    protected function createNotFoundResponse($id, $entity)
    {
        $content = array(
            'error' => array(
                'type'    => 'Not Found',
                'message' => $entity . ' with id = ' . $id . ' not found'
            )
        );
        return new JsonResponse($content, 404);
    }
}
