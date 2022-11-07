<?php

namespace CorporateBundle\Repository\Admin;

use Doctrine\ORM\EntityRepository;
use CorporateBundle\Entity\CorpoEmployees;

class CorpoEmployeesRepository extends EntityRepository
{
    protected $utils;
    protected $em;

    /**
     * This method will retrieve all Employees of corporate
     *
     * @param
     * @return doctrine object result of Employees or false in case of no data
     */
    public function getEmployeeList($accountId)
    {
        $query = $this->createQueryBuilder('ap')
            ->select('ap, a.name as accountName, u.fullname as userName, d.name as departmentName, c.name as countryName, c.code as countryCode, w.name as cityName')
            ->leftJoin('CorporateBundle:CorpoAccount', 'a', 'WITH', "a.id = ap.accountId")
            ->leftJoin('CorporateBundle:CmsUsers', 'u', 'WITH', "u.id = ap.userId")
            ->leftJoin('CorporateBundle:CorpoDepartments', 'd', 'WITH', "d.id = ap.departmentId")
            ->leftJoin('CorporateBundle:CmsCountries', 'c', 'WITH', "c.code = ap.countryCode")
            ->leftJoin('CorporateBundle:Webgeocities', 'w', 'WITH', "w.id = ap.cityId");
        if ($accountId) {
            $query->where("ap.accountId = :accountId")->setParameter(':accountId', $accountId);
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve all Employees of corporate
     *
     * @param
     * @return doctrine object result of Employees or false in case of no data
     */
    public function getEmployeeAllList($accountId)
    {
        $query = $this->createQueryBuilder('ap')->select('ap');
        if ($accountId) {
            $query->where("ap.accountId = :accountId")->setParameter(':accountId', $accountId);
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result;
        } else {
            return array();
        }
    }

    /**
     * This method will retrieve an Employee of corporate
     *
     * @param
     * @return doctrine object result of Employee or false in case of no data 
     */
    public function getEmployeeById($paramters)
    {
        $query = $this->createQueryBuilder('p')
            ->select("p, w.name as cityName,a.name as accountName, c.id as countryId, c.code as countryCode, c.name as countryName, d.name as departmentName, u.fullname as userName")
            ->leftJoin('CorporateBundle:CorpoAccount', 'a', 'WITH', "a.id = p.accountId")
            ->leftJoin('CorporateBundle:Webgeocities', 'w', 'WITH', "w.id = p.cityId")
            ->leftJoin('CorporateBundle:CmsCountries', 'c', 'WITH', "c.code = p.countryCode")
            ->leftJoin('CorporateBundle:CorpoDepartments', 'd', 'WITH', "d.id = p.departmentId")
            ->leftJoin('CorporateBundle:CmsUsers', 'u', 'WITH', "u.id = p.userId");
        if (isset($paramters['id'])) {
            $query->where("p.id = :ID")->setParameter(':ID', $paramters['id']);
        }
        if (isset($paramters['userId'])) {
            $query->where("p.userId = :userId")->setParameter(':userId', $paramters['userId']);
        }

        $quer   = $query->getQuery();
        $result = $quer->getScalarResult();

        if (!empty($result) && isset($result[0])) {
            return $result[0];
        } else {
            return array();
        }
    }

    /**
     * This method will add an Employee for corporate Approval Flow
     * Note: leaving method as is; as I don't have a way to test saveEmployee() method on addEmployee.
     *
     * @param $parameters   Employee info list
     * @return doctrine result of Employee id or false in case of no data
     */
    public function addEmployee($parameters)
    {
        $this->em = $this->getEntityManager();
        $employee = new CorpoEmployees();
        if (isset($parameters['fname']) && $parameters['fname'] != '') {
            $employee->setFname($parameters['fname']);
        }
        if (isset($parameters['lname']) && $parameters['lname'] != '') {
            $employee->setLname($parameters['lname']);
        }
        if (isset($parameters['mname']) && $parameters['mname'] != '') {
            $employee->setMname($parameters['mname']);
        }
        if (isset($parameters['cityId']) && $parameters['cityId'] != '') {
            $employee->setCityId($parameters['cityId']);
        }
        if (isset($parameters['accountId']) && $parameters['accountId'] != '') {
            $employee->setAccountId($parameters['accountId']);
        }
        if (isset($parameters['countryCode']) && $parameters['countryCode'] != '') {
            $employee->setCountryCode($parameters['countryCode']);
        }
        if (isset($parameters['address']) && $parameters['address'] != '') {
            $employee->setAddress($parameters['address']);
        }
        if (isset($parameters['mobile']) && $parameters['mobile'] != '') {
            $employee->setMobile($parameters['mobile']);
        }
        if (isset($parameters['phone1']) && $parameters['phone1'] != '') {
            $employee->setPhone1($parameters['phone1']);
        }
        if (isset($parameters['phone2']) && $parameters['phone2'] != '') {
            $employee->setPhone2($parameters['phone2']);
        }
        if (isset($parameters['email']) && $parameters['email'] != '') {
            $employee->setEmail($parameters['email']);
        }
        if (isset($parameters['passportNumber']) && $parameters['passportNumber'] != '') {
            $employee->setPassportNumber($parameters['passportNumber']);
        }
        if (isset($parameters['passportExpiryDate']) && $parameters['passportExpiryDate'] != '') {
            $employee->setPassportExpiryDate(new \DateTime($parameters['passportExpiryDate']));
        }
        if (isset($parameters['issueDate']) && $parameters['issueDate'] != '') {
            $employee->setIssueDate(new \DateTime($parameters['issueDate']));
        }
        if (isset($parameters['departmentNameCode']) && $parameters['departmentNameCode'] != '') {
            $employee->setDepartmentId($parameters['departmentNameCode']);
        }
        if (isset($parameters['userNameCode']) && $parameters['userNameCode'] != '') {
            $employee->setUserId($parameters['userNameCode']);
        }
        if (isset($parameters['p_profile_picture']) && $parameters['p_profile_picture'] != '') {
            $employee->setProfilePicture($parameters['p_profile_picture']);
        }
        if (isset($parameters['userId']) && $parameters['userId'] != '') {
            $employee->setUserId($parameters['userId']);
        }
        $this->em->persist($employee);
        $x = $this->em->flush();
        if ($employee) {
            return $employee->getId();
        } else {
            return false;
        }
    }

    /**
     * This method adds/updates an Employee.
     *
     * @param Array $data   The employee data.
     * @return CorpoEmployees
     */
    private function saveEmployee(array $data, $update = false)
    {
        if (!empty($data)) {
            $em = $this->getEntityManager();

            $obj = new CorpoEmployees();

            $where = array();
            if (isset($data['id']) && $data['id'] != '') {
                $where['id'] = $data['id'];
            }

            if (isset($data['updateByThisUserId']) && $data['updateByThisUserId'] != '') {
                $where['userId'] = $data['updateByThisUserId'];
            }

            if (isset($data['updateByThisAccountId']) && $data['updateByThisAccountId'] != '') {
                $where['accountId'] = $data['updateByThisAccountId'];
            }

            if ($update) {
                $obj = $this->findOneBy($where);

                if (!$obj) {
                    return false;
                }
            }

            foreach ($data AS $column => $value) {
                switch (strtolower($column)) {
                    case 'p_profile_picture':
                        $column = 'profile_picture';
                        break;
                    case 'cityid':
                        $column = 'city_id';
                        break;
                    case 'countrycode':
                        $column = 'country_code';
                        break;
                    case 'departmentnamecode':
                        $column = 'department_id';
                        break;
                    case 'usernamecode':
                    case 'updatebythisuserId':
                        $column = 'user_id';
                        break;
                    case 'updatebythisaccountId':
                        $column = 'account_id';
                        break;
                }

                $func = 'set'.str_replace(' ', '', ucwords(preg_replace('/_+/', ' ', $column)));
                if (method_exists($obj, $func)) {
                    $obj->{$func}($value);
                }
            }

            $em->persist($obj);
            $em->flush();

            return $obj;
        }

        return false;
    }

    /**
     * This method will update an Employee.
     *
     * @param Array $data   The employee data.
     * @return doctrine object result of Employee or false in case of no data
     */
    public function updateEmployee($data)
    {
        if ((isset($data['id']) && $data['id']) || ((isset($data['updateByThisAccountId']) && $data['updateByThisAccountId']) || (isset($data['updateByThisUserId']) && $data['updateByThisUserId']))) {
            return $this->saveEmployee($data, true);
        } else {
            return false;
        }
    }

    /**
     * This method will delete an Employee
     *
     * @param
     *            id of Employee
     * @return doctrine object result of Employee or false in case of no data
     */
    public function deleteEmployee($id)
    {
        $this->em = $this->getEntityManager();
        $qb       = $this->em->createQueryBuilder('p')
            ->delete('CorporateBundle:CorpoEmployees', 'p')
            ->where("p.id = :ID")
            ->setParameter(':ID', $id);
        $query    = $qb->getQuery();
        return $query->getResult();
    }
}
