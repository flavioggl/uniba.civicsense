<?php
require_once dirname(__DIR__) . '/issue/functions.php';

function printTicketStatus($issue) {
    if (!$issue) {
        ?>
        <span class="badge badge-secondary">NON ANCORA GESTITO</span>
        <?php
    } else {
        printIssueStatus($issue);
    }
}

function printTicketPriority($priority) {
    switch ($priority) {
        case \App\Model\Ticket::PRIORITY_GREEN:
            ?>
            <span class="badge badge-success">BASSA</span>
            <?php break;
        case \App\Model\Ticket::PRIORITY_YELLOW:
            ?>
            <span class="badge badge-warning">MEDIA</span>
            <?php break;
        case \App\Model\Ticket::PRIORITY_RED:
            ?>
            <span class="badge badge-danger">ALTA</span>
            <?php break;
    }
}

function hidden($object, $field) {
    return isset($object->hidden) && is_array($object->hidden) && in_array($field, $object->hidden);
}