<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Form;

    /**
     * author osama saed
     * base controller 
     */

class BaseController extends AbstractController
{
    /**
     * this function to handle form errors 
     * accept one parameter Form $form
     */
    public function getFormErrors(Form $form): array
    {
        $errors = array();
        foreach ($form->getErrors() as $key => $error) {
            $error = str_replace("ERROR: ","",(string) $error->getMessage);
            $error = str_replace("\n","",$error);
            if ($form->isRoot()) {
                $errors['#'][] = $error->getMessage();
            } else {
                $errors[] = $error->getMessage();
            }
        }
        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $error = str_replace("ERROR: ","",(string) $child->getErrors(true, false));
                $error = str_replace("\n","",$error);
                $errors[$child->getName()] = $error;
            }
        }
       return $errors;
    }
    
}
