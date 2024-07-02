<?php
$result = fetchDataFromApi('http://192.168.1.16:5000/api/products');
?>

<?= Component::createTitle('Lista prodotti PriceWatcherBot') ?>
<div class="col-md-12" style="text-align:center; overflow-x: auto;">
    <table class="table responsive">
        <thead>
            <th>Nome Prodotto</th>
            <th>Prezzo</th>
            <th>ASIN</th>
            <th>Categoria</th>
            <th>Inserito il</th>
            <th></th>
        </thead>
        <tbody>
        <?php foreach ($result as $product) { ?>
            <tr>
                <td><?= $product['product_name'] ?></a></td>
                <td><?= $product['price'] ?> €</td>
                <td><?= $product['asin'] ?></td>
                <td><?= $product['category'] ?></td>
                <td><?= $product['created_at'] ?></td>
                <td><a href="<?= $product['url'] ?>" target="_blank"><i class="fa-solid fa-link"></i></a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
