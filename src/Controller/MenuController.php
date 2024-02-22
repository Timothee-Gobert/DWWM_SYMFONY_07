<?php

namespace App\Controller;

use App\Entity\Menu;
use App\Form\MenuType;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Location;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MenuController extends AbstractController
{
    #[Route('/menu/delete/{id}', name: 'app_menu_delete')]
    public function delete($id,EntityManagerInterface $em){
    
        $menu=$em->getRepository(Menu::class)->find($id);
        $libelle=$menu->getLibelle();
        $enfants=$menu->getMenus();
        if(count($enfants) != 0){
            $message="Impossible de supprimer le menu $libelle car il contient des sous-menus";
        }else{
            $em->remove($menu); // suppression de l'objet menu
            $em->flush();       // suppression dans la table menu
            $message="Le menu $libelle a bien été supprimé";
        }
        echo $message;
        exit;
    } 

    #[Route('/menu/show/{id}', name: 'app_menu_show')]
    public function show($id,EntityManagerInterface $em, Request $request): Response{

        $menu=$em->getRepository(Menu::class)->find($id);
        return $this->render('menu/show.html.twig',[
            'menu'=>$menu,
        ]);
    }

    #[Route('/menu/edit/{id}', name: 'app_menu_edit')]
    public function edit($id,EntityManagerInterface $em, Request $request): Response{
        $id=(int) $id;
        if($id==0){
            $menu=new Menu();
        }else{
            $menu=$em->getRepository(Menu::class)->find($id);
        }
        $form=$this->createForm(MenuType::class,$menu);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($menu);
            $em->flush();
            return $this->redirectToRoute("app_menu");
        }
        return $this->render('menu/form.html.twig',[
            'form'=>$form->createView()
        ]);
    }

    #[Route('/menu', name: 'app_menu')]
    public function index(EntityManagerInterface $em): Response
    {

        $menus=$em->getRepository(Menu::class)->findBy([],['parent' => 'ASC']);
        $menu=$this->list_menu(null,0,$menus);
        return $this->render('menu/index.html.twig', [
            'menu' => $menu,
            'nbre'=>count($menus),
        ]);
    }

    public function list_menu($parent,$niveau,$menus){
        $html="";
        foreach ($menus as $menu){
            $id=$menu->getId();
            $rang=$menu->getRang();
            $libelle=$menu->getLibelle();
            $url=$menu->getUrl();
            $role=$menu->getRole();
            $parentMenu=$menu->getParent();
            $icone=$menu->getIcone();
            $icone="<i $icone></i>";
            if($parent==$parentMenu){
                $point="";
                for($i=1;$i<=$niveau;$i++){
                    $point.=".......";
                }
                $class=($niveau==0)?"fs-5":"";
                $html.="<tr>";
                $html.="<td class='center'><input  type='checkbox' name='check' value='$id' id='$id' onClick='onlyOne(this)'></td>";
                $html.="<td class='$class'>$point $libelle</td><td>$url</td><td>$icone</td><td>$role</td>";
                $html.="</tr>";
                $html.=$this->list_menu($menu,$niveau+1,$menus);
            }
        }
        return $html;
    }
}