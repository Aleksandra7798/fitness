<?php

class CustomerHandler extends CustomerDAO
{
    public function __construct()
    {
    }
    
    private $executionFeedback;

    public function getExecutionFeedback()
    {
        return $this->executionFeedback;
    }

    public function setExecutionFeedback($executionFeedback)
    {
        $this->executionFeedback = $executionFeedback;
    }

    public function getAllCustomer()
    {
        if ($this->getAll()) {
            return $this->getAll();
        } else {
            return Util::DB_SERVER_ERROR;
        }
    }

    public function getSingleRow($email)
    {
        if ($this->getByEmail($email)) {
            return $this->getByEmail($email);
        } else {
            return Util::DB_SERVER_ERROR;
        }
    }

    public function getCustomerObj($email)
    {
        $c = new Customer();
        $k = $this->getByEmail($email);
        foreach ($k as $v) {
            $c->setId($v->getId());
            $c->setEmail($v->getEmail());
            $c->setPassword($v->getPassword());
            $c->setPhone($v->getPhone());
            $c->setFullName($v->getFullName());
        }
        return $c;
    }

    public function getCustomerObjByCid($id)
    {
        $c = new Customer();
        $k = $this->getByCid($id);
        foreach ($k as $v) {
            $c->setId($v->getId());
            $c->setEmail($v->getEmail());
            $c->setPassword($v->getPassword());
            $c->setPhone($v->getPhone());
            $c->setFullName($v->getFullName());
        }
        return $c;
    }

    public function getUsername($_email)
    {
        $_fullName = null;
        foreach ($this->getSingleRow($_email) as $obj) {
            $_fullName = $obj->getFullName();
        }
        if ($_fullName != null) {
            return $_fullName;
        } else {
            $positionOfAt = strpos($_email, "@");
            return substr($_email, 0, $positionOfAt);
        }
    }

    public function insertCustomer(Customer $customer)
    {
        //jeśli wartość wynosi 0, co oznacza, że email tego klienta nadal nie jest zarejestrowany
        if ($this->isCustomerExists($customer->getEmail()) == 0) {
            if ($this->insert($customer)) {
                $this->setExecutionFeedback("Zarejestrowałeś się! Zostało Ci utworzone konto klienta. Teraz możesz się zalogować!");
            } else {
                $this->setExecutionFeedback(Util::DB_SERVER_ERROR);
            }
        } else {
            $this->setExecutionFeedback("E-mail już zarejestrowany. Proszę o wpisanie innego adresu.");
        }
    }

    public function updateCustomer(Customer $customer)
    {
        if ($this->isCustomerExists($customer->getEmail()) == 1) {
            if ($this->update($customer)) {
                $this->setExecutionFeedback("Pomyślnie zaktualizowałeś swój profil!");
            } else {
                $this->setExecutionFeedback(Util::DB_SERVER_ERROR);
            }
        } else {
            $this->setExecutionFeedback("Ten e-mail nie jest zarejestrowany.");
        }
    }

    public function deleteCustomer(Customer $customer)
    {
        if ($this->isCustomerExists($customer->getEmail()) == 1) {
            if ($this->delete($customer)) {
                $this->setExecutionFeedback("Pomyślnie usunąłeś swój profil!");
            } else {
                $this->setExecutionFeedback(Util::DB_SERVER_ERROR);
            }
        } else {
            $this->setExecutionFeedback("Ten e-mail nie jest zarejestrowany.");
        }
    }

    public function isPasswordMatchWithEmail($password, Customer $customer)
    {
        $cust = $this->getSingleRow($customer->getEmail())[0];
        if (password_verify($password, $cust->getPassword())) {
            return 'Hasło jest ważne!';
        } else {
            return 'Nieprawidłowe hasło.';
        }
    }

    public function totalCustomersCount() {
       return count($this->getAllCustomer());
    }

    public function doesCustomerExists($email) {
        return $this->isCustomerExists($email);
    }

    public function handleIsAdmin($email) {
        return $this->isAdminCount($email);
    }
}
