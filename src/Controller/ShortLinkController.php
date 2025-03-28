<?php

namespace App\Controller;

use App\DTO\CreateShortLinkDTO;
use App\Entity\ShortLink;
use App\Service\ShortLinkService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class ShortLinkController extends AbstractController
{
    #[Route('/short-links', methods: ['POST'], name: 'create_short_link')]
    public function create(
        #[MapRequestPayload]
        CreateShortLinkDTO $dto,
        ShortLinkService $shortLinkService,
    ): Response {
        $shortLink = $shortLinkService->createShortLink($dto);

        return $this->json($shortLink);
    }
    #[Route('/short-links/{id}', methods: ['PUT'], name: 'update_short_link')]
    public function update(
        ShortLink $shortLink,
        #[MapRequestPayload]
        CreateShortLinkDTO $dto,
        ShortLinkService $shortLinkService,
        EntityManagerInterface $entityManager
    ): Response {
        $shortLink = $shortLinkService->updateShortLink($shortLink, $dto);
        return $this->json($shortLink);
        $shortLink->setShortCode($dto->shortCode);
        $shortLink->setUrl($dto->url);
        $shortLink->setMaxVisits($dto->maxVisits);
        $shortLink->setValidOn($dto->validOn);
        $shortLink->setExpiresAt($dto->expiresAt);
        $shortLink->setTags($dto->tags);

        $entityManager->flush();

        return $this->json($shortLink);
    }
}
