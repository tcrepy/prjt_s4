<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Users;
use AppBundle\Entity\Parties;
use AppBundle\Entity\Cartes;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * Class DefaultController
 * @package AppBundle\Controller
 * @Route("joueur")
 */
class JoueurController extends Controller
{

    /**
     * @Route("/", name="joueur_homepage")
     */
    public function indexAction()
    {
        $user = $this->getUser();
        return $this->render("joueur/index.html.twig", ['user' => $user]);
    }

    /**
     * @Route("/parties/", name="joueur_parties")
     */
    public function mesPartiesAction()
    {
        $user = $this->getUser();
        return $this->render("joueur/mesparties.html.twig", ['user' => $user]);
    }

    /**
     * @Route("/parties/add", name="joueur_parties_add")
     */

    public function addPartieAction()
    {
        $user = $this->getUser();
        // récupérer tous les joueurs existants
        $joueurs = $this->getDoctrine()->getRepository('AppBundle:Users')->findAll();
        return $this->render("joueur/addPartie.html.twig", ['user' => $user, 'joueurs' => $joueurs]);
    }

    /**
     * @param Users $id
     * @Route("/inviter/{joueur}", name="creer_partie")
     */
    public function creerPartieAction(Users $joueur)
    {

        $user = $this->getUser();

        $partie = new Parties();

        $partie->setUsers1($user);
        $partie->setUsers2($joueur);

        $partie->setPartieTour($user);

        $em = $this->getDoctrine()->getManager();
        $em->persist($partie);
        $em->flush();

        // récupérer les cartes
        $modeles = $this->getDoctrine()->getRepository('AppBundle:Modeles')->findAll();
        //on mélange les cartes
        shuffle($modeles);

        for ($i = 0; $i < 8; $i++) {
            $cartes = new Cartes();
            $cartes->setParties($partie);

            $cartes->setModeles($modeles[$i]);
            $cartes->setCarteSituation('mainJ1');

            $em->persist($cartes);
        }

        for ($i = 8; $i < 16; $i++) {
            $cartes = new Cartes();
            $cartes->setParties($partie);

            $cartes->setModeles($modeles[$i]);
            $cartes->setCarteSituation('mainJ2');

            $em->persist($cartes);
        }

        for ($i = 16; $i < count($modeles); $i++) {
            $cartes = new Cartes();
            $cartes->setParties($partie);

            $cartes->setModeles($modeles[$i]);
            $cartes->setCarteSituation('pioche');

            $em->persist($cartes);
        }

        $em->flush();

//        return $this->render('joueur/partie.html.twig', ['partie' => $partie, 'user' => $user]);
        return $this->redirectToRoute('afficher_partie', ['id' => $partie->getId()]);
    }

    /**
     * @param Parties $id
     * @Route("/afficher/{id}", name="afficher_partie")
     */
    public function afficherPartieAction(Parties $id)
    {
        $cartes = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findAll();
        $partie = $this->getDoctrine()->getRepository('AppBundle:Parties')->findBy(['id' => $id]);
        $user = $this->getUser();

        return $this->render(':joueur:afficherpartie.html.twig', ['cartes' => $cartes, 'parties' => $partie, 'user' => $user]);
    }

    /**
     * @param Parties $partieid
     * @Route("/piocherj1/{partieid}", name="piocherj1")
     */
    public function piocherj1Action($partieid)
    {
        $cartesPioche = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findOneBy(['carteSituation' => 'pioche', 'parties' => $partieid]);
        $em = $this->getDoctrine()->getManager();
        $cartesPioche->setCarteSituation('mainJ1');
        $em->flush();
        return $this->redirectToRoute('afficher_partie', ['id' => $partieid]);
    }

    /**
     * @param Parties $partieid
     * @Route("/piocherj2/{partieid}", name="piocherj2")
     */
    public function piocherj2Action($partieid)
    {
        $cartesPioche = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findOneBy(['carteSituation' => 'pioche', 'parties' => $partieid]);
        $em = $this->getDoctrine()->getManager();
        $cartesPioche->setCarteSituation('mainJ2');
        $em->flush();
        return $this->redirectToRoute('afficher_partie', ['id' => $partieid]);
    }

    /**
     * @param Parties $partieid Cartes $carteid
     * @Route("/jouer/{partieid}/{carteid}", name="jouerCarte")
     **/

    public function jouerCarteAction($partieid, $carteid)
    {

        //recup de la carte à jouer, et de sa catégorie
        $carteAJouer = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findOneBy(['id' => $carteid]);
        $categorie = $carteAJouer->getModeles()->getModeleCategorie();
        $valeur = $carteAJouer->getModeles()->getModeleValeur();

        //recup des cartes sur le plateau
        $cartesSurPlateau = $this->getDoctrine()->getRepository('AppBundle:Cartes')->findBy(['carteSituation' => 'plateau', 'parties' => $partieid]);

        //si il y a des cartes sur le plateau
        if (!empty($cartesSurPlateau)) {
            $test = 0;
            $aEteJouee = 0;
            foreach ($cartesSurPlateau as $val) {
                //si les catégories sont les mêmes et que la valeur de la carte jouée est supérieure à celle du plateau
                if ($val->getModeles()->getModeleCategorie() == $categorie) {
                    if ($val->getModeles()->getModeleValeur() < $valeur) {
                        $aEteJouee = 1;
                    } else {

                    }
                } else {
                    $test++;
                }
            }
            if ($test == count($cartesSurPlateau) || $aEteJouee == 1) {
                //on joue la carte
                $em = $this->getDoctrine()->getManager();
                $carteAJouer->setCarteSituation('plateau');
                $em->flush();
                $message = 'La carte a été jouée';
            } else {
                $message = 'La carte n\'a pas pu être jouée';
            }
        } else {
            //sinon on joue la carte
            $em = $this->getDoctrine()->getManager();
            $carteAJouer->setCarteSituation('plateau');
            $em->flush();
            $message = 'La carte a été jouée';
        }


        return $this->redirectToRoute('afficher_partie', ['id' => $partieid]);
//        return $this->render('joueur/test.html.twig', ['message' => $message]);
    }
}