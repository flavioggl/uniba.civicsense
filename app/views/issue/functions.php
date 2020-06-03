<?php
function printIssueStatus($issue) {
    switch ($issue->status) {
        case \App\Model\Issue::STATUS_PROCESSING:
            ?>
            <span class="badge badge-info">IN GESTIONE</span>
            <?php break;
        case \App\Model\Issue::STATUS_SOLVED:
            ?>
            <span class="badge badge-success">RISOLTA</span>
            <?php break;
        case \App\Model\Issue::STATUS_FAILED:
            ?>
            <span class="badge badge-danger">NON RISOLTA</span>
            <?php break;
    }
}