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

    /**
     * Ottiene la lista categorie con paginazione e ordinamento lato database
     */
    public function listCategoryTablePaginated($params = []) {
        $result = $this->category->getCategoryPaginated($params);
        
        $categoryList = array(
            array('Categoria', 'Inserita da', 'Data inserimento', 'Actions') // Intestazione
        );

        $categoryArray = array();
        if ($result['data']) {
            foreach ($result['data'] as $category) {
                $categoryArray[] = [
                    ucfirst($category['category']),
                    ucfirst($this->user->getInfoUserById($category['iduser'])['firstname']),
                    date('d/m/Y', strtotime($category['datainserimento'])),
                    $category['id']
                ];
            }
        }
        $categoryArray = array_merge($categoryList, $categoryArray);

        return [
            'data' => $categoryArray,
            'pagination' => $result['pagination']
        ];
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

    /**
     * Ottiene la lista metodi di pagamento con paginazione e ordinamento lato database
     */
    public function listPaymentTypeTablePaginated($params = []) {
        $result = $this->paymentType->getPaymentTypesPaginated($params);
        
        $paymentTypeList = array(
            array('Metodo di pagamento', 'Inserito da', 'Data inserimento', 'Actions') // Intestazione
        );

        $paymentTypeArray = array();
        if ($result['data']) {
            foreach ($result['data'] as $paymentType) {
                $paymentTypeArray[] = [
                    $paymentType['paymenttype'],
                    ucfirst($this->user->getInfoUserById($paymentType['iduser'])['firstname']),
                    date('d/m/Y', strtotime($paymentType['datainserimento'])),
                    $paymentType['id']
                ];
            }
        }
        $paymentTypeArray = array_merge($paymentTypeList, $paymentTypeArray);

        return [
            'data' => $paymentTypeArray,
            'pagination' => $result['pagination']
        ];
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
            $iduser = $amountData['iduser'] ?? null;
            $typeamount = $amountData['typeamount'] ?? null;
            $amount = $amountData['amount'] ?? null;
            $description = $amountData['description'] ?? null;
            $categoryid = $amountData['categoryid'] ?? null;
            $paymenttypeid = $amountData['paymenttypeid'] ?? null;
            $paymentdate = $amountData['paymentdate'] ?? null;
            
            // Verifica se è una transazione rateizzata
            $installment = isset($amountData['installment']) && $amountData['installment'] === '1'; // La checkbox invia '1' se selezionata
            $installmentEndDate = $amountData['installmentenddate'] ?? null;
            if ($installment && !empty($installmentEndDate)) {
                $success = $this->finance->registerInstallmentAmount(
                    $iduser,
                    $typeamount,
                    $amount,
                    $description,
                    $categoryid,
                    $paymenttypeid,
                    $paymentdate,
                    $installmentEndDate
                );
            } else {
                $amountArray = array(
                    "iduser" => $iduser,
                    "typeamount" => $typeamount,
                    "amount" => $amount ?: 0,
                    "description" => $description,
                    "categoryid" => $categoryid,
                    "paymenttypeid" => $paymenttypeid,
                    "paymentdate" => $paymentdate ?: date("Y-m-d H:i:s"),
                    "installment_end_date" => null
                );
                $success = $this->finance->createTransaction($amountArray);
            }

            if ($success) {
                header("Location: " . refreshPage() . "&idmsg=32");
            } else {
                header("Location: " . refreshPage() . "&idmsg=46");
            }
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

    /**
     * Ottiene le transazioni finanziarie con paginazione e ordinamento lato database
     */
    public function selectFinancesPaginated($filters = null, $params = []) {
        $result = $this->finance->getTransactionsPaginated($filters, $params);
        
        $financesArray = array();
        if ($result['data']) {
            foreach ($result['data'] as $value) {
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
        }
        
        return [
            'data' => $financesArray,
            'pagination' => $result['pagination']
        ];
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
            $transaction = $this->finance->getTransactionById($financeId);
            return $transaction;
        }
    }

    public function updateTransaction($financeId, $transactionData) {
        if (!empty($financeId) && isset($financeId) && !empty($transactionData)) {
            $updateArray = array(
                "id" => $financeId,
                "type" => $transactionData['type'],
                "amount" => $transactionData['amount'],
                "description" => $transactionData['description'],
                "categoryid" => $transactionData['categoryid'],
                "paymenttypeid" => $transactionData['paymenttypeid'],
                "paymentdate" => $transactionData['paymentdate']
            );
            
            if ($this->finance->updateTransactionById($updateArray)) {
                header("Location: " . refreshPage() . "&idmsg=44#table-container");
            } else {
                header("Location: " . refreshPage() . "&idmsg=45#table-container");
            }
        }
    }

    public function deleteTransaction($financeId) {
        if (!empty($financeId) && isset($financeId)) {
            if ($this->finance->deleteTransactionById($financeId)) {
                header("Location: " . refreshPage() . "&idmsg=43#table-container");
            }
        }
    }

    public function payTransaction($financeId) {
        if (!empty($financeId) && isset($financeId)) {
            if ($this->finance->updatePayTransaction($financeId)) {
                header("Location: " . refreshPage() . "&idmsg=36#table-container");
            }
        }
    }
}
?>