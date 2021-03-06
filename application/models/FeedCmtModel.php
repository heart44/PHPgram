<?php
namespace application\models;
use PDO;

class FeedCmtModel extends Model { 
    //댓글 등록
    public function insFeedCmt(&$param) {
        $sql = "INSERT INTO t_feed_cmt (ifeed, iuser, cmt)
                VALUES (:ifeed, :iuser, :cmt)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":ifeed", $param["ifeed"]);
        $stmt->bindValue(":iuser", $param["iuser"]);
        $stmt->bindValue(":cmt", $param["cmt"]);
        $stmt->execute();

        return intval($this->pdo->lastInsertId());
    }

    //젤 최신 댓글 하나 select
    public function selFeedCmt(&$param) {
        $sql = "SELECT g.*, COUNT(g.icmt) - 1 AS ismore
                FROM (
                    SELECT a.ifeed, a.icmt, a.cmt, a.regdt
                        , b.iuser, b.id AS writer, b.mainimg AS writerimg
                    FROM t_feed_cmt a
                    INNER JOIN t_user b
                    ON a.iuser = b.iuser
                    WHERE a.ifeed = :ifeed
                    ORDER BY a.icmt DESC
                    LIMIT 2
                ) g
                GROUP BY g.ifeed";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":ifeed", $param["ifeed"]);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    //댓글 리스트 select
    public function selFeedCmtList(&$param) {
        $sql = "SELECT a.ifeed, a.icmt, a.cmt, a.regdt
                    , b.iuser, b.id AS writer, b.mainimg AS writerimg
                FROM t_feed_cmt a
                INNER JOIN t_user b
                ON a.iuser = b.iuser
                WHERE a.ifeed = :ifeed
                ORDER BY a.icmt";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(":ifeed", $param["ifeed"]);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}