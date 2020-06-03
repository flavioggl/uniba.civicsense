<?php return function ($obj) {
    $institution = NULL; /** @var $institution \App\Model\Institution */ ?>
    <form method="post" class="card mb-3">
        <div class="card-header">Assegna il ticket <?= $obj->ticket->code ?> a un Ente Gestore/Azienda</div>
        <div class="card-body">
            <label for="institution_id">Seleziona Azienda</label>
            <select name="institution_id" id="institution_id" class="form-control">
                <?php foreach ($obj->institutions as $institution): ?>
                    <option value="<?= $institution->id ?>">
                        <?= $institution->name ?>
                        <small>(<?= $institution->service_description ?>)</small>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="card-footer">
            <button class="btn btn-primary">Conferma</button>
        </div>
    </form>
    <?php
    view('ticket/detail', (array) $obj);
};