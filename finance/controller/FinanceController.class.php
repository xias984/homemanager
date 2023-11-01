<?php
require("./finance/model/Category.class.php");
require("./auth/model/Auth.class.php");

class FinanceController
{
    public function __construct() {
        $this->iduser = $_SESSION['iduser'];
        $this->datetime = date('Y-m-d H:i:s');
        $this->category = new Category();
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

            header("Location: " . refreshPageWOmsg() . "&idmsg=29");
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
                header("Location: " . refreshPageWOmsg() . "&idmsg=30");
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
                header("Location: " . refreshPageWOmsg() . "&idmsg=31");
            }
        }
    }

    public function selectCategories() {
        $categoriesArray = array();

        foreach ($this->category->getCategory() as $value) {
            $categoriesArray[] = array(
                "id" => $value['id'],
                "category" => $value['category']
            );
        }
        return $categoriesArray;
    }

    public function registerAmount($amountData) {
        dump($amountData);
        /*
        if (!empty($category)) {
            $categoryArray = array(
                "category"  => ucfirst($category),
                "iduser"    => $this->iduser,
                "date"      => $this->datetime
            );

            $this->category->createCategory($categoryArray);

            header("Location: " . refreshPageWOmsg() . "&idmsg=29");
         */
    }
}
?>