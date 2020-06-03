<?php

namespace App\Controller;

use App\Model\Contract;
use App\Model\Institution;
use App\Model\Issue;
use App\Model\Ticket;
use App\Model\Trouble;
use Core\Request;

class TicketController extends AbstractController {

    static function getCreate() {
        return self::view("ticket/create", [
            "title"    => "Nuova Segnalazione",
            "js"       => ['ticket-create'],
            "troubles" => Trouble::getMulti()
        ]);
    }

    static function postCreate() {
        if (!$request = Request::filter(INPUT_POST, Ticket::$rules)) {
            return self::errorPageNotFound();
        }
        if (!$ticket = Ticket::create($request)) {
            return self::errorServer();
        }
        self::auth()->addTrackingCode($ticket->code);
        return redirect("/ticket/" . $ticket->code);
    }

    static function getSearch() {
        return self::view('ticket/search', ["title" => 'Cerca Ticket di Segnalazione']);
    }

    static function postSearch() {
        if (!($code = Request::filter(INPUT_POST, "code", ["required", "max" => 8, "min" => 8])) ||
            !($ticket = Ticket::get(strtoupper($code)))) {
            return self::view('ticket/search', ["title" => 'Cerca Ticket di Segnalazione', 'error' => TRUE]);
        }
        self::auth()->addTrackingCode($ticket->code);
        return self::viewDetail($ticket);
    }

    static function getDetail($code) {
        if (!$ticket = Ticket::get(strtoupper($code))) {
            return self::errorPageNotFound();
        }
        return self::viewDetail($ticket);
    }

    private static function viewDetail(Ticket $ticket) {
        $title = "Ticket " . $ticket->code;
        return self::view('ticket/detail', compact(['title', 'ticket']));
    }

    static function getManage($code) {
        if (!$ticket = Ticket::get(strtoupper($code))) {
            return self::errorPageNotFound();
        }
        return self::view('ticket/manage', [
            'ticket'       => $ticket,
            'title'        => "Gestisci Ticket " . $ticket->code,
            'institutions' => Contract::institutionsOfCity(self::auth()->loggedRow)
        ]);
    }

    static function postManage($code) {
        if (!$ticket = Ticket::get(strtoupper($code))) {
            return self::errorPageNotFound();
        }
        if (!($institutionId = Request::post('institution_id', FILTER_VALIDATE_INT)) ||
            !($institution = Institution::get($institutionId))) {
            return self::errorServer();
        }
        Issue::create($institution)->bindTicket($ticket);
        return redirect('/ticket/' . $ticket->code);
    }

}