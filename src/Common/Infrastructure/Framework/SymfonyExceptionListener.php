<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Framework;

use App\Common\Domain\Exception\CustomException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

final class SymfonyExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        // You get the exception object from the received event
        $exception = $event->getThrowable();
        $message = $exception->getMessage();

        $routeName = $event->getRequest()->attributes->get('_route');
        $isApiRoute = str_starts_with($routeName, 'app_api_');

        // Customize your response object to display the exception details
        if ($isApiRoute) {
            $response = new JsonResponse();
            $response->setData(['ok' => false, 'error' => ['message' => $message]]);
            $response->headers->set('Content-Type', 'application/json');
        } else {
            $response = new Response($message);
            $response->setContent($message);
            // the exception message can contain unfiltered user input;
            // set the content-type to text to avoid XSS issues
            $response->headers->set('Content-Type', 'text/plain; charset=utf-8');
        }


        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof CustomException) {
            $response->setStatusCode($exception->getCode());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
