<?php return function ($obj) { ?>
    <div class="row text-center">
        <div class="col-sm">
            <a href="/contracts" class="btn btn-lg btn-primary">
                <i class="fas fa-file-contract mr-2"></i>
                Appalti<br>
                <small>
                    Automatizzazione della gestione dei ticket attraverso associazioni fra
                    <em>"Tipologie Segnalazione"</em> ed <em>"Enti Gestori/Aziende"</em>.
                </small>
            </a>
        </div>
        <div class="col-sm">
            <a href="/tickets/rejected" class="btn btn-lg btn-danger">
                <i class="fas fa-exclamation-circle mr-2"></i>
                Ticket Anomali o Rigettati<br>
                <small>Lista dei ticket non gestibili dagli attuali appalti impostati o rigettati.</small>
            </a>
        </div>
    </div>
    <?php
};