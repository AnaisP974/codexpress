<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('AUTHENTICATED_FULLY')]
#[Route('/profile')]
class CreatorController extends AbstractController
{
    #[Route('/', name: 'app_profile', methods: ['GET'])]
    public function profile(): Response
    {
        return $this->render('creator/profile.html.twig', []);
    }

    #[Route('/edit', name:'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(): Response
    {
        $user = $this->getUser(); // Récupère l'utilisateur authentifié
        
        return $this->render('creator/edit.html.twig', []);
    }
}
