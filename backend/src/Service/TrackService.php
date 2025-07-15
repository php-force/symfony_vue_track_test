<?php

namespace App\Service;

use App\Entity\Track;
use App\Repository\TrackRepository;
use Doctrine\ORM\EntityManagerInterface;

class TrackService
{
    private TrackRepository $trackRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(TrackRepository $trackRepository, EntityManagerInterface $entityManager)
    {
        $this->trackRepository = $trackRepository;
        $this->entityManager = $entityManager;
    }

    public function getAllTracks(): array
    {
        $tracks = $this->trackRepository->findAll();
        return array_map(function (Track $track) {
            return [
                'id' => $track->getId(),
                'title' => $track->getTitle(),
                'artist' => $track->getArtist(),
                'duration' => $track->getDuration(),
                'isrc' => $track->getIsrc(),
            ];
        }, $tracks);
    }

    public function saveTrack(Track $track): Track
    {
        $this->entityManager->persist($track);
        $this->entityManager->flush();
        return $track;
    }
}