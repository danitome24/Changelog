<?php
/**
 * Guido: Open Course Guide and Syllabus for Universities
 * Copyright (C) 2015 SREd Servei de Recursos Educatius <http://www.sre.urv.cat/>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

/**
 * @author Daniel Tomé <danieltomefer@gmail.com>
 * @copyright 2015 Servei de Recursos Educatius (http://www.sre.urv.cat)
 */
class Commit
{

    private $hash;
    private $author;
    private $date;
    private $message;
    private $id;

    public function __construct($hash, $author, $date, $message)
    {
        $this->hash = $hash;
        $this->author = $author;
        $this->date = $date;
        $this->message = $message;
        $positionOfId = substr($this->getMessage(),strpos($this->getMessage(), '#'));
        $this->id = (int)trim(substr($positionOfId, 1, 5));
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }

    /**
     * @return mixed
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param mixed $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
