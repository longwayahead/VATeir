<?php
class Notifications {
	private $_db,
			$_data = array();

	public function __construct() {
		$this->_db = DB::getInstance();
	}
	public function getList($type, $id) { //type is 0=individual, 1=group. id is id of whichever of the two as in database column.
		$not = $this->_db->query("SELECT notifications.id AS notification_id, notifications.type, notifications.from, notifications.to_type, notifications.to, notifications.submitted, notifications.status,
									notification_types.id, notification_types.group, notification_types.name AS type_name, notification_types.sort,
									controllers.id, controllers.first_name, controllers.last_name, controllers.email
			FROM notifications
			LEFT JOIN notification_types ON notifications.type = notification_types.id
			LEFT JOIN controllers ON notifications.from = controllers.id
			WHERE (notifications.status = 0)
				AND (notifications.to_type = ?
				AND notifications.to = ?
				OR notifications.from = ?)
				AND notifications.status = 0
			ORDER BY notification_types.sort DESC, notifications.submitted ASC", [[$type, $id, $id]]);
	
		if($not->count()) {
			return $not->results();
		}
	}

	public function getNotification($id) { //serves up the notification if the cid has permission to view it
		$user = new User;
		$pre = $this->_db->query("SELECT notifications.id AS notification_id, notifications.type, notifications.from, notifications.to_type, notifications.to, notifications.submitted, notifications.status,
									notification_types.id, notification_types.group, notification_types.name AS type_name, notification_types.sort,
									notification_groups.id, notification_groups.name, notification_groups.permission_required, notification_groups.sort,
									controllers.id, controllers.first_name, controllers.last_name, controllers.email
			FROM notifications
			LEFT JOIN notification_types ON notifications.type = notification_types.id
			LEFT JOIN notification_groups ON notifications.to = notification_groups.id
			LEFT JOIN controllers ON controllers.id = notifications.from
			WHERE notifications.id = ? OR notifications.to = ?", [[$id, $id]]);
		
		if(!$pre->count()) {
			throw new Exception("No record found for that ID");
		} else {
			$this->_data = $pre->first();

			if((($user->data()->id == $this->_data->from) || ($user->data()->id == 0 && $user->data()->id == $this->_data->to)) || ($user->hasPermission($this->_data->permission_required))) {
				return $this->_data;
			}
			throw new Exception("Invalid permissions to view that record");
		}	
	}
	public function getComments($id) { //notification id
		$comments = $this->_db->query("SELECT notifications_comments.id, notifications_comments.notification_id, notifications_comments.submitted, notifications_comments.submitted_by, notifications_comments.text,
										controllers.id, controllers.first_name, controllers.last_name, controllers.email
		FROM notifications_comments
		LEFT JOIN controllers ON controllers.id = notifications_comments.submitted_by
		WHERE notifications_comments.notification_id = ?
		ORDER BY notifications_comments.submitted ASC", [[$id]]);
		if($comments->count()) {
			return $comments->results();
		} else {
			return false;
		}
	}

	public function exists($type, $cid) {
		$exists = $this->_db->query("SELECT * FROM notifications WHERE type = ? AND `from` = ? AND status = 0", [[$type, $cid]]);
		if($exists->count()) {
			return $exists->first()->id;
		}
		return false;
	}

	public function add($fields = array()) {
		if(!$this->_db->insert('notifications', $fields)) {
			throw new Exception('There was a problem adding a notification.');
		}
		return $this->_db->query("SELECT MAX(id) AS id FROM notifications")->first()->id;
	}

	public function addComment($fields = array()) {
		if(!$this->_db->insert('notifications_comments', $fields)) {
			throw new Exception('There was a problem adding a comment.');
		}
	}

	public function edit($fields, $where) {
		if(!$this->_db->update('notifications', $fields, $where)) {
			throw new Exception('There was a problem updating the notification.');
		}
		return true;
	}
}