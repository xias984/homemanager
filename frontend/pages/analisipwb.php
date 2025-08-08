<?php
require("./system/TableHelper.class.php");

$result = fetchDataFromApi('http://192.168.1.16:5000/api/products');

// Prepara i dati per la tabella
$tableData = [
    ['Nome Prodotto', 'Prezzo', 'ASIN', 'Categoria', 'Inserito il', 'Azioni']
];

if ($result && is_array($result)) {
    foreach ($result as $product) {
        $tableData[] = [
            $product['product_name'],
            $product['price'] . ' €',
            $product['asin'],
            $product['category'],
            $product['created_at'],
            '<a href="' . $product['url'] . '" target="_blank"><i class="fa-solid fa-link"></i></a>'
        ];
    }
}

// Inizializza TableHelper per paginazione e ordinamento
$tableHelper = new TableHelper($tableData, 10);
$paginatedData = $tableHelper->getPaginatedData();
?>

<?= Component::createTitle('Lista prodotti PriceWatcherBot') ?>

<!-- Controlli paginazione -->
<div class="row mb-3">
    <div class="col-md-6">
        <?= $tableHelper->getPaginationSummary() ?>
    </div>
    <div class="col-md-6 text-end">
        <?= $tableHelper->getItemsPerPageSelector() ?>
    </div>
</div>

<div class="col-md-12" style="text-align:center; overflow-x: auto;">
    <table class="table responsive">
        <thead>
            <?php foreach ($paginatedData[0] as $key => $header) { ?>
                <th><?= $tableHelper->createSortableHeader($key, $header) ?></th>
            <?php } ?>
        </thead>
        <tbody>
        <?php foreach (array_slice($paginatedData, 1) as $row) { ?>
            <tr>
                <?php foreach ($row as $cell) { ?>
                    <td><?= $cell ?></td>
                <?php } ?>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>

<!-- Paginazione -->
<div class="row mt-3">
    <div class="col-md-12 text-center">
        <?= $tableHelper->createPaginationLinks() ?>
    </div>
</div>
