CREATE PROCEDURE sp_AddMember(IN userLogin VARCHAR(60), 
								IN firstName VARCHAR(40), 
								IN lastName VARCHAR(40), 
								IN userEmail VARCHAR(100), 
								IN sendPressKey VARCHAR(20), 
								IN spListID BIGINT(20), 
								IN metaStatus VARCHAR(100)
)
BEGIN
	# Description:
	#	Procedure that takes values from CSV file and adds users to tables:
	#		users
	#		usermeta
	#		sendpress_subscribers
	#		sendpress_subscribers_meta
	
	# Plugin:
	#	clubusers (Manage Members)
	
	# Application:
	#	admin/members_ajax.php
	
	# Function(s):
	#	function AddMember

	DECLARE output VARCHAR(1000) DEFAULT '';
	DECLARE userExist INT DEFAULT 0;
	DECLARE duplicateUsers INT DEFAULT 0;
	DECLARE userRegistered DATETIME DEFAULT NOW();
	DECLARE userPass VARCHAR(20) DEFAULT '';
	DECLARE userID bigint(20);
	DECLARE sendPressID INT(11);
	SET userPass = fn_randomPassword();
	
	# Add member to users and usermeta if member email is not already in table:
	SELECT COUNT(*) INTO userExist FROM my_users WHERE user_email = userEmail;
	
	IF userExist > 0 THEN
		SET duplicateUsers = duplicateUsers + 1;
	ELSE
		INSERT INTO my_users(user_login, user_pass, user_nicename, user_email, user_registered, user_status, display_name)
		VALUES(userLogin, MD5(userPass), userLogin, userEmail, userRegistered, 0, userLogin);
		
		SELECT LAST_INSERT_ID() INTO userID; # Get user_id for usermeta table
	END IF;
	
	IF userExist < 1 THEN
		INSERT INTO my_usermeta (user_id, meta_key, meta_value)
		VALUES
			(userID, 'nickname', userLogin),
			(userID, 'first_name', firstName),
			(userID, 'last_name', lastName),
			(userID, 'rich_editing', 'true'),
			(userID, 'comment_shortcuts', 'false'),
			(userID, 'admin_color', 'light'),
			(userID, 'use_ssl', '0'),
			(userID, 'show_admin_bar_front', 'true'),
			(userID, 'my_capabilities', 'a:1:{s:10:"subscriber";b:1;}'),
			(userID, 'my_user_level', '0'),
			(userID, 'clubuser_status', metaStatus);
		SET output = 'Added WP User';
	ELSE
		SET output = 'WP User Exists';
	END IF;
	
	# Now add to sendpress_subscribers and my_sendpress_list_subscribers tables:
	SELECT COUNT(*) INTO userExist FROM my_sendpress_subscribers WHERE email = userEmail;
	
	IF userExist < 1 THEN
		# Add to sendpress_subscribers:
		INSERT INTO my_sendpress_subscribers (email, join_date, status, registered, identity_key, firstname, lastname, wp_user_id)
		VALUES(userEmail, userRegistered, 1, userRegistered, sendPressKey, firstName, lastName, userID);
		
		# Get subscriberID for my_sendpress_list_subscribers table:
		SELECT LAST_INSERT_ID() INTO sendPressID;
				
		# Add to my_sendpress_list_subscribers table spListID list:
		INSERT INTO my_sendpress_list_subscribers(listID, subscriberID, status, updated)
		VALUES(spListID, sendPressID, 2, userRegistered);
		
		# Add to my_sendpress_list_subscribers table 'All Members' list:
		INSERT INTO my_sendpress_list_subscribers(listID, subscriberID, status, updated)
		VALUES(160, sendPressID, 2, userRegistered);
		
		SET output = CONCAT(output, ' Added to All Members List');
	END IF;
	
	SET output = CONCAT(output, ' Users already added: ', duplicateUsers);
	SELECT output;
END
