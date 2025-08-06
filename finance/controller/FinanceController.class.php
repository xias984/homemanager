<?php
require("./finance/model/Category.class.php");
require("./finance/model/PaymentType.class.php");
require("./auth/model/Auth.class.php");
require("./finance/model/Finance.class.php");

class FinanceController
{
    public function __construct() {
        $this->iduser = $_SESSION['iduser'];
        $this->datetime = date('Y-m-d H:i:s');
        $this->category = new Category();
        $this->paymentType = new PaymentType();
        $this->finance = new Finance();
        $this->user = new Auth();
    }

    public function registerCategory($category) {
        if (!empty($category)) {
            $categoryArray = array(
                "category"  => ucfirst($category),
                "iduser"    => $this->iduser,
                "date"      => $this->datetime
            );

            $this->category->createCategory($categoryArray);

            header("Location: " . refreshPage() . "&idmsg=29");
        }
    }

    public function listCategoryTable() {
        $categoryList = array(
            array('Categoria', 'Inserita da', 'Data inserimento', 'Actions') // Intestazione
        );

        $categoryArray = array();

        if ($this->category->getCategory()) {
            $categoryData = $this->category->getCategory();
            foreach ($categoryData as $category) {
                $categoryArray[] = [
                    ucfirst($category['category']),
                    ucfirst($this->user->getInfoUserById($category['iduser'])['firstname']),
                    date('d/m/Y', strtotime($category['datainserimento'])),
                    $category['id']
                ];
            }
        }
        $categoryArray = array_merge($categoryList, $categoryArray);

        return $categoryArray;
    }

    public function removeCategory($categoryId) {
        if (!empty($categoryId) && isset($categoryId)) {
            if ($this->category->deleteCategoryById($categoryId)) {
                header("Location: " . refreshPage() . "&idmsg=30");
            }
        }
    }

    public function editCategory($categoryId, $categoryPost) {
        $categoryArray = array();

        if (!empty($categoryId)) {
            $categoryData = $this->category->getCategoryById($categoryId);

            $categoryArray = array(
                "id"            =>  $categoryData['id'],
                "category"      =>  ucfirst($categoryPost[0]),
                "userid"        =>  $this->iduser,
                "datamodifica"  =>  $this->datetime
            );

            if ($this->category->updateCategoryById($categoryArray)) {
                header("Location: " . refreshPage() . "&idmsg=31");
            }
        }
    }

    public function selectCategories() {
        $categoriesArray = array();

        foreach ($this->category->getCategory() as $value) {
            $categoriesArray[$value['id']] = $value['category'];
        }
        return $categoriesArray;
    }

    public function registerPaymentType($paymentType) {
        if (!empty($paymentType)) {
            $paymentTypeArray = array(
                "paymenttype"  => strtoupper($paymentType),
                "iduser"    => $this->iduser,
                "date"      => $this->datetime
            );

            $this->paymentType->createPaymentType($paymentTypeArray);

            header("Location: " . refreshPage() . "&idmsg=33");
        }
    }

    public function selectPaymentTypes() {
        $categoriesArray = array();

        foreach ($this->paymentType->getPaymentTypes() as $value) {
            $categoriesArray[$value['id']] = $value['paymenttype'];
        }
        return $categoriesArray;
    }

    public function listPaymentTypeTable() {
        $paymentTypeList = array(
            array('Metodo di pagamento', 'Inserito da', 'Data inserimento', 'Actions') // Intestazione
        );

        $paymentTypeArray = array();

        if ($this->paymentType->getPaymentTypes()) {
            $paymentTypeData = $this->paymentType->getPaymentTypes();
            foreach ($paymentTypeData as $paymentType) {
                $paymentTypeArray[] = [
                    $paymentType['paymenttype'],
                    ucfirst($this->user->getInfoUserById($paymentType['iduser'])['firstname']),
                    date('d/m/Y', strtotime($paymentType['datainserimento'])),
                    $paymentType['id']
                ];
            }
        }
        $paymentTypeArray = array_merge($paymentTypeList, $paymentTypeArray);

        return $paymentTypeArray;
    }

    public function removePaymentType($paymentTypeId) {
        if (!empty($paymentTypeId) && isset($paymentTypeId)) {
            if ($this->paymentType->deletePaymentTypeById($paymentTypeId)) {
                header("Location: " . refreshPage() . "&idmsg=34");
            }
        }
    }

    public function editPaymentType($paymentTypeId, $paymentTypePost) {
        $paymentTypeArray = array();

        if (!empty($paymentTypeId)) {
            $paymentTypeData = $this->paymentType->getPaymentTypeById($paymentTypeId);

            $paymentTypeArray = array(
                "id"            =>  $paymentTypeData['id'],
                "paymenttype"   =>  strtoupper($paymentTypePost[0]),
                "userid"        =>  $this->iduser,
                "datamodifica"  =>  $this->datetime
            );
            
            if ($this->paymentType->updatePaymentTypeById($paymentTypeArray)) {
                header("Location: " . refreshPage() . "&idmsg=31");
            }
        }
    }

    public function registerAmount($amountData) {
        if (!empty($amountData)) {
            $now = date("Y-m-d H:i:s");
            $amountArray = array(
                "iduser" => $amountData['iduser'],
                "typeamount" => $amountData['typeamount'],
                "amount" => $amountData['amount'] ?: 0,
                "description" => $amountData['description'],
                "categoryid" => $amountData['categoryid'],
                "paymenttypeid" => $amountData['paymenttypeid'],
                "paymentdate" => $amountData['paymentdate'] ?: $now
            );

            $this->finance->createTransaction($amountArray);

            header("Location: " . refreshPage() . "&idmsg=32");
        }
    }

    public function selectFinances($filters = null) {
        $financesArray = array();

        foreach ($this->finance->getTransactions($filters) as $value) {
            $financesArray[] = array(
                "id" => $value['id'],
                "user" => $this->user->getInfoUserById($value['userid'])['firstname'],
                "type" => $value['type'],
                "amount" => $value['amount'],
                "description" => $value['description'],
                "category" => isset($value['categoryid']) ? ($this->category->getCategoryById($value['categoryid'])['category'] ?? 'ND') : 'ND',
                "paymenttype" => isset($value['paymenttypeid']) ? ($this->paymentType->getPaymentTypeById($value['paymenttypeid'])['paymenttype'] ?? 'ND') : 'ND',
                "paymentdate" => $value['paymentdate'],
                "payed" => $value['payed']
            );
        }
        return $financesArray;
    }
/*
    public function editUser($userPost) {
        if (!empty($this->userData['editid']) && isset($this->userData['editid'])) {
            $userPost[3] = !empty($userPost[3]) ? 1 : 0;
            
            if ($this->user->updateUserById($this->userData['editid'], $userPost)) {
                header("Location: " . refreshPage() . "&idmsg=26");
            }
        }
    }*/
    public function editTransaction($financeId) {
        if (!empty($financeId) && isset($financeId)) {
            if ($this->finance->updateTransactionById($financeId)) {
                header("Location: " . refreshPage() . "&idmsg=44");
            }
        }
    }

    public function deleteTransaction($financeId) {
        if (!empty($financeId) && isset($financeId)) {
            if ($this->finance->deleteTransactionById($financeId)) {
                header("Location: " . refreshPage() . "&idmsg=43");
            }
        }
    }

    public function payTransaction($financeId) {
        if (!empty($financeId) && isset($financeId)) {
            if ($this->finance->updatePayTransaction($financeId)) {
                header("Location: " . refreshPage() . "&idmsg=36");
            }
        }
    }
}
?>