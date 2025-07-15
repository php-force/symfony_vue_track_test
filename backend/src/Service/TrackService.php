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
        return $this->trackRepository->findAll();
    }

    public function createTrack(Track $track): Track
    {
        $this->entityManager->persist($track);
        $this->entityManager->flush();
        return $track;
    }

    public function updateTrack(Track $track): Track
    {
        $this->entityManager->flush();
        return $track;
    }
}