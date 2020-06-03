<?php

namespace App\Model;

use Core\Query;

class AbstractModelIterator implements \Iterator {

    /** @var Query */
    private $query;
    /** @var bool|\PDOStatement */
    private $stmt;
    /** @var string class */
    private $class;
    /** @var int */
    private $currentIndex;
    /** @var mixed */
    private $currentElem;

    function __construct(Query $query, $class = AbstractModel::class) {
        $this->query = $query;
        $this->class = $class;
        $this->initialize();
    }

    /**
     * Return the current element
     * @link https://php.net/manual/en/iterator.current.php
     * @return AbstractModel Can return any type.
     * @since 5.0.0
     */
    public function current() {
        return $this->currentElem;
    }

    /**
     * Move forward to next element
     * @link https://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next() {
        $this->nextElem();
    }

    /**
     * Return the key of the current element
     * @link https://php.net/manual/en/iterator.key.php
     * @return int scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key() {
        return $this->currentIndex;
    }

    /**
     * Checks if current position is valid
     * @link https://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid() {
        return !is_null($this->currentIndex);
    }

    /**
     * Rewind the Iterator to the first element
     * @link https://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind() {
        $this->initialize();
        $this->nextElem();
    }

    private function initialize() {
        $this->stmt = $this->query->execute();
        $this->currentIndex = -1;
        $this->currentElem = NULL;
    }

    private function nextElem() {
        if (!$properties = $this->stmt->fetch()) {
            $this->currentIndex = NULL;
            return $this->currentElem = NULL;
        }
        $this->currentIndex++;
        return $this->currentElem = new $this->class((array) $properties);
    }

}