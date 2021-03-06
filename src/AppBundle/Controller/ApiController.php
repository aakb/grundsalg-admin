<?php

namespace AppBundle\Controller;

use AppBundle\DBAL\Types\GrundType;
use AppBundle\DBAL\Types\SalgsType;
use AppBundle\Entity\Grund;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\DBAL\Types\GrundStatus as Status;
use AppBundle\DBAL\Types\GrundSalgStatus as SalgStatus;
use AppBundle\DBAL\Types\GrundPublicStatus as PublicStatus;

/**
 * @Route("/public/api")
 */
class ApiController extends Controller
{
    /**
     * @TODO: Missing documentation!
     *
     * @Route("/udstykning/{udstykningsId}/grunde/{format}", name="pub_api_grunde")
     */
    public function grundeAction(Request $request, $udstykningsId, $format = 'drupal_api')
    {
        $em     = $this->getDoctrine()->getManager();
        $grunde = $em->getRepository('AppBundle:Grund')->getGrundeForSalgsOmraade($udstykningsId);

        $publicPropertiesService = $this->get('grundsalg.public_properties');

        $list = [];

        if ($format === 'geojson') {
            $list['type'] = 'FeatureCollection';

            $crs                       = [];
            $crs['type']               = 'link';
            $crs['properties']['href'] = 'http://spatialreference.org/ref/epsg/25832/proj4/';
            $crs['properties']['href'] = $this->getParameter('crs_properties_href');
            $crs['properties']['type'] = $this->getParameter('crs_properties_type');
            $list['crs']               = $crs;

            $list['features'] = [];

            foreach ($grunde as $grund) {
                $data = [];

                $data['type']     = 'Feature';
                $data['geometry'] = $grund->getSpGeometryArray();

                $properties['id']      = $grund->getId();
                $properties['address'] = trim($grund->getVej().' '.$grund->getHusnummer().$grund->getBogstav());
                $properties['status']  = $publicPropertiesService->getPublicStatus($grund);
                $properties['area_m2'] = intval($grund->getAreal());
                // @TODO which fields to map for prices?
                $properties['minimum_price'] = $publicPropertiesService->getPublicMinPris($grund);
                $properties['sale_price']    = $publicPropertiesService->getPublicPris($grund);
                $properties['pdf_link']      = $grund->getPdfLink();

                // Needed in the frontend/weblayer. If true popup will be enabled for the feature.
                $properties['markers'] = true;

                $data['properties'] = $properties;

                $list['features'][] = $data;
            }

        } else {

            $list['count']  = count($grunde);
            $list['grunde'] = [];

            foreach ($grunde as $grund) {
                $data            = [];
                $data['id']      = $grund->getId();
                $data['address'] = trim($grund->getVej().' '.$grund->getHusnummer().$grund->getBogstav());
                $data['status']  = $publicPropertiesService->getPublicStatus($grund);
                $data['area_m2'] = intval($grund->getAreal());
                $data['minimum_price'] = $publicPropertiesService->getPublicMinPris($grund);
                $data['sale_price']    = $publicPropertiesService->getPublicPris($grund);
                $data['pdf_link']      = $grund->getPdfLink();

                $list['grunde'][] = $data;
            }

        }

        $response = $this->json($list);

        $response->setPrivate();
        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);

        return $response;
    }

    /**
     * Returns JSON with information about a "Salgsomraad".
     *
     * @param Request $request
     *   Symfony request object.
     * @param Int $udstykningsId
     *   The id for the area to load.
     *
     * @return JsonResponse
     *   JSON encode symfony response object.
     *
     * @Route("/udstykning/{udstykningsId}", name="pub_api_salgsomraade")
     */
    public function salgsomraadeAction(Request $request, $udstykningsId)
    {
        $em   = $this->getDoctrine()->getManager();
        $area = $em->getRepository('AppBundle:Salgsomraade')->findOneById($udstykningsId);
        $data = [];

        if ($area->isAnnonceres()) {
            $data = [
                'id'         => $area->getId(),
                'type'       => $area->getType(),
                'title'      => $area->getTitel(),
                'vej'        => $area->getVej(),
                'city'       => $area->getPostby() ? $area->getPostby()->getCity() : null,
                'postalCode' => $area->getPostby() ? $area->getPostby()->getPostalcode() : null,
                'geometry'   => $area->getSpGeometryArray(),
                'srid'       => $area->getSrid(),
                'publish'    => $area->isAnnonceres(),
            ];
        }

        $response = $this->json($data);

        $response->setPrivate();
        $response->setMaxAge(0);
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->headers->addCacheControlDirective('no-store', true);

        return $response;
    }

}
