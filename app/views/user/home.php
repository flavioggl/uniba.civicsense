<?php return function () { ?>
    <div class="row justify-content-center">
        <div class="col-sm-6 col-md">
            <div class="card card-home card-home-trouble mb-4">
                <div class="card-img-top"></div>
                <div class="card-body">
                    <h5 class="card-title text-uppercase">Segnala un Problema</h5>
                    <p class="card-text">
                        Sistema con il quale un cittadino può segnalare agevolmente un guasto, problema o
                        malfunzionamento per un soggetto che eroga servizi o gestisce infrastrutture di interesse
                        pubblico.
                    </p>
                    <a href="/ticket/create" class="btn btn-primary">Effettua Segnalazione</a>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-md">
            <div class="card card-home card-home-ticket mb-4">
                <div class="card-img-top"></div>
                <div class="card-body">
                    <h5 class="card-title text-uppercase">Stato Segnalazione</h5>
                    <p class="card-text">
                        Ogni segnalazione genera un ticket con un suo codice di tracking. E' possibile inserire
                        qualsiasi CDT e verificarne lo stato di avanzamanto.
                    </p>
                    <a href="/ticket/search" class="btn btn-primary">Ricerca Ticket</a>
                </div>
            </div>
        </div>
        <div class="col-sm-8 col-md">
            <div class="card card-home card-home-solver mb-4">
                <div class="card-img-top"></div>
                <div class="card-body">
                    <h5 class="card-title text-uppercase">Risoluzione Ticket</h5>
                    <p class="card-text">
                        Gli enti gestiscono le segnalazioni pendenti, visualizzano statistiche di periodo e assegnano i
                        ticket a gruppi di risoluzione che modificano lo stato di avanzamento.
                    </p>
                    <a href="/login" class="btn btn-primary mb-2">Accedi</a><br>
                    <a href="/institution/create" class="btn btn-primary">Registra Azienda/Ente</a>
                </div>
            </div>
        </div>
    </div>
    <?php
};