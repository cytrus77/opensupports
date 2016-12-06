<?php
use Respect\Validation\Validator as DataValidator;
DataValidator::with('CustomValidations', true);

class AddStaffController extends Controller {
    const PATH = '/add';

    private $name;
    private $email;
    private $password;
    private $profilePic;
    private $level;
    private $departments;


    public function validations() {
        return [
            'permission' => 'staff_3',
            'requestData' => [
                'name' => [
                    'validation' => DataValidator::length(2, 55)->alpha(),
                    'error' => ERRORS::INVALID_NAME
                ],
                'email' => [
                    'validation' => DataValidator::email(),
                    'error' => ERRORS::INVALID_EMAIL
                ],
                'password' => [
                    'validation' => DataValidator::length(5, 200),
                    'error' => ERRORS::INVALID_PASSWORD
                ],
                'level' => [
                    'validation' => DataValidator::between(1, 3, true),
                    'error' => ERRORS::INVALID_LEVEL
                ]

            ]
        ];
    }

    public function handler() {

        $this->storeRequestData();
        $staff =  new Staff();

        $staffrow = Staff::getDataStore($this->email,'email');

        if($staffrow->isNull()) {
            $staff->setProperties([
                'name'=> $this->name,
                'email' => $this->email,
                'password'=> $this->password,
                'profilePic' => $this->profilePic,
                'level' => $this->level,
                'sharedDepartmentList'=> $this->departments,
            ]);

            $staff->store();

            Response::respondSuccess();
            return;
        }

        Response::respondError(ERRORS::ALREADY_A_STAFF);


    }
    public function storeRequestData() {
        $this->name = Controller::request('name');
        $this->email = Controller::request('email');
        $this->password = Controller::request('password');
        $this->profilePic = Controller::request('profilePic');
        $this->level = Controller::request('level');
        $this->departments = Controller::request('departments');
    }
}