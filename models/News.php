<?php

namespace Application\Model;

use Micro\Db\Adapter\Adapter;

class News
{

    protected $db;

    public function __construct(Adapter $db)
    {
        $this->db = $db;
    }

    public function fetchAll()
    {
        $stmt = $this->db->query("SELECT * FROM `news`");
        return $stmt->fetchAll();
    }

    public function find($id)
    {
        $stmt = $this->db->query("SELECT * FROM `news` WHERE id = " . (int) $id);
        return $stmt->fetch();
    }

    public function fetchComments($id)
    {
        $stmt = $this->db->query("SELECT * FROM `comments` WHERE `news_id` = " . (int) $id);
        return $stmt->fetchAll();
    }
    
    public function addComment($id, $data)
    {
        $sql = "INSERT INTO `comments` SET "
             . "`news_id` = " . (int)$id . ", "
             . "`username` = " . $this->db->quote($data['username']). ", "
             . "`text` = " . $this->db->quote($data['text']). ", "
             . "`date_posted` = NOW(), "
             . "`ip` = INET_ATON('" . $data['ip'] . "')";
        
        $this->db->query($sql);
    }

}