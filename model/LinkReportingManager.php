<?php

namespace model;

class LinkReportingManager extends DBAccess {

	public function add(LinkReporting $linkReporting) {
		$q = $this->db->prepare("INSERT INTO `projet5_linkreporting` (`userId`, `groupId`, `commentId`, `reportingDate`) VALUES (:userId, :groupId, :commentId, NOW());");

    $q->bindValue(':userId', $linkReporting->getUserId());
    $q->bindValue(':groupId', $linkReporting->getGroupId());
    $q->bindValue(':commentId', $linkReporting->getCommentId());
		$q->execute();
    
    $linkReporting->hydrate([
      'id' => $this->db->lastInsertId()]);
    return $linkReporting;
  }

  public function count() {
    return $this->db->query('SELECT COUNT(*) FROM projet5_linkreporting')->fetchColumn();
  }

  public function existLink($userId, $commentId) {

    $q = $this->db->prepare('SELECT `id`, `userId`, `groupId`, `commentId`, DATE_FORMAT(reportingDate, \'%d/%m/%Y à %Hh%imin%ss\') AS reportingDate FROM projet5_linkreporting WHERE userId = :userId AND commentId = :commentId');
    $q->bindValue(':userId', $userId);
    $q->bindValue(':commentId', $commentId);
    $q->execute();

    $linkReporting = $q->fetch(\PDO::FETCH_ASSOC);
    if ($linkReporting != NULL) {
        return new LinkReporting($linkReporting);
    }
    
  }

  public function delete($userId, $commentId) {
    $q = $this->db->prepare('DELETE FROM projet5_linkreporting WHERE commentId = :commentId AND userId = :userId');
    $q->bindValue(':userId', $userId);
    $q->bindValue(':commentId', $commentId);
    $q->execute();
    return 'ok';
  }

  public function deleteAllComment($commentId) {
    $q = $this->db->prepare('DELETE FROM projet5_linkreporting WHERE commentId = :commentId');
    $q->bindValue(':commentId', $commentId);
    $q->execute();
    return 'ok';
  }

  public function deleteAllUser($userId) {
    $q = $this->db->prepare('DELETE FROM projet5_linkreporting WHERE userId = :userId');
    $q->bindValue(':userId', $userId);
    $q->execute();
    return 'ok';
  }

  public function getComments($commentId) {
    $linkReporting = [];

    $q = $this->db->prepare('SELECT `id`, `userId`, `groupId`, `commentId`, DATE_FORMAT(reportingDate, \'%d/%m/%Y à %Hh%imin%ss\') AS reportingDate FROM projet5_linkreporting WHERE commentId = :commentId ORDER BY reportingDate');
    $q->bindValue(':commentId', $commentId);
    $q->execute();

    while ($data = $q->fetch(\PDO::FETCH_ASSOC)) {
     $linkReporting[] = new LinkReporting($data);
    }
    return $linkReporting;
  }

    public function getLinkGroup($groupId) {
    $linkReporting = [];

    $q = $this->db->prepare('SELECT `id`, `userId`, `groupId`, `commentId`, DATE_FORMAT(reportingDate, \'%d/%m/%Y à %Hh%imin%ss\') AS reportingDate FROM projet5_linkreporting WHERE groupId = :groupId ORDER BY reportingDate');
    $q->bindValue(':groupId', $groupId);
    $q->execute();

    while ($data = $q->fetch(\PDO::FETCH_ASSOC)) {
     $linkReporting[] = new LinkReporting($data);
    }
    return $linkReporting;
  }
  
  public function update(LinkReporting $linkReporting) {
    $q = $this->db->prepare('UPDATE projet5_linkreporting SET commentId = :commentId, userId = :userId , groupId = :groupId WHERE id = :id');
    
    $q->bindValue(':commentId', $linkGroup->getCommentId());
    $q->bindValue(':userId', $linkGroup->getUserId());
    $q->bindValue(':groupId', $linkGroup->getGroupId());
    $q->execute();
  }
}