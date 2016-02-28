<?php

namespace Pixelbonus\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use FOS\RestBundle\View\View;
use Knp\Component\Pager\Pagination\AbstractPagination;
use JMS\Serializer\SerializationContext;
use Pixelbonus\ApiBundle\Exclusion\FieldsExclusionStrategy;
use Pixelbonus\UserBundle\Entity\User;

class ApiController extends Controller {

    /* 
     * Wrapper to quickly return from api method calls with specific
     * errors messages and status codes.
     * 
     * By default a status code of 400 is returned
     */
    protected function api_error($message, $code=400) {
        $msg = array('error' => $message);
        $view = View::create()
            ->setStatusCode($code)
            ->setData($msg);
        return $this->get('fos_rest.view_handler')->handle($view);
    }

    /*
     * Wrapper to quickly return from api method calls and render the data
     * 
     * By default a status code of 200 is returned
     */
    protected function api_response($data, $code=200) {
        if($data instanceof AbstractPagination) {
            $currentPage = $data->getCurrentPageNumber();
            $itemCount = $data->getTotalItemCount();
            $itemsPerPage = $data->getItemNumberPerPage();
            $paginatedData = array(); // Workaround because in Criteria
            foreach($data as $curData) { $paginatedData[] = $curData; }
            $view = View::create()->setStatusCode($code)->setData($paginatedData)
                    ->setHeader('Pagination-Current-Page', $currentPage)
                    ->setHeader('Pagination-Total-Item-Count', $itemCount)
                    ->setHeader('Pagination-Items-Per-Page', $itemsPerPage);
        } else {
            $view = View::create()->setStatusCode($code)->setData($data);
        }
        $view->setHeader('Access-Control-Allow-Origin', '*');
        $serializationContext = SerializationContext::create();
        $serializationContext->setVersion(3);
        // Handle the fields parameter
        if($this->getRequest()->get('fields') !== null) {
            try {
                $serializationContext->addExclusionStrategy(new FieldsExclusionStrategy(
                    $this->getRequest()->get('fields'),
                    $this->container->get('jms_serializer.naming_strategy')
                ));
            } catch(\Exception $e) {
                return $this->api_error('Malformed fields parameter: '.$e->getMessage(), 400);
            }
        }
        // End handling the fields parameter
        $serializationContext->setGroups(array('list'));
        $view->setSerializationContext($serializationContext);
        // Workaround for a JMS serializer bug where the keys are sometimes turned into strings producing {"0": "xyz"} instead of ["xyz"]
        $response = $this->get('fos_rest.view_handler')->handle($view);
        if(((is_array($data) && count($data) > 0 && array_keys($data)[0] == '0') || $data instanceof AbstractPagination) && strlen($response->getContent()) > 0 && $response->getContent()[0] == '{') {
            $data = json_decode($response->getContent());
            $fixedData = array();
            foreach($data as $curData) { $fixedData[] = $curData; };
            $response->setContent(json_encode($fixedData));
        }
        return $response;
    }

    protected function api_convert_to_datetime($fields) {
        if(!isset($fields['hours'])) { $fields['hours'] = '00'; };
        if(!isset($fields['minutes'])) { $fields['minutes'] = '00'; };
        if(!isset($fields['seconds'])) { $fields['seconds'] = '00'; };
        if(!isset($fields['year']) || !isset($fields['month']) || !isset($fields['day'])) { return false; }
        return \DateTime::createFromFormat('Y-m-d H:i:s', $fields['year'].'-'.$fields['month'].'-'.$fields['day'].' '.$fields['hours'].':'.$fields['minutes'].':'.$fields['seconds']);
    }

    protected function api_get_form_errors(\Symfony\Component\Form\Form $form) {
        $errors = array();

        foreach ($form->getErrors() as $key => $error) {
                $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $child) {
            if (!$child->isValid()) {
                $errors[$child->getName()] = $this->api_get_form_errors($child);
            }
        }

        return $errors;
    }

    /*
     * Check if user is authenticated
     *
     * @return bool 
     */
    public function is_authenticated($user) {
        if (!is_object($user) || empty($user) ) {
            return false;
        }
        return true;
    }
}