<?php
include_once __DIR__ . '/functions.php';
return function ($obj) {
    /** @var \App\Model\Ticket $ticket */
    $ticket = $obj->ticket;
    $city = $ticket->city();
    $issue = $ticket->issue(); ?>
    <div class="card">
        <table class="card-body table table-hover">
            <tbody>
            <tr>
                <th>Codice di Tracking</th>
                <td><?= $ticket->code ?></td>
            </tr>
            <tr>
                <th>Descrizione</th>
                <td><?= $ticket->description ?></td>
            </tr>
            <tr>
                <th>Tipologia Segnalazione</th>
                <td><?= $ticket->trouble()->description ?></td>
            </tr>
            <tr>
                <th>Priorità</th>
                <td><?php printTicketPriority($ticket->priority) ?></td>
            </tr>
            <tr>
                <th>Posizione</th>
                <td>
                    <p><?= $city ? $city->city_name : '<em>indefinito</em>' ?>, <?= $city ? $city->cap : $ticket->city_cap ?></p>
                    <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#modalMap"
                            data-location="<?= $ticket->latitude, ',', $ticket->longitude ?>">
                        <i class="fas fa-map-marked-alt"></i>
                        Visualizza su Maps
                    </button>
                </td>
            </tr>
            <tr>
                <th>Azienda/Ente Gestore</th>
                <td><?php if (!$issue) {
                        print '-';
                    } else {
                        $institution = $issue->institution();
                        print $institution->name . "(" . $institution->service_description . ")";
                    } ?></td>
            </tr>
            <tr>
                <th>Data creazione</th>
                <td><?= $ticket->creation_datetime ?></td>
            </tr>
            <tr>
                <th>Stato d'avanzamento</th>
                <td><?php printTicketStatus($issue) ?></td>
            </tr>
            <?php if ($issue && $issue->closing_datetime): ?>
                <tr>
                    <th>Data Chiusura</th>
                    <td><?= $issue->closing_datetime ?></td>
                </tr>
                <tr>
                    <th>Dettaglio Risoluzione</th>
                    <td><?= $issue->closing_detail ?></td>
                </tr>
            <?php endif ?>
            <?php if ($ticket->video_src): ?>
                <tr>
                    <th>Video</th>
                    <td><?= $ticket->video_src ?></td>
                </tr>
            <?php endif ?>
            </tbody>
        </table>
        <img class="card-img-bottom" src="<?= $ticket->photo_src ?>" alt="<?= $ticket->description ?>">
    </div>
    <?php
};