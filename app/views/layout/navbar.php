<?php
use Core\Auth;

return function ($object) {
    $auth = Auth::Instance(); ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="/">CIVIC SENSE</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropDownAdvisories" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Segnalazioni/Ticket</a>
                        <div class="dropdown-menu dropdown-menu-left text-left bg-dark"
                             aria-labelledby="navbarDropDownAdvisories">
                            <a class="dropdown-item" href="/ticket/create">Crea</a>
                            <a class="dropdown-item" href="/ticket/search">Ricerca</a>
                            <?php switch ($auth->loggedType) {
                                case Auth::TYPE_CITY:
                                    ?>
                                    <div class="dropdown-divider border-secondary"></div>
                                    <a class="dropdown-item" href="/tickets/rejected">Anomali/Rigettati</a>
                                    <?php break;
                                case Auth::TYPE_MANAGER:
                                case Auth::TYPE_SPECIALIST:
                                    ?>
                                    <div class="dropdown-divider border-secondary"></div>
                                    <h6 class="dropdown-header">Lista di Segnalazioni</h6>
                                    <?php if (Auth::isManager()): ?>
                                    <a class="dropdown-item" href="/issues/WAITING">Non Gestite</a>
                                <?php endif ?>
                                    <a class="dropdown-item" href="/issues/<?= \App\Model\Issue::STATUS_PROCESSING ?>">
                                        In Gestione</a>
                                    <a class="dropdown-item" href="/issues/CLOSED">Concluse</a>
                                    <?php break;
                            } ?>
                        </div>
                    </li>
                    <?php switch ($auth->loggedType) {
                        case Auth::TYPE_ADMIN:
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/cities">Città</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/institutions">Aziende</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/troubles">Tipologie Segnalazione</a>
                            </li>
                            <?php break;
                        case Auth::TYPE_CITY:
                            ?>
                            <li class="nav-item">
                                <a class="nav-link" href="/contracts">Appalti</a>
                            </li>
                            <?php break;
                        case Auth::TYPE_MANAGER:
                            ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropDownTeams"
                                   role="button"
                                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Gruppi di
                                    risoluzione</a>
                                <div class="dropdown-menu dropdown-menu-left text-left bg-dark"
                                     aria-labelledby="navbarDropDownTeams">
                                    <a class="dropdown-item" href="/team/list">Lista/Modifica</a>
                                    <a class="dropdown-item" href="/team/create">Nuovo</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/stats">Statistiche</a>
                            </li>
                            <?php break;
                    } ?>
                </ul>


                <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownAccess" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= !$auth->loggedType ? "Accedi" : $auth->loggedRow->email ?>
                            <i class="fas fa-user"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right text-right bg-dark"
                             aria-labelledby="navbarDropdownAccess">
                            <?php if (!$auth->loggedType): ?>
                                <form class="px-3 py-1" action="/login" method="post" style="min-width: 200px">
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-sm" id="email" name="email"
                                               placeholder="eMail" maxlength="64" width="30" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-sm" id="password"
                                               name="password" placeholder="Password" required>
                                    </div>
                                    <button type="submit" class="btn btn-sm btn-warning">Login</button>
                                </form>
                            <?php else: ?>
                                <?php if ($auth::isManager()) : ?>
                                    <a class="dropdown-item" href="/institution/<?= $auth->loggedRow->id ?>/update">
                                        Modifica
                                        <i class="fas fa-sign-out-alt"></i>
                                    </a>
                                <?php endif ?>
                                <a class="dropdown-item" href="/logout">
                                    Esci
                                    <i class="fas fa-sign-out-alt"></i>
                                </a>
                            <?php endif ?>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <?php
};