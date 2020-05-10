<?php


namespace App\Domain\Subscribers;

use App\Domain\Exceptions\JWTException;
use App\Domain\Exceptions\PostNotFoundException;
use App\Domain\Exceptions\ValidatorException;
use App\Responder\ErrorResponder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ExceptionEvent::class => 'onProcessException'
        ];
    }

    /**
     * @param ExceptionEvent $event
     */
    public function onProcessException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();
        switch (get_class($exception)) {
            case JWTException::class:
                $this->processJWTException($event);
                break;
            case ValidatorException::class:
                $this->processValidatorException($event);
                break;
            case NotFoundHttpException::class:
            case PostNotFoundException::class:
                $this->processNotFoundException($event);
                break;
        }
    }

    /**
     * @param ExceptionEvent $event
     */
    private function processJWTException(ExceptionEvent $event): void
    {
        /** @var JWTException $exception */
        $exception = $event->getThrowable();

        $event->setResponse(
            ErrorResponder::response(
                [
                    'error' => $exception->getError()
                ],
                $exception->getStatusCode()
            )
        );
    }

    private function processValidatorException(ExceptionEvent $event): void
    {
        /** @var ValidatorException $exception */
        $exception = $event->getThrowable();

        $event->setResponse(
            ErrorResponder::response(
                [
                    'errors' => $exception->getErrors()
                ],
                $exception->getStatusCode()
            )
        );
    }

    private function processNotFoundException(ExceptionEvent $event): void
    {
        /** @var NotFoundHttpException $exception */
        $exception = $event->getThrowable();

        $event->setResponse(
            ErrorResponder::response(
                [
                    'errors' => [
                        'message' => $exception->getMessage()
                    ]
                ],
                $exception->getStatusCode()
            )
        );
    }
}