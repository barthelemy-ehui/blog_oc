<?php
namespace App\Validation;

use App\Exceptions\RuleNotFoundException;

class Validator
{
    const REQUIRED = 'required';
    const EMAIL = 'email';
    const REQUIRED_EMAIL = 'require|email';
    
    
    private $rules;
    
    private $errors = [];
    
    private $datas = [];
    
    private $userInputDatas = [];
    
    const HTTP_POST_METHOD = 'post';
    
    public function validate($type = 'post')
    {
        if($type === self::HTTP_POST_METHOD) {
            $datas = array_keys($this->rules);
            foreach($datas as $data) {
                $rule = $this->rules[$data];
                $validationOption = $this->getValidationsOption($rule);
                $filter = key($validationOption);
                $option = current($validationOption);
                if($result = filter_input(INPUT_POST, $data, $filter, $option)){
                    $this->datas[$data] = $result;
                }else {
                    $this->errors[$data] = $this->getErrorMessage($rule);
                }
            }
        }
        
        if(count($this->errors)>0){
            $this->userInputDatas = $_POST;
            return false;
        }
        return $this->datas;
    }
    
    public function getErrors(){
        return ['errors' => $this->errors, 'datas' => $this->userInputDatas ];
    }
    
    public function addRule($rules){
        if(!is_array($rules)){
            throw new IsNotArrayException();
        }
        $this->rules = $rules;
    }
    
    private function getErrorMessage($key){
        $messages = [
            self::REQUIRED => 'Ce champs est requis',
            self::EMAIL => 'Email invalide',
            self::REQUIRED_EMAIL => 'Email invalide ou champs vide'
        ];
        return $messages[$key];
    }
    
    private function getValidationsOption($ruleName)
    {
        $rulesOptions = [];
            switch($ruleName){
                case self::REQUIRED:
                    $rulesOptions[FILTER_VALIDATE_REGEXP] = [
                        'options' => ['regexp' => '/\w/']
                    ];
                    break;
                case self::EMAIL:
                    $rulesOptions[FILTER_VALIDATE_EMAIL] = [];
                    break;
                case self::REQUIRED_EMAIL:
                    $rulesOptions[FILTER_VALIDATE_REGEXP] = [
                        'options' => ['regexp' => '/\w/'],
                        'filter' => FILTER_VALIDATE_EMAIL,
                    ];
                    break;
                default:
                    throw new RuleNotFoundException();
            }

        return $rulesOptions;
    }
}