<?php


namespace App\EventSubscriber;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SessionTimeoutSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly TokenStorageInterface $tokenStorage,
        private readonly UserService           $userService,
        private readonly UrlGeneratorInterface $urlGenerator
    )
    {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        //  Ca intercepte que la requête principale (pas les rendus de fragments Twig)
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');

        // Ca ne fait rien si l'utilisateur va sur le login ou le logout,
        // sinon on va créer une boucle infinie de redirections !
        if (in_array($routeName, ['app_login', 'app_logout', '_wdt', '_profiler'], true)) {
            return;
        }

        // Ca récupère l'utilisateur connecté
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return;
        }

        $user = $token->getUser();
        if (!$user instanceof User) {
            return;
        }

        // Ca récupère la session et on appelle le service de vérification
        $session = $request->getSession();
        $isSessionValid = $this->userService->checkSessionTimeout($user, $session);

        //  Si le temps est dépassé, PHP coupe court et force la déconnexion
        if (!$isSessionValid) {
            //dd( Le Subscriber PHP a bien intercepté la session expirée !');
            $logoutUrl = $this->urlGenerator->generate('app_logout');
             $event->setResponse(new RedirectResponse($logoutUrl));
        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => [['onKernelRequest', 0]],
        ];
    }
}
