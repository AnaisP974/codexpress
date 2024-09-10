<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NoteController extends AbstractController
{
    #[Route('/note/{slug}', name: 'app_note')]
    public function show(NoteRepository $notes, string $slug): Response
    {
        return $this->render('note/show.html.twig', [
            'note' => $notes->findBy(['slug' => $slug]),
        ]);
    }

    #[Route('/notes', name: 'app_notes')]
    public function notes(NoteRepository $notes): Response
    {
        
        return $this->render('note/index.html.twig', [
            'notes' => $notes->findAll(),
        ]);
    }
}
