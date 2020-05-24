<?php
namespace App\Service;

use App\Helper\FormValidator;
use Symfony\Component\Form\Form;
use App\Response\RestApiEntityResponse;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RestApiService
{
    private $request;
    private $em;
    private $serializer;
    private $serializeGroup = "api";

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
    }

    public function handleFormRequest(Request $request, Form $form)
    {
        $form->submit($this->parseJsonPayload($request));

        FormValidator::validate($form);

        $entity = $form->getData();

        $this->saveEntity($entity);

        return $this->respond($entity);
    }

    public function parseJsonPayload(Request $request)
    {
        return json_decode($request->getContent(), true);
    }

    public function respond($entity, int $status = Response::HTTP_OK, array $headers = [])
    {
        $json = $this->serializer->serialize($entity, 'json', array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], ['groups' => $this->serializeGroup, AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true]));

        return new JsonResponse($json, $status, $headers, true);
    }

    public function saveEntity($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();
    }

    public function deleteEntity($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }

    /**
     * @param string $serializeGroup
     * @return RestApiService
     */
    public function setSerializeGroup(string $serializeGroup): RestApiService
    {
        $this->serializeGroup = $serializeGroup;
        return $this;
    }
}
