<?php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Doctrine\ORM\EntityManagerInterface;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
#[Route('/client')]
class ClientController extends AbstractController
{
    #[Route('/', name: 'app_client')]
    public function index(ClientRepository $cr): Response
    {
        $clients=$cr->findAll();
        return $this->render('client/index.html.twig', [
            'clients'=>$clients,
            'nbre'=>count($clients),
        ]);
    }
    
    #[Route("/export/excel",name:"app_client_export_excel")]
    public function exportExcel(EntityManagerInterface $em):Response{
        $file="../public/modele-document/modele-fichier-client.xlsx";
        $spreadsheet=IOFactory::load($file);
        $sheet=$spreadsheet->getActiveSheet();
        $clients=$em->getRepository(Client::class)->findAll();
        $row0=3;
        $row=$row0+1;
        foreach($clients as $client){
            $sheet->insertNewRowBefore($row);
            $sheet->setCellValue("A$row",$client->getNumClient());
            $sheet->setCellValue("B$row",$client->getNomClient());
            $sheet->setCellValue("C$row",$client->getAdresseClient());
            $row++;  // $row+=1  //   $row=$row+1;
        }
        $nbre=count($clients);
        $sheet->setCellValue("A$row","Nombre client :$nbre");
        $a_row0=$sheet->getCell("A$row0")->getValue();
        if(!$a_row0){
            $sheet->removeRow($row0);
        }
        //---------------sauvegarder des donnée dans le fichier liste_clients.xlsx
        $target="../public/partage-document/liste_clients.xlsx";
        $writer=new Xlsx($spreadsheet);
        $writer->save($target);
        echo "Exportation terminée!";
        exit;
        //return $this->redirectToRoute('app_client');

    }

    #[Route('/show/{id}',name:'app_client_show')]
    public function show(ClientRepository $cr,$id){
        $client=$cr->find($id);
        return $this->render("client/show.html.twig",[
            'client'=>$client,
        ]);
    }


    #[Route('/delete/{id}', name: 'app_client_delete')]
    public function delete(EntityManagerInterface $em,$id){
        $client=$em->getRepository(Client::class)->find($id);
        $em->remove($client);
        $em->flush();
        return $this->redirectToRoute("app_client");
    }


    #[Route('/edit/{id}',name:"app_client_edit",methods:["POST","GET"])]
    public function edit(EntityManagerInterface $em,ClientRepository $cr,$id,Request $request){
        $id=(int) $id;
        if($id){  //   $id est different de 0 ou null 
            $client=$cr->find($id);
            // ou  $client=$em->getRepository(Client::class)->find($id);
        }else{
            $client=new Client();
        }
        //-------creation du form à partir de ClientType sur l'entity Client = $client
        $form=$this->createForm(ClientType::class,$client);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $em->persist($client);  //  hidrater l'entité $client
            $em->flush();  //   enregistrer les données saisie dans la base de données
            return $this->redirectToRoute("app_client");

        }

        return $this->render("client/form.html.twig",[
            'form'=>$form->createView(),
        ]);
    }


}
