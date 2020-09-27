<?php
declare(strict_types=1);

namespace App\Controller;

use Grpc\ChannelCredentials;
use Phpfestproto\GetMessageRequest;
use Phpfestproto\GetMessageResponse;
use Phpfestproto\PHPFestClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function index(): Response
    {
        return new Response('Test');
    }

    /**
     * @Route("/getGRPC")
     */
    public function getGRPC(): Response
    {
        $client = new PHPFestClient(
            //'127.0.0.1:8282',
            'unix://' . $this->getParameter('kernel.project_dir') . DIRECTORY_SEPARATOR . 'phpfestgo.sock',
            [
                'credentials' => ChannelCredentials::createInsecure(),
            ]
        );

        $request = new GetMessageRequest();
        $request->setTbool(false);

        /** @var $response GetMessageResponse */
        [$response, $status] = $client->GetMessage($request)->wait();
        if ($status->code !== 0) {
            dump($status);

            return new Response('Error in handling request ', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return new Response($response->serializeToJsonString());
    }
}
