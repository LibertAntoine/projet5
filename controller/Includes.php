<?php 

namespace controller;

    use \controller\CRUD\GroupCRUD;
    use \controller\CRUD\PostCRUD;
    use \controller\CRUD\CommentCRUD;
    use \controller\CRUD\UserCRUD;
    use \controller\CRUD\LinkGroupCRUD;
    use \controller\CRUD\LinkFriendCRUD;
    use \controller\CRUD\LinkReportingCRUD;

class Includes {

	public function groupBar() {
		if (isset($_SESSION['id'])) {
			$linkGroupCRUD = new LinkGroupCRUD();
			$linkGroups = $linkGroupCRUD->readGroups($_SESSION['id']);
			if ($linkGroups != 'none') {
				$groupCRUD = new GroupCRUD();
				foreach ($linkGroups as $groupId => $link) {
					$group = $groupCRUD->read($groupId);
					if ($group->getPublic() == 1) {
						$groupsPublic[$groupId] = $group;
					} else {
						$groupsPrivate[$groupId] = $group;
					}					
				}
			}	
		} 
		ob_start();
		require('view/include/groupBar.php');
		$groupBar = ob_get_clean();
		return $groupBar;
	}

	public function memberBar() {		
		if (isset($_GET['id'])) {
			$groupCRUD = new GroupCRUD();
			$group = $groupCRUD->read(intval($_GET['id']));
			$linkGroupCRUD = new LinkGroupCRUD();
			$members = $linkGroupCRUD->readMembers(intval($_GET['id']));			
			$userCRUD = new UserCRUD();
			foreach ($members as $memberId => $member) {
				$profils[$memberId] = $userCRUD->read($memberId);
				if ($member->getStatusInt() === 1) {
					$admins[$memberId] = $member;
				} elseif ($member->getStatusInt() === 2) {
					$authors[$memberId] = $member;
				} 
				if ($group->getPublic() == 0) {
					if ($member->getStatusInt() === 3) {
						$commenters[$memberId] = $member;
					} elseif ($member->getStatusInt() === 4) {
						$viewers[$memberId] = $member;
					}
				}
			}
			ob_start();
			require('view/include/memberBar.php');
			$memberBar = ob_get_clean();
			return $memberBar;
		}
	}
}

?>