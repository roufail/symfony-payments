<?php

namespace App\Controller\API;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Attribute\Route;
use App\Gateways\GatewayFactory;
use Symfony\Component\Form\FormFactoryInterface;
use App\Forms\chargeForm;
use Symfony\Component\Validator\Validator\ValidatorInterface;

    /**
     * author osama saed
     * paymentsController to handle api payments endpoints
     */


class paymentsController extends BaseController
{
    private $request;
    private $payment;
    private FormFactoryInterface $formFactory;
    private GatewayFactory $gatewayFactory;

    /*    
     * paymentsController construct
     * parameters $request
     * $form interface
     * $gateway factory
     * this will handle gateway and retrive instance upon gateway parameter 
    */


    public function __construct(RequestStack $requestStack, FormFactoryInterface $formFactory, GatewayFactory $gatewayFactory)
    {
        $this->request = $requestStack->getCurrentRequest();
        $gateway = $this->request->get('gateway');
        $this->gatewayFactory = $gatewayFactory;
        $this->payment = $gatewayFactory->setGateWay($gateway);
        $this->payment = $gatewayFactory->getGateWay();
        $this->formFactory = $formFactory;
    }



    /*    
     * paymentsController list
     * parameters $gateway
     * this will retrive collections of charges
     * $gateway factory
     * return json
    */
    #[Route('/api/payments/{gateway}', name: 'payments',methods: 'GET')]
    public function list($gateway): JsonResponse
    {
        return $this->json($this->payment->fetch());
    }


    /*    
     * paymentsController create
     * parameters $gateway
     * this will create new charge
     * return json with created item
    */
    #[Route('/api/payments/{gateway}/create', name: 'payments_create',methods: 'POST')]
    public function create($gateway): JsonResponse
    {
        $form = $this->formFactory->create(chargeForm::class);
        $this->request->request->replace([$form->getName() => $this->request->request->all()]);
        $form->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->payment->create($form->getData());
            return $this->json($response);
        } 
       $errors = $this->getFormErrors($form);
       return $this->json(['errors' => $errors]);
    }
    
}
