<?php return function ($object) {
    global $auth;
    $manager = $auth->loggedRow;
    $institution = $object->institution; ?>
    <div class="card">
        <div class="card-body">
            <p class="card-text"><strong>Partita IVA</strong>: <?= $institution->vat ?></p>
            <p class="card-text"><strong>Descrizione Servizio</strong>: <?= $institution->service_description ?></p>
        </div>
        <div class="card-footer">
            <h5 class="card-title">Titolare</h5>
            <p class="card-text"><strong>Cognome e Nome</strong>: <?= $manager->surname, " ", $manager->name ?></p>
            <p class="card-text"><strong>Codice Fiscale</strong>: <?= $manager->tax_code ?></p>
            <p class="card-text"><strong>Data di Nascita</strong>: <?= $manager->birthday ?></p>
        </div>
    </div>
    <br>
    <table class="table table-hover table-bordered">
        <thead class="thead-dark">
        <tr>
            <th>ISSUES/SEGNALAZIONI</th>
            <th># Numero</th>
            <th width="118"></th>
        </tr>
        </thead>
        <tbody>
        <tr class="table-danger">
            <th>NON GESTITE</th>
            <td><?= $object->count_waiting ?></td>
            <td><a href="/issues/WAITING" class="btn btn-sm btn-dark">VISUALIZZA</a></td>
        </tr>
        <tr class="table-warning">
            <th>IN GESTIONE</th>
            <td><?= $object->count_processing ?></td>
            <td><a href="/issues/<?= \App\Model\Issue::STATUS_PROCESSING ?>" class="btn btn-sm btn-dark">VISUALIZZA</a>
            </td>
        </tr>
        <tr class="table-info">
            <th>CHIUSE</th>
            <td><?= $object->count_closed ?></td>
            <td><a href="/issues/CLOSED" class="btn btn-sm btn-dark">VISUALIZZA</a></td>
        </tr>
        </tbody>
    </table>
    <?php
};