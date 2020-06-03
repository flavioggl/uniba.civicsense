<?php
use Core\Auth;
use Core\Route;

//errors
Route::get("/401", function () {
    http_response_code(401);
    viewWithMaster("error/401");
});
Route::get("/404", function () {
    http_response_code(404);
    viewWithMaster("error/404");
});
Route::get("/500", function () {
    http_response_code(500);
    viewWithMaster("error/500");
});

//user
Route::both("/logout", "UserController::logout");
Route::get("/login", "UserController::getLogin");
Route::post("/login", "UserController::postLogin");

//home
Route::get("/", function () {
    switch (Auth::Instance()->loggedType) {
        case Auth::TYPE_ADMIN:
            return \App\Controller\AdminController::getHome();
        case Auth::TYPE_CITY:
            return \App\Controller\CityController::getHome();
        case Auth::TYPE_MANAGER:
            return \App\Controller\ManagerController::getHome();
        case Auth::TYPE_SPECIALIST:
            return \App\Controller\SpecialistController::getHome();
    }
    return \App\Controller\AbstractController::view('user/home');
});

//trouble
Route::get('/troubles', "AdminController::getTroubles", Auth::TYPE_ADMIN);
Route::post('/trouble/create', "AdminController::postTroubleCreate", Auth::TYPE_ADMIN);
Route::both('/trouble/{int}/delete', "AdminController::troubleDelete", Auth::TYPE_ADMIN);

//city
Route::get('/cities', "CityController::getList", Auth::TYPE_ADMIN);
Route::get('/city/create', "CityController::getCreate", Auth::TYPE_ADMIN);
Route::post('/city/create', "CityController::postCreate", Auth::TYPE_ADMIN);
Route::get("/city/{int}/update", "CityController::getUpdate", Auth::TYPE_ADMIN);
Route::post("/city/{int}/update", "CityController::postUpdate", Auth::TYPE_ADMIN);
Route::both("/city/{int}/delete", "CityController::delete", Auth::TYPE_ADMIN);
Route::get('/contracts', "CityController::getContracts", Auth::TYPE_CITY);
Route::get('/contract/create', "CityController::getContractCreate", Auth::TYPE_CITY);
Route::post('/contract/create', "CityController::postContractCreate", Auth::TYPE_CITY);
Route::post('/contract/delete', "CityController::postContractDelete", Auth::TYPE_CITY);

//issue
Route::post("/issue/manage", "IssueController::postManage", Auth::TYPE_MANAGER);
Route::post("/issue/close", "SpecialistController::postClose", Auth::TYPE_SPECIALIST);
Route::get("/issue/{int}/map", "IssueController::getMap");
Route::get('/issues/{str}', function ($type) {
    if (Auth::isManager()) {
        return \App\Controller\ManagerController::getIssues($type);
    }
    if (Auth::isSpecialist()) {
        return \App\Controller\SpecialistController::getIssues($type);
    }
    return \App\Controller\AbstractController::errorUnauthorized();
});

//ticket
Route::get("/ticket/create", "TicketController::getCreate");
Route::post("/ticket/create", "TicketController::postCreate");
Route::get("/ticket/search", "TicketController::getSearch");
Route::post("/ticket/search", "TicketController::postSearch");
Route::get('/ticket/{str}', 'TicketController::getDetail');
Route::get("/ticket/{str}/manage", "TicketController::getManage", Auth::TYPE_CITY);
Route::post("/ticket/{str}/manage", "TicketController::postManage", Auth::TYPE_CITY);
Route::get('/tickets/rejected', "CityController::getTicketsRejected", Auth::TYPE_CITY);


//institution
Route::get('/institutions', "AdminController::getInstitutions", Auth::TYPE_ADMIN);
Route::get('/institution/create', 'InstitutionController::getCreate');
Route::post('/institution/create', 'InstitutionController::postCreate');
Route::get("/institution/{int}/update", "InstitutionController::getUpdate", [Auth::TYPE_MANAGER, Auth::TYPE_ADMIN]);
Route::post("/institution/{int}/update", "InstitutionController::postUpdate", [Auth::TYPE_MANAGER, Auth::TYPE_ADMIN]);
Route::both("/institution/{int}/delete", "InstitutionController::delete", Auth::TYPE_ADMIN);

//team
Route::get("/team/list", "TeamController::getList", Auth::TYPE_MANAGER);
Route::get("/team/create", "TeamController::getCreate", Auth::TYPE_MANAGER);
Route::post("/team/create", "TeamController::postCreate", Auth::TYPE_MANAGER);
Route::get("/team/{int}/update", "TeamController::getUpdate", Auth::TYPE_MANAGER);
Route::post("/team/{int}/update", "TeamController::postUpdate", Auth::TYPE_MANAGER);
Route::both("/team/{int}/delete", "TeamController::delete", Auth::TYPE_MANAGER);

//specialist
Route::post('/specialist/add', "TeamController::addSpecialist", Auth::TYPE_MANAGER);

//stats
Route::get('/stats', "ManagerController::getStats", Auth::TYPE_MANAGER);

//ADMIN utils
Route::get('/map-update', function () {
    $map = new \Core\Map();
    foreach (\App\Model\Ticket::getMulti()->where("city_cap", NULL) as $ticket) {
        if (!$details = $map->positionDetails($ticket->latitude, $ticket->longitude)) {
            continue;
        }
        $ticket->city_cap = $details["cap"];
        $ticket->save();
    }
}, Auth::TYPE_ADMIN);
Route::get('/issues-update', function () {
    /** @var \App\Model\Ticket $ticket */
    foreach (\App\Model\Ticket::getMulti()->where("issue_id", NULL) as $ticket) {
        if (!$duplicate = $ticket->searchDuplicate()) {
            echo PHP_EOL, $ticket->code, " no duplicates.";
            continue;
        }
        $duplicate->issue()->bindTicket($ticket);
        echo PHP_EOL, $ticket->code, " binded to ", $duplicate->code;
    }
}, Auth::TYPE_ADMIN);