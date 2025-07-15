<?php

namespace App\Controller\Api;

use App\Entity\Track;
use App\Form\TrackType;
use App\Service\TrackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/tracks')]
class TrackController extends AbstractController
{
    public function __construct(
        private TrackService $trackService
    ){
    }

    #[Route('', name: 'track_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $tracks = $this->trackService->getAllTracks();
        return new JsonResponse($tracks, 200);
    }

    #[Route('', name: 'track_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $track = new Track();
        $form = $this->createForm(TrackType::class, $track);
        $form->submit($request->getContent() ? json_decode($request->getContent(), true) : []);
        if ($form->isSubmitted() && $form->isValid()) {
            $track = $this->trackService->saveTrack($track);
            return new JsonResponse([
                'id' => $track->getId(),
                'title' => $track->getTitle(),
                'artist' => $track->getArtist(),
                'duration' => $track->getDuration(),
                'isrc' => $track->getIsrc(),
            ], 201); // 201 Created to indicate a new track was successfully created
        }
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getOrigin()->getName()][] = $error->getMessage();
        }
        return new JsonResponse($errors, 400);
    }

    #[Route('/{id}', name: 'track_update', methods: ['PUT'])]
    public function update(Request $request, Track $track): JsonResponse
    {
        $form = $this->createForm(TrackType::class, $track);
        $form->submit($request->getContent() ? json_decode($request->getContent(), true) : []);
        if ($form->isSubmitted() && $form->isValid()) {
            $track = $this->trackService->saveTrack($track);
            return new JsonResponse([
                'id' => $track->getId(),
                'title' => $track->getTitle(),
                'artist' => $track->getArtist(),
                'duration' => $track->getDuration(),
                'isrc' => $track->getIsrc(),
            ], 200);
        }
        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getOrigin()->getName()][] = $error->getMessage();
        }
        return new JsonResponse($errors, 400);
    }
}
