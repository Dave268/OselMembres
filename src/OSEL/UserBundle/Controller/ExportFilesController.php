<?php
// src/Osel/UserBundle/Controller/SecurityController.php;

namespace OSEL\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FormType;

class ExportFilesController extends Controller
{
    public function indexAction(Request $request)
    {
        $formData = array();
        $formOptions = $this->get('form.factory')->createBuilder(FormType::class, $formData)
            ->add('ID',         CheckboxType::class, array('required' => false))
            ->add('Email',      CheckboxType::class, array('required' => false))
            ->add('Instrument', CheckboxType::class, array('required' => false))
            ->add('Adress',     CheckboxType::class, array('required' => false))
            ->add('Phone',      CheckboxType::class, array('required' => false))
            ->add('Birthday',   CheckboxType::class, array('required' => false))
            ->add('Emergency',  CheckboxType::class, array('required' => false))
            ->add('Exporter',   SubmitType::class)
            ->getForm();

        if ($request->isMethod('POST'))
        {
            $formOptions->handleRequest($request);
            $formData = $formOptions->getData();

            $id = 0;
            $email = 0;
            $instrument = 0;
            $adress = 0;
            $phone = 0;
            $birthday = 0;
            $emergency = 0;

            if($formData['ID'])
            {
                $id = true;
            }
            if($formData['Email'])
            {
                $email = true;
            }
            if($formData['Instrument'])
            {
                $instrument = true;
            }
            if($formData['Adress'])
            {
                $adress = true;
            }
            if($formData['Phone'])
            {
                $phone = true;
            }
            if($formData['Birthday'])
            {
                $birthday = true;
            }
            if($formData['Emergency'])
            {
                $emergency = true;
            }
            
            return new RedirectResponse($this->generateUrl('osel_user_export', array(
                'id'        => $id,
                'email'     => $email,
                'instrument'=> $instrument,
                'adress'    => $adress,
                'phone'     => $phone,
                'birthday'  => $birthday,
                'emergency' => $emergency)));
        }

        return $this->render('OSELUserBundle:User:export.html.twig', array(
          'form' => $formOptions->createView(),
            'selectedPage'  => 'membres'
        ));
    }

    public function exportAction($id, $email, $instrument, $adress, $phone, $birthday, $emergency)
    {
        $response = new StreamedResponse(function() use ($id, $email, $instrument, $adress, $phone, $birthday, $emergency)
        {

            $em = $this->getDoctrine()->getManager();
            $results = $em->getRepository('OSELUserBundle:User')->getExportQuery()->iterate();
            $handle = fopen('php://output', 'r+');

            $header = array();
            if($id)
            {
                $header[] = 'ID';
            }
            $header[] = 'NOM';
            $header[] = 'PRENOM';
            if($email)
            {
                $header[] = 'MAIL';
            }
            if($instrument)
            {
                $header[] = 'INSTRUMENT';
            }
            if($adress)
            {
                $header[] = 'ADRESSE';
            }
            if($phone)
            {
                $header[] = 'GSM';
            }
            if($birthday)
            {
                $header[] = 'NAISSANCE';
            }
            if($emergency)
            {
                $header[] = 'CONTACT URGENCE';
            }
            fputcsv($handle, $header , ";");
            

            while (false !== ($row = $results->next())) {
                fputcsv($handle, $row[0]->toArray($id, $email, $instrument, $adress, $phone, $birthday, $emergency), ";");
                $em->detach($row[0]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/force-download; charset=utf-8');
        $response->headers->set('Content-Disposition','attachment; filename="export.csv"');

        return $response;
    }

    public function exportContactAction()
    {
        $response = new StreamedResponse(function()
        {

            $em = $this->getDoctrine()->getManager();
            $results = $em->getRepository('OSELUserBundle:User')->getExportQuery()->iterate();
            $handle = fopen('php://output', 'r+');

            $headerList = "First Name,Middle Name,Last Name,Title,Suffix,Initials,Web Page,Gender,Birthday,Anniversary,Location,Language,Internet Free Busy,Notes,E-mail Address,E-mail 2 Address,E-mail 3 Address,Primary Phone,Home Phone,Home Phone 2,Mobile Phone,Pager,Home Fax,Home Address,Home Street,Home Street 2,Home Street 3,Home Address PO Box,Home City,Home State,Home Postal Code,Home Country,Spouse,Children,Manager's Name,Assistant's Name,Referred By,Company Main Phone,Business Phone,Business Phone 2,Business Fax,Assistant's Phone,Company,Job Title,Department,Office Location,Organizational ID Number,Profession,Account,Business Address,Business Street,Business Street 2,Business Street 3,Business Address PO Box,Business City,Business State,Business Postal Code,Business Country,Other Phone,Other Fax,Other Address,Other Street,Other Street 2,Other Street 3,Other Address PO Box,Other City,Other State,Other Postal Code,Other Country,Callback,Car Phone,ISDN,Radio Phone,TTY/TDD Phone,Telex,User 1,User 2,User 3,User 4,Keywords,Mileage,Hobby,Billing Information,Directory Server,Sensitivity,Priority,Private,Categories";
            $header = explode(",", $headerList);

            fputcsv($handle, $header , ",");




            while (false !== ($row = $results->next())) {
                $user = $row[0];

                if($user->getBirthday() != null)
                {
                    $birth = $user->getBirthday()->format('Y-m-d');
                }
                else{
                    $birth = '';
                }

                $userArray = array(
                    $user->getName(),
                    '',
                    $user->getLastname(),
                    '','','','','',
                    $birth,
                    '','','','','','',
                    $user->getEmail(),
                    '','','','',
                    $user->getPhone(),
                    $user->getMobilephone(),
                    '','',
                    $user->getStreet() . ' ' . $user->getNumber() . ' ' . $user->getPostal() . ' ' . $user->getCity(),
                    '','','','','','','',
                    $user->getCountry(),
                    '','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''

                );
                fputcsv($handle, $userArray, ",");
                //fputcsv($handle, $row[0]->toArray(Adèle,,Pierre,,,,,,,,,,,,adele.pierrehenriet@gmail.com,adele_pierre@hotmail.com,,,,,0472 24 93 12,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,Normal,,My Contacts), ",");
                $em->detach($row[0]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'application/force-download; charset=utf-8');
        $response->headers->set('Content-Disposition','attachment; filename="Osel_export_contacts.csv"');

        return $response;
    }
}