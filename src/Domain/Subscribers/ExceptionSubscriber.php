<?php


namespace App\Domain\Subscribers;

use App\Domain\Exceptions\JWTException;
use App\Domain\Exceptions\ValidatorException;
use App\Responder\ErrorResponder;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

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
    public function onProcessException(ExceptionEvent $event)
    {
        $exception = $event->getThrowable();
        switch (get_class($exception)) {
            case JWTException::class:
                $this->processJWTException($event);
                break;
            case ValidatorException::class:
                $this->processValidatorException($event);
                break;
        }
    }

    /**
     * @param ExceptionEvent $event
     */
    private function processJWTException(ExceptionEvent $event)
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

    private function processValidatorException(ExceptionEvent $event)
    {
        /** @var ValidatorException $exception */
        $exception = $event->getThrowable();

        $event->setResponse(
            ErrorResponder::response(
                [
                    'error' => $exception->getErrors()
                ],
                $exception->getStatusCode()
            )
        );
    }
}