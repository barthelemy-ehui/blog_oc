<?php
namespace App\Validation;

use App\Exceptions\RuleNotFoundException;

class Validator
{
    const REQUIRED = 'required';
    const EMAIL = 'email';
    const REQUIRED_EMAIL = 'required|email';
    const REQUIRED_PASSWORD_COMPARE = 'required|compare';
    
    private $secondPasswordFieldName;
    
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
            self::REQUIRED_EMAIL => 'Email invalide ou champs vide',
            self::REQUIRED_PASSWORD_COMPARE => 'Mot de passe requis ou différent'
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
                case self::REQUIRED_PASSWORD_COMPARE:
                    $rulesOptions[FILTER_CALLBACK] = [
                        'options' => 'self::comparePassword',
                    ];
                    break;
                default:
                    throw new RuleNotFoundException();
            }

        return $rulesOptions;
    }
    
    public function addPasswordToCompare($fieldName) {
        $this->secondPasswordFieldName = $fieldName;
    }
    
    private function comparePassword($password)
    {
        $secondPassword = $_POST[$this->secondPasswordFieldName];
        $isNotEmpty = !empty($password) && !empty($secondPassword);
        if($isNotEmpty && strcmp($password, $secondPassword) === 0){
            return $password;
        }
        return false;
    }
}