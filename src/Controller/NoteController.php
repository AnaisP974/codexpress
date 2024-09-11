<?php

namespace App\Controller;

use App\Repository\NoteRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/notes')] // SUfixe pour les routes du controller
class NoteController extends AbstractController
{
    #[Route('/', name: 'app_note_all', methods: ['GET'])]
    public function all(NoteRepository $nr): Response
    {
        $notes = $nr->findAll(
            ['is_public' => true], //Filtre les notes publics
            ['created_at' => 'DESC'], // Trie les notes par date de création
        );
        return $this->render('note/all.html.twig', [
            'allNotes' => $notes,
        ]);
    }

    #[Route('/{slug}', name:'app_note_show', methods: ['GET'])]
    public function show(NoteRepository $nr, string $slug): Response
    {
        $note = $nr->findOneBySlug(['slug' => $slug]);
        return $this->render('note/show.html.twig', [
            'note' => $note,
        ]);
    }

    #[Route('/{username}', name:'app_note_user', methods: ['GET'])]
    public function userNotes(UserRepository $user, string $username): Response
    {
        $creator = $user->findByUsername($username);
        return $this->render('note/user.html.twig', [
            'notes' => $creator->getNotes(),
            'user' => $creator->getUsername(),
        ]);
    }

    #[Route('/new', name: 'app_note_new', methods: ['GET', 'POST'])]
    public function new(): Response
    {
        return $this->render('note/new.html.twig', []);
    }

    #[Route('/edit/{slug}', name: 'app_note_edit', methods: ['GET', 'POST'])]
    public function edit(string $slug, NoteRepository $nr): Response
    {
        // Récupérer la note via le slug
        $note = $nr->findOneBySlug($slug);
        // Formulaire de modification et traitement des données

        return $this->render('note/edit.html.twig', [
            'note' => $note,
        ]);
    }

    #[Route('/delete/{slug}', name: 'app_note_delete', methods: ['POST'])]
    public function delete(string $slug, NoteRepository $nr): Response
    {
        // Récupérer la note via le slug
        $note = $nr->findOneBySlug($slug);

        // Traitement de la suppression

        //Création d'un message de confirmation
        $this->addFlash('success', 'Your code snippet has been deleted.');
        
        // Retour à la page 'app_note_user'
        return $this->redirectToRoute('app_note_user');
    }
}
