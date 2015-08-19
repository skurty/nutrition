<?php

namespace Skurty\NutritionBundle\Core;

class RepositoryCore {
    protected $db;

    protected $table;

    public function setDB($db)
    {
        $this->db = $db;
    }

    public function __construct($db)
    {
        $this->setDB($db);

        $calledClass = explode('\\', get_called_class());
        $class = end($calledClass);
    }

    public function findAll()
    {
        $sql = 'SELECT * FROM ' . $this->table . ' WHERE status = 1';
        return $this->db->fetchAll($sql);
    }

    public function insert($params)
    {
        $params['created'] = date('Y-m-d H:i:s');

        return $this->db->insert($this->table, $params);
    }

    public function update($id, $params)
    {
        $params['updated'] = date('Y-m-d H:i:s');

        return $this->db->update($this->table, $params, array('id' => $id));
    }

    public function delete($id)
    {
        $params = array(
            'updated' => date('Y-m-d H:i:s'),
            'status'  => 0
        );

        return $this->db->update($this->table, $params, array('id' => $id));
    }

    public function findCountByid($id)
    {
        $sql = 'SELECT count(*) as nb FROM ' . $this->table . ' WHERE id = ? AND status = 1';

        $res = $this->db->fetchAssoc($sql, array((int)$id));

        return $res['nb'];
    }

    public function findCountByName($name, $id = null)
    {
        $sql = 'SELECT count(*) as nb FROM ' . $this->table . ' WHERE name = ? AND status = 1';

        $res = $this->db->fetchAssoc($sql, array($name));

        return $res['nb'];
    }
}