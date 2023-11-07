<?php
?>
<div class="title">
    <h3>Prospetto Entrate/Uscite</h3>
</div>
<div class="row">
    <div class="col-md-12" style="text-align:center">
        <h5>Filtri ricerca</h5>
        <div class="chat-box p-3">
            <div class="row">
                <div class="col-md-10">
                    <div class="form-group">
                        <input class="form-control" type="textbox" placeholder="Cerca...">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <input class="form-check-input" type="checkbox" name="payed"> Pagato ?
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-box p-3">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="Utente">Utente:</label>
                        <select class="form-control" name="Utente" style="flex:1;" multiple>
                            <option>Daniel Costarelli</option>
                            <option>Bernadette Giordano</option>
                            <option>Martina Costarelli</option>
                            <option>Leonardo Costarelli</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="Utente">Categoria:</label>
                        <select class="form-control" name="Utente" style="flex:1;" multiple>
                            <option>Auto</option>
                            <option>Affitto</option>
                            <option>Stipendio</option>
                            <option>Prestiti</option>
                            <option>Pensione</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="chat-box p-3">
            <div class="row">
                <div class="col-md-6"><div class="form-group">
                        <label for="lasttransactions">Ultimi movimenti:</label>
                        <select class="form-control" name="lasttransactions" style="flex:1;">
                            <option>Ultimi 10 giorni</option>
                            <option>Ultimo mese</option>
                            <option>Ultimi 3 mesi</option>
                            <option>Ultimi 6 mesi</option>
                            <option>Ultimo anno</option>
                        </select>
                    </div></div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="periodo">Periodo:</label>
                        <select class="form-control" name="periodo" style="flex:1;">
                            <option>Novembre</option>
                            <option>Ottobre</option>
                            <option>Settembre</option>
                            <option>Agosto</option>
                            <option>Luglio</option>
                            <option>Giugno</option>
                            <option>Maggio</option>
                            <option>Aprile</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="chat-box p-3">
            <div class="row">
                <div class="col-md-6">
                    <input type="submit" class="form-control btn btn-primary" value="CERCA" style="width:30%;float:right">
                </div>
                <div class="col-md-6">
                    <input type="submit" class="form-control" value="RESET" style="width:30%;float:left">
                </div>
            </div>
        </div>
    </div>
</div>
<div>&nbsp;</div>
<div class="row">
    <div class="col-md-6" style="overflow-x: auto;">
        <h5>Entrate</h5>
        <table class="table responsive">
            <thead>
            <tr>
                <th scope="col">Data pagamento</th>
                <th scope="col">Inserito da:</th>
                <th scope="col">Categoria</th>
                <th scope="col">Importo</th>
                <th scope="col">Note:</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>1/11/2023</td>
                <td>Bernadette Giordano</td>
                <td>Pensione</td>
                <td style="color:green; text-align:right"><strong>+280,00 €</strong></td>
                <td>Invalidità Marty</td>
                <td><a href="#">Modifica</a> - <a href="#">Cancella</a></td>
            </tr>
            <tr>
                <td>10/11/2023</td>
                <td>Daniel Costarelli</td>
                <td>Stipendio</td>
                <td style="color:green; text-align:right"><strong>+2.333,00 €</strong></td>
                <td>Stipendio Nextar</td>
                <td><a href="#">Modifica</a> - <a href="#">Cancella</a></td>
            </tr>
            <tr style="color:grey">
                <td>22/11/2023</td>
                <td>Bernadette Giordano</td>
                <td>Pensione</td>
                <td style="text-align:right">+470,00 €</td>
                <td>Assegno unico</td>
                <td><a href="#">Modifica</a> - <a href="#">Cancella</a></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3"><strong>TOTALE</strong> (previsione)</td>
                <td style="color:green; text-align:right"><strong>+2.613,00 €</strong></td>
                <td colspan="2" style="color:grey">(+3.083,00 €)</td>
            </tr>
            </tfoot>
        </table>
    </div>
    <div class="col-md-6" style="overflow-x: auto;">
        <h5>Uscite</h5>
        <table class="table responsive">
            <thead>
            <tr>
                <th scope="col">Data pagamento</th>
                <th scope="col">Inserito da:</th>
                <th scope="col">Categoria</th>
                <th scope="col">Importo</th>
                <th scope="col">Note:</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>4/11/2023</td>
                <td>Daniel Costarelli</td>
                <td>Auto</td>
                <td style="color:red; text-align:right"><strong>-233,00 €</strong></td>
                <td>Rata macchina</td>
                <td><a href="#">Modifica</a> - <a href="#">Cancella</a></td>
            </tr>
            <tr style="color:grey">
                <td>12/11/2023</td>
                <td>Daniel Costarelli</td>
                <td>Prestiti</td>
                <td style="text-align:right">-800,00 €</td>
                <td>Carta di credito</td>
                <td><a href="#">Modifica</a> - <a href="#">Cancella</a></td>
            </tr>
            <tr style="color:grey">
                <td>12/11/2023</td>
                <td>Daniel Costarelli</td>
                <td>Affitto</td>
                <td style="text-align:right">-630,00 €</td>
                <td></td>
                <td><a href="#">Modifica</a> - <a href="#">Cancella</a></td>
            </tr>
            <tr style="color:grey">
                <td>22/11/2023</td>
                <td>Daniel Costarelli</td>
                <td>Prestito</td>
                <td style="text-align:right">-203,00 €</td>
                <td>Hype</td>
                <td><a href="#">Modifica</a> - <a href="#">Cancella</a></td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <td colspan="3"><strong>TOTALE</strong> (previsione)</td>
                <td style="color:green; text-align:right"><strong>+2.380,00 €</strong></td>
                <td colspan="2" style="color:grey">(+1.217,00 €)</td>
            </tr>
            </tfoot>
        </table>
    </div>
</div>
