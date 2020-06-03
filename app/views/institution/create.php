<?php return function ($obj) {
    /** @var \App\Model\Institution|null $institution */
    $institution = isset($obj->institution) ? $obj->institution : NULL;
    $manager = $institution ? $institution->manager() : NULL;
    ?>
    <form class="row" method="post">
        <div class="col-sm">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Dati Titolare</h5>
                    <?php view('user/register-fields-user', $manager ? ["user" => $manager] : []) ?>
                    <div class="form-group">
                        <label for="tax_code">Codice Fiscale</label>
                        <input type="text" class="form-control" id="tax_code" name="tax_code" maxlength="24"
                               value="<?= $manager ? $manager->tax_code : NULL ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="birthday">Data di Nascita</label>
                        <input type="date" class="form-control" id="birthday" name="birthday"
                               value="<?= $manager ? $manager->birthday : "1990-01-01" ?>" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Dati Ente/Azienda</h5>
                    <div class="form-group">
                        <label for="institution_name">Ragione Sociale</label>
                        <input type="text" class="form-control" id="institution_name" name="institution_name"
                               placeholder="CivicSense S.r.l." maxlength="45" required
                               value="<?= $institution ? $institution->name : NULL ?>">
                    </div>
                    <div class="form-group">
                        <label for="institution_vat">Partita IVA</label>
                        <input type="text" class="form-control" id="institution_vat" name="institution_vat"
                               maxlength="15" required
                               value="<?= $institution ? $institution->vat : NULL ?>">
                    </div>
                    <div class="form-group">
                        <label for="institution_service_description">Descrizione Servizio</label>
                        <input type="text" class="form-control" id="institution_service_description" maxlength="64"
                               name="institution_service_description" required
                               value="<?= $institution ? $institution->service_description : NULL ?>">
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary"><?= $institution ? "Aggiorna" : "Crea" ?> Ente</button>
                </div>
            </div>
        </div>
    </form>
    <?php
};