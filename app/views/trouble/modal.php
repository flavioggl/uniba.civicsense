<?php return function ($obj) { ?>
    <div class="modal fade" id="modalTroubleCreate" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Crea Tipologia di Segnalazione</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="/trouble/create" class="modal-body">
                    <div class="form-group">
                        <label for="description">Descrizione</label>
                        <input class="form-control" name="description" id="description" type="text" maxlength="64"
                               required>
                    </div>
                    <button class="btn btn-primary">Aggiungi</button>
                </form>
            </div>
        </div>
    </div>
    <?php
};