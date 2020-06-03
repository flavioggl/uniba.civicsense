<?php
namespace App\Model;


use Core\Query;

class Issue extends AbstractModel {

    const STATUS_PROCESSING = 'PROCESSING';
    const STATUS_SOLVED = 'SOLVED';
    const STATUS_FAILED = 'FAILED';

    /**
     * @return string
     */
    static function tableName() {
        return "issues";
    }

    static function fieldRules() {
        return [
            Institution::FK => ["required", "int"],
            "status"        => ["values" => [self::STATUS_SOLVED, self::STATUS_PROCESSING, self::STATUS_FAILED]]
        ];
    }

    /**
     * @param Institution $institution
     * @param Team $team
     * @return Issue|bool
     */
    static function create(Institution $institution, Team $team = NULL) {
        $values = [
            Institution::FK => $institution->id,
            "status"        => self::STATUS_PROCESSING
        ];
        if ($team) {
            $values["team_id"] = $team->id;
        }
        return self::insert($values);
    }

    /**
     * @param Ticket $ticket
     * @return Issue|bool
     */
    static function createFromTicket(Ticket $ticket) {
        if (!$institution = Contract::firstInstitutionFromTicket($ticket)) {
            return FALSE;
        }
        return self::create($institution, $institution->firstFreeTeam())->bindTicket($ticket);
    }

    /**
     * @return Query
     */
    static function waiting() {
        return self::select()
            ->where("team_id", NULL)
            ->where("status", self::STATUS_PROCESSING);
    }

    /**
     * @return Query
     */
    static function processing() {
        return self::select()
            ->whereNot("team_id", NULL)
            ->where("status", self::STATUS_PROCESSING);
    }

    /**
     * @return Query
     */
    static function closed() {
        return self::select()
            ->whereNot("team_id", NULL)
            ->where("status", [self::STATUS_FAILED, self::STATUS_SOLVED]);
    }


    function trouble() {
        return Trouble::get($this->trouble_id);
    }

    /**
     * @return Institution
     */
    function institution() {
        return Institution::get($this->institution_id);
    }

    /**
     * @return Team|null
     */
    function team() {
        return $this->team_id ? Team::get($this->team_id) : NULL;
    }

    /**
     * @return Collection|Ticket[]
     */
    function tickets() {
        return Ticket::getMulti(["issue_id" => $this->id]);
    }

    /**
     * @param Ticket $ticket
     * @return $this
     */
    function bindTicket(Ticket $ticket) {
        $ticket->issue_id = $this->id;
        $ticket->save();
        return $this;
    }

    /**
     * @param $status string
     * @param $detail string
     * @return bool
     */
    function close($status, $detail) {
        if (!in_array($status, [self::STATUS_FAILED, self::STATUS_SOLVED])) {
            return FALSE;
        }
        $this->status = $status;
        $this->closing_datetime = now()->toDateTimeString();
        $this->closing_detail = $detail;
        return $this->save();
    }

}