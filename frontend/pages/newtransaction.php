<div class="title">
    <h3>Registra Transazione</h3>
</div>
<div class="row">
    <div class="col-md-3">
    </div>
    <div class="col-md-6">
        <form action="" method="post">
            <div class="form-group">
                <label for="typeamount">Tipo importo:</label>
                <select class="form-control" name="typeamount" style="flex:1;">
                    <option value="E">Entrata</option>
                    <option value="U">Uscita</option>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Importo:</label>
                <input type="number" class="form-control" name="amount" required>
            </div>
            <div class="form-group">
                <label for="description">Descrizione:</label>
                <input type="text" class="form-control" name="description" required>
            </div>
            <div class="form-group">
                <label for="categoryid">Categoria:</label>
                <select class="form-control" name="categoryid" style="flex:1;">
                    <option value="1">Casa</option>
                    <option value="2">Famiglia</option>
                    <option value="3">Bolletta</option>
                    <option value="4">Risparmi</option>
                    <option value="5">Macchina</option>
                </select>
            </div>
            <div class="form-group">
                <label for="data">Data di pagamento:</label>
                <input type="date" class="form-control" name="paymentdate">
            </div>
            <button type="submit" class="btn btn-primary">Accedi</button>
        </form>
    </div>
    <div class="col-md-3">
    </div>
</div>