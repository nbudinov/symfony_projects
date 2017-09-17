<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactsController extends Controller
{
    /**
     * @Route("/contacts", name="contacts")
     */
    public function indexAction(Request $request)
    {
//        $contact = new Contact();

        $form = $this->createFormBuilder()
            ->add('name', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-botton:15px')))
            ->add('mobile', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-botton:15px')))
            ->add('email', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-botton:15px')))
//            ->add('category', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-botton:15px')))
            ->add('message', TextareaType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-botton:15px')))
//            ->add('priority', ChoiceType::class, array('choices' => array('Low' => 'Low', 'Normal' => 'Normal', 'High' => 'High'), 'attr' => array('class' => 'form-control', 'style' => 'margin-botton:15px')))
//            ->add('dueDate', DateTimeType::class, array('attr' => array('class' => 'formcontrol', 'style' => 'margin-botton:15px')))
            ->add('save', SubmitType::class, array('attr' => array('label' => 'Create Todo', 'class' => 'btn btn-primary', 'style' => 'margin-botton:15px')))
            ->GetForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $contact = new Contact();

            $now = new\DateTime('now');
            $contact->setName($form['name']->getData());
            $contact->setEmail($form['email']->getData());
            $contact->setMobile($form['mobile']->getData());
            $contact->setMessage($form['message']->getData());
//            $todo->setCreateDate($now->getTimestamp());

            $em = $this->getDoctrine()->getManager();
            $em->persist($contact);
            $em->flush();

            $this->addFlash(
                'notice', 'Your message was successfully sent'
            );

            $message = \Swift_Message::newInstance()
            ->setSubject("subject")
            ->setFrom($contact->getEmail())
            ->setTo('nikkik@mail.bg')
            ->setBody($contact->getMessage());

            $this->get('mailer')->send($message);

            return $this->redirectToRoute('contacts');
        }


//        if ($request->isMethod('POST')) {
////          $form->submit($request->request->get($form->getName()));
//            print_r($request);die;
//            if ($form->isSubmitted() && $form->isValid()) {
//
//
//
//                return $this->redirectToRoute('task_success');
//            }
//        }


//


        // replace this example code with whatever you need
        return $this->render('contacts.html.twig', array('form' => $form->createView()));
    }



}
