<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crud/annonce')]
class CrudAnnonceController extends AbstractController
{
    #[Route('/list', name: 'app_crud_annonce')]
    public function listAnnonce(AnnonceRepository $repository): Response
    {
        $annonces = $repository->findAll();
        return $this->render('crud_annonce/annonce.html.twig', ['annonces' => $annonces]);
    }

    #[Route('/search', name: 'app_search_annonce')]
    public function searchByTitle(Request $request, AnnonceRepository $repository): Response
    {
        $title = $request->query->get('title');
        $annonces = $repository->findBy(['title' => $title]);
        return $this->render('crud_annonce/annonce.html.twig', ['annonces' => $annonces]);
    }

    #[Route('/insert', name: 'app_insert_annonce')]
    public function insertAnnonce(ManagerRegistry $doctrine): Response
    {
        $annonce = new Annonce();
        $annonce->setTitle('Annonce Exemple');
        $annonce->setPublicationDate(new \DateTime());
        $annonce->setNbrLike(0);

        $em = $doctrine->getManager();
        $em->persist($annonce);
        $em->flush();

        return $this->redirectToRoute('app_crud_annonce');
    }

    #[Route('/delete/{id}', name: 'app_delete_annonce')]
    public function deleteAnnonce($id, AnnonceRepository $repository, ManagerRegistry $doctrine): Response
    {
        $annonce = $repository->find($id);
        $em = $doctrine->getManager();
        $em->remove($annonce);
        $em->flush();

        return $this->redirectToRoute('app_crud_annonce');
    }

    #[Route('/update/{id}', name: 'app_update_annonce')]
    public function updateAnnonce(Annonce $annonce, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $annonce->setNbrLike($annonce->getNbrLike() + 1);
        $em->flush();

        return $this->redirectToRoute('app_crud_annonce');
    }
    #[Route('/{id}/annonces', name: 'app_agence_annonces')]
    public function showAnnonces(Agence $agence): Response
    {
        $annonces = $agence->getAnnonces();

        return $this->render('crud_agence/annonce.html.twig', [
            'annonces' => $annonces,
            'agence' => $agence,
        ]);
    }
}
