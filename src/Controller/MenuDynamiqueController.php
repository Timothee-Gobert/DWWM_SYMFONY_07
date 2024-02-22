<?php

namespace App\Controller;

use App\Entity\Menu;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MenuDynamiqueController extends AbstractController
{
    #[Route('/menu/dynamique', name: 'app_menu_dynamique')]
    public function index(EntityManagerInterface $em, UrlGeneratorInterface $ugi): Response
    {
        
        $menus=$em->getRepository(Menu::class)->findBy([],['rang'=>'asc']);
        $html=$this->show_menu($ugi,null, 0, $menus);
        return $this->render('menu_dynamique/index.html.twig', [
            'menu' => $html,
        ]);
    }

    public function show_menu($ugi,$parent,$niveau,$menus){
        $html="";
        $niveau_precedent=0;
        if($niveau_precedent==0 && $niveau==0){
            $html.="<ul class='navbar d-flex'>"; 
        }
        foreach($menus as $menu){
            $id=$menu->getId();
            $rang=$menu->getRang();
            $libelle=$menu->getLibelle();
            $role=$menu->getRole();
            $autorisation=false;
            if($this->isGranted($role) || $role=='ROLE_USER'){
                $autorisation=true;
            }
            $url=$menu->getUrl();
            try {
                $href=$ugi->generate($url);
            } catch (\Throwable $th) {
                $href=$url;
            }
            $icone=$menu->getIcone();
            $parentMenu=$menu->getParent();
            $enfants=$menu->getMenus();
            $nbreEnfants=count($enfants);
            $drop=($niveau>1)?'dropend':'dropdown';
            $class_a=($nbreEnfants!=0)?"nav-link dropdown-toggle mx-2":"nav-link mx-2";
            $color=($niveau==0)?"text-light":"";
            if($parentMenu==$parent && $autorisation){
                if($niveau_precedent<$niveau){
                    $html.="<ul class='dropdown-menu'>";
                }
                if($nbreEnfants != 0){
                    $html.="<li class='nav_item $drop'><a href='$href' class='$class_a $color' data-bs-toggle='dropdown' data-bs-auto-close='outside'>$libelle</a>";
                } else {
                    $html.="<li class='nav_item'><a href='$href' class='$class_a $color'>$icone $libelle</a></li>";
                }
                $niveau_precedent=$niveau;
                $html.=$this->show_menu($ugi,$menu,$niveau+1,$menus);
            }
        }
        if($niveau_precedent==0 && $niveau==0){
            $html.="<ul>";
        }elseif($niveau_precedent==$niveau){
            $html.="</ul></li>";
        }else{
            $html.="</li>";
        }
        return $html;
    }
}