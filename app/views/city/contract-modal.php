<?php return function ($obj) {
    $institution = NULL; ?>
    <div class="modal fade" id="modalContractCreate" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form class="modal-content" method="post" action="/contract/create">
                <div class="modal-header">
                    <h5 class="modal-title">Nuovo Appalto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="trouble_id">Tipologia Segnalazione</label>
                        <select name="trouble_id" id="trouble_id" class="form-control">
                            <?php foreach ($obj->troubles as $institution): ?>
                                <option value="<?= $institution->id ?>"><?= $institution->description ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="institution_id">Azienda/Ente Gestore</label>
                        <select name="institution_id" id="institution_id" class="form-control">
                            <?php foreach ($obj->institutions as $institution): ?>
                                <option value="<?= $institution->id ?>"><?= $institution->name ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Associa</button>
                </div>
            </form>
        </div>
    </div>
    <?php
};