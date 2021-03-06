<?php

namespace controller;

    use \controller\CRUD\GroupCRUD;
    use \controller\CRUD\PostCRUD;
    use \controller\CRUD\CommentCRUD;
    use \controller\CRUD\UserCRUD;
    use \controller\CRUD\LinkGroupCRUD;
    use \controller\CRUD\LinkFriendCRUD;
    use \controller\CRUD\LinkReportingCRUD;

class Backend {

	function __construct($view, $param = NULL)
    {
        $this->$view($param); 
    }
    
	public function loginView() {
		require('view/backend/loginView.php');
	}

	public function inscriptionView() {
		require('view/backend/inscriptionView.php');
	}

	public function backOfficeView() {
		$includes = new Includes();
		$groupBar = $includes->groupBar();
		require('view/backend/backOfficeView.php');
	}

	public function myGroupView() {
		$linkGroupCRUD = new LinkGroupCRUD();
		$linkGroups = $linkGroupCRUD->readGroups($_SESSION['id']);
		if ($linkGroups != 'none') {
			$groupCRUD = new GroupCRUD();
			foreach ($linkGroups as $groupId => $link) {
				$groups[$groupId] = $groupCRUD->read($groupId);
			}
		}
		require('view/backend/myGroupView.php');
	}

	public function myFriendView() {
		$friendCRUD = new LinkFriendCRUD();
		$listFriends = $friendCRUD->readFriends();
		$userCRUD = new UserCRUD();
		if ($listFriends !== NULL) {
			foreach ($listFriends as $friend) {
 				if ($friend->getLink() === 0) {
					$requests[$friend->getUserId2()] = $userCRUD->read($friend->getUserId2());
				} elseif ($friend->getLink() === 1) {
					if ($friendCRUD->readLink($_SESSION['id'], $friend->getUserId2())) {
						$friends[$friend->getUserId2()] = $userCRUD->read($friend->getUserId2());
					}
				} else {
					throw new Exception('Erreur dans la lecture de la liste d\'amis');
				}	
			}
		}
		$userCRUD = new UserCRUD();
		$allUsers = $userCRUD->readAll();
		if (isset($allUsers)) {
			foreach ($listFriends as $friend) {
				unset($allUsers[$friend->getuserId2()]);
			}
			unset($allUsers[$_SESSION['id']]);
			$includes = new Includes();
			$groupBar = $includes->groupBar();
			require('view/backend/myFriendView.php');
		} else {
			throw new Exception('Impossible de récupérer les utilisateurs');
		}	
	}


	public function newGroupView() {
		require('view/backend/newGroupView.php');
	}

	public function newGroupMemberView() {
		$friendCRUD = new LinkFriendCRUD();
		$linkFriends = $friendCRUD->readFriends();
		if ($linkFriends != 'none') {
			$userCRUD = new UserCRUD();
			foreach ($linkFriends as $friend) {
				if ($friend->getLink() === 1) {
					if ($friendCRUD->readLink($_SESSION['id'], $friend->getUserId2())) {
						$friends[$friend->getUserId2()] = $userCRUD->read($friend->getUserId2());
					}
				}
			}
		}
		if (!isset($_SESSION['admin'])) {
			$_SESSION['admin'] = [];
		}
		if (!isset($_SESSION['author'])) {
			$_SESSION['author'] = [];
		}
		if (!isset($_SESSION['viewer'])) {
			$_SESSION['viewer'] = [];
		}
		if (!isset($_SESSION['commenter'])) {
			$_SESSION['commenter'] = [];
		}
		if (isset($_POST['admin'])) {
			array_push($_SESSION['admin'], serialize($userCRUD->read(intval($_POST['admin']))));
		}
		if (isset($_POST['commenter'])) {
			array_push($_SESSION['commenter'], serialize($userCRUD->read(intval($_POST['commenter']))));
		}
		if (isset($_POST['author'])) {
			array_push($_SESSION['author'], serialize($userCRUD->read(intval($_POST['author']))));
		}
		if (isset($_POST['viewer'])) {
			array_push($_SESSION['viewer'], serialize($userCRUD->read(intval($_POST['viewer']))));
		}		
		if (isset($friends)) {
			$listAdmins = $friends;
			if ($_SESSION['admin'] != NULL) {
				for ($i=0; $i < count($_SESSION['admin']); $i++) {
					$admin = unserialize($_SESSION['admin'][$i]);
					if (isset($listAdmins[$admin->getId()])) {
						unset($listAdmins[$admin->getId()]);
					} else {
						unset($_SESSION['admin'][$i]);
					}
				}
			}		
			$listAuthors = $listAdmins;
			if ($_SESSION['author'] != NULL) {
				for ($i=0; $i < count($_SESSION['author']); $i++) {
					$author = unserialize($_SESSION['author'][$i]);
					if (isset($listAuthors[$author->getId()])) {
						unset($listAuthors[$author->getId()]);
					} else {
						unset($_SESSION['author'][$i]);
					}
				}
			}
			if ($_SESSION['public'] == 0) {
				$listCommenters = $listAuthors;
				if ($_SESSION['commenter'] != NULL) {
					for ($i=0; $i < count($_SESSION['commenter']); $i++) {
						$commenter = unserialize($_SESSION['commenter'][$i]);
						if (isset($listCommenters[$commenter->getId()])) {
							unset($listCommenters[$commenter->getId()]);
						} else {
							unset($_SESSION['commenter'][$i]);
						}	
					}
				}
				$listViewers = $listCommenters;
				if ($_SESSION['viewer'] != NULL) {
					for ($i=0; $i < count($_SESSION['viewer']); $i++) {
						$viewer = unserialize($_SESSION['viewer'][$i]);
						if (isset($listViewers[$viewer->getId()])) {
							unset($listViewers[$viewer->getId()]);
						} else {
							unset($_SESSION['viewer'][$i]);
						}	
					}
				}
			}
		}
		require('view/backend/newGroupMemberView.php');
	}

	public function adminGroupView($groupId) { 
		$groupCRUD = new GroupCRUD();
		$group = $groupCRUD->read($groupId);
		$linkGroupCRUD = new LinkGroupCRUD();
		$members = $linkGroupCRUD->readMembers($groupId);	
		$userCRUD = new UserCRUD();
		foreach ($members as $memberId => $member) {
			$profils[$memberId] = $userCRUD->read($memberId);
			if ($member->getStatusInt() === 1) {
				$admins[$memberId] = $member;
			} elseif ($member->getStatusInt() === 2) {
				$authors[$memberId] = $member;
			} elseif ($member->getStatusInt() === 3) {
				$commenters[$memberId] = $member;
			} elseif ($member->getStatusInt() === 4) {
				$viewers[$memberId] = $member;
			} else {
				throw new Exception('Erreur de profil utilisateur');
			}
		}
		$friendCRUD = new LinkFriendCRUD();
		$linkFriends = $friendCRUD->readFriends();
		if ($linkFriends != 'none') {
			$userCRUD = new UserCRUD();
			foreach ($linkFriends as $friend) {
				if ($friend->getLink() === 1) {
					if ($friendCRUD->readLink($_SESSION['id'], $friend->getUserId2())) {
						$friendId = $friend->getUserId2();
						if (!isset($members[$friendId])) {
							$friends[$friend->getUserId2()] = $userCRUD->read($friend->getUserId2());
						} 
					}
				}
			}
		}
		$commentCRUD = new CommentCRUD();
		$reportingCRUD = new LinkReportingCRUD();
		$links = $reportingCRUD->readLinkGroup($group->getId());
		$includes = new Includes();
		$groupBar = $includes->groupBar();
		require('view/backend/adminGroupView.php');
	} 
}