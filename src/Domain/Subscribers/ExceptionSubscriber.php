<?php


namespace App\Domain\Subscribers;

use App\Domain\Exceptions\JWTException;
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
        }
    }

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
}