<?php return function ($object) {
    global $auth;
    /** @var \App\Model\Team $team */
    $team = $object->team;
    $specialist = NULL; ?>
    <div class="row mb-3">
        <div class="col-12 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Componenti Gruppo di Risoluzione</h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
                        <?php foreach ($team->specialists() as $specialist): ?>
                            <li><?= $specialist, $specialist->id === $auth->loggedRow->id ? ' <em>[logged]</em>' : NULL ?></li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Ente/Azienda</h5>
                </div>
                <div class="card-body">
                    <p class="card-text">Partita IVA: <?= $team->institution->vat ?></p>
                    <p class="card-text">Descrizione Servizio: <?= $team->institution->service_description ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mt-4">
        <h2 class="mb-3">SEGNALAZIONI</h2>
        <a href="/issues/<?= \App\Model\Issue::STATUS_PROCESSING ?>" class="btn btn-info">
            In Gestione (<?= $object->count_processing ?>)</a>
        <a href="/issues/CLOSED" class="btn btn-primary">
            Concluse (<?= $object->count_closed ?>)</a>
    </div>
    <?php
};