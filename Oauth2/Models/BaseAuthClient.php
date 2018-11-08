<?php
namespace Oauth2\Models;

use GapOrm\Mapper\FieldMapper;
use GapOrm\Mapper\Model;

class BaseAuthClient extends Model
{
    /**
     * BaseRefreshToken constructor.
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
        $field = new FieldMapper($this->table(), 'clientSecret', parent::FIELD_TYPE_STR);
        $this->addField($field);
        $field = new FieldMapper($this->table(), 'allowedScopes', parent::FIELD_TYPE_STR);
        $this->addField($field);
        $field = new FieldMapper($this->table(), 'redirectUrls', parent::FIELD_TYPE_STR);
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
        return 'authClients';
    }
}