<?php

declare(strict_types=1);

namespace App\Presenters;

namespace App\Module\Api\Presenters;

use Nette;
use App\Model\Blog;
use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerBuilder;


class ApiPresenter extends Nette\Application\UI\Presenter
{
    protected EntityManagerInterface $em;
    protected $serializer;
    protected $jwtSecret;


    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
        $this->serializer = SerializerBuilder::create()->build();
        $this->jwtSecret = '123123123';
    }

    /**
     * Serialize data by groups for current object
     * @param data array with model for serializing
     * @param groups strings with current groups for serializing
     * @return string  serialized data in JSON format
     */
    
    protected function serializeJson($data, array $groups = [])
    {
        $context = $this->getSerializationContext($groups);
        return $this->serializer->serialize($data, 'json', $context);
    }


    protected function sendJsonError(string $message)
    {
        // $this->getHttpResponse()->setCode($code);
        $errorResponse = [
            'status' => 'error',
            'message' => $message
        ];
        $this->sendJson($errorResponse);
    }


    /**
     * Define attribute for serializing by groups 
     */
    private function getSerializationContext(array $groups = [])
    {
        $context = SerializationContext::create();
        if (!empty($groups)) {
            $context->setGroups($groups);
        }
        return $context;
    }


 




    
}
