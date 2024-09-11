<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET'])]
    public function index(NoteRepository $nr): Response
    {
        $lastNotes = $nr->findBy(
            ['is_public' => true], //Filtre les notes publics
            ['created_at' => 'DESC'], // Trie les notes par date de création
            6 // Limite à 6 notes
        );
        return $this->render('home/index.html.twig', [
            'lastNotes' => $lastNotes, // Envoie les 6 dernières notes publiques à la vue Twig
        ]);
    }
}
