<?php
use App\Model\Ticket;

include_once __DIR__ . '/functions.php';
return function ($object) {
    $ticket = NULL;
    /** @var $ticket Ticket */?>
    <table class="table table-hover mb-0">
        <thead class="thead-dark">
        <tr>
            <th>CDT</th>
            <?php if (!hidden($object, 'status')): ?>
                <th>Stato</th><?php endif ?>
            <th>Descrizione</th>
            <th>Data di Apertura</th>
            <?php if (!hidden($object, 'closing_datetime')): ?>
                <th>Data di Chiusura</th><?php endif ?>
            <?php if (!hidden($object, 'team')): ?>
                <th>Gruppo di Risoluzione</th><?php endif ?>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($object->tickets as $ticket):
            $issue = $ticket->issue(); ?>
            <tr class="bg-<?= strtolower($ticket->priority) ?>">
                <td><?= $ticket->code ?></td>
                <?php if (!hidden($object, 'status')): ?>
                    <td><?php printTicketStatus($issue) ?></td><?php endif ?>
                <td><?= $ticket->description ?></td>
                <td><?= $ticket->creation_datetime ?></td>
                <?php if (!hidden($object, 'closing_datetime') && $issue): ?>
                    <td><?= $ticket->closing_datetime ?></td><?php endif ?>
                <?php if (!hidden($object, 'team') && $issue): ?>
                    <td><?= implode(", ", $issue->team()->specialists()->toArray()) ?></td><?php endif ?>
                <td class="text-right">
                    <a class="btn btn-sm btn-dark" target="_blank" href="/ticket/<?= $ticket->code ?>">
                        <i class="fas fa-info-circle"></i> Dettaglio</a>
                    <?php if (!$issue): ?>
                        <a href="/ticket/<?= $ticket->code ?>/manage" class="btn btn-sm btn-primary ml-2">
                            <i class="fas fa-tools"></i> Gestisci</a>
                    <?php endif ?>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
    <?php
};