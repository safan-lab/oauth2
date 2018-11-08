<?php
namespace Oauth2\Models;

use GapOrm\Mapper\FieldMapper;
use GapOrm\Mapper\Model;

class BaseAccessToken extends Model
{
    /**
     * BaseAccessToken constructor.
     */
    public function __construct()
    {
        $field = new FieldMapper($this->table(), 'id', parent::FIELD_TYPE_INT);
        $field->pk(true);
        $field->noinsert(true);
        $field->noupdate(true);
        $this->addField($field);
        $field = new FieldMapper($this->table(), 'clientID', parent::FIELD_TYPE_INT);
        $this->addField($field);
        $field = new FieldMapper($this->table(), 'userID', parent::FIELD_TYPE_INT);
        $this->addField($field);
        $field = new FieldMapper($this->table(), 'token', parent::FIELD_TYPE_STR);
        $this->addField($field);
        $field = new FieldMapper($this->table(), 'expired', parent::FIELD_TYPE_DATETIME);
        $this->addField($field);
        $field = new FieldMapper($this->table(), 'scope', parent::FIELD_TYPE_STR);
        $this->addField($field);
    }

    /**
     * @param string $className
     * @return mixed
     */
    public static function instance($className=__CLASS__)
    {
        return parent::instance($className);
    }

    /**
     * @return string
     */
    public function table()
    {
        return 'accessTokens';
    }
}