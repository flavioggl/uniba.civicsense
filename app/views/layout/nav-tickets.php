<?php return function ($obj) {
    global $auth;
    $code = NULL; ?>
    <div class="container fixed-bottom">
        <div class="btn-group dropup">
            <button id="btnSavedTickets" type="button" class="btn btn-dark dropdown-toggle" data-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false" style="border-radius: 12px 12px 0 0">
                <i class="fas fa-ticket-alt pr-2"></i>
                <span>Ticket Salvati:</span>
                <?= count($auth->getTrackingCodes()) ?>
            </button>
            <div class="dropdown-menu bg-dark py-0">
                <h6 class="dropdown-header border-bottom border-secondary">Ticket Salvati</h6>
                <!-- Dropdown menu links -->
                <?php foreach ($auth->getTrackingCodes() as $code): ?>
                    <a class="dropdown-item  py-1" href="/ticket/<?= $code ?>"><?= $code ?></a>
                <?php endforeach ?>
            </div>
        </div>
    </div>
    <?php
};