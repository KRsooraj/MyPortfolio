<?php
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (isset($_POST["username"]) && isset($_POST["username"])) {
		require_once("../include/dbConnect.php");
		require_once("../include/AnythingHelper.php");
		// include("../include/appData.php");
		$daysLeft = 999;
		$username = strtolower(trim($_POST["username"]));
		$password = trim($_POST["password"]);
		$remember_token = genToken();

		if ($username == '' || $password == '') {
			echo json_encode(array(
				"status" => 0,
				"message" => 'Please enter sign in credentials'
			));
			exit();
		} else {
			$stmt = $db->prepare("SELECT * FROM users WHERE username = ? AND status = 1 AND password = ? LIMIT 0,1");
			$stmt->bindParam(1, $username);
			$stmt->bindParam(2, $password);
			$stmt->execute();
			$usercount = $stmt->rowCount();
			if ($usercount > 0) {
				$user_rows = $stmt->fetch(PDO::FETCH_ASSOC);
				if ($password == $user_rows['password']) {
					$usertoken = $user_rows['token'];
					if ($daysLeft < 0) {
						if ($user_rows['usertype'] == 'admin') {
							echo json_encode(array(
								"status" => 0,
								"message" => 'App licence expired, please contact EWOLWE.'
							));
							exit();
						}
						echo json_encode(array(
							"status" => 0,
							"message" => 'Something went wrong, please contact admin.'
						));
						exit();
					}

					$_SESSION['SESS_USERUNDER'] = $user_rows['token'];
					if ($user_rows['usertype'] == 'user') {
						$_SESSION['SESS_USERUNDER'] = $user_rows['created_by'];
					}

					$_SESSION['SESS_USERID'] = $user_rows['id'];
					$_SESSION['SESS_USERTYP'] = $user_rows['usertype'];
					$_SESSION['SESS_USERTOKEN'] = $user_rows['token'];
					$_SESSION['SESS_USERNAME'] = $user_rows['name'];
					$_SESSION['SESS_TRANS'] = $user_rows['trans_prev'];
					setcookie("LU001", $remember_token, time() + (10 * 365 * 24 * 60 * 60), '/');

					$db->prepare("UPDATE users SET remember_token = '$remember_token' WHERE  token = '$usertoken'")->execute();

					echo json_encode(array(
						"status" => 1,
						"message" => 'Sign in Success'
						echo"completed"
					));
					exit();
				} else {
					echo json_encode(array(
						"status" => 0,
						"message" => 'Invalid sign in credentials'
					));
					exit();
				}
			} else {
				$resultsU = $db->prepare("SELECT * FROM email_verification WHERE email = '$username'");
				$resultsU->execute();
				if ($resultsU->rowCount() > 0) {
					$rowsU = $resultsU->fetch();
					if ($rowsU['verified'] == 1) {
						echo json_encode(array(
							"status" => 0,
							"message" => 'Please contact Superadmin'
						));
						exit();
					} else {
						$verification_code = rand(10000, 99999);
						$db->prepare("UPDATE email_verification SET verification_code = '$verification_code' WHERE  token = '" . $rowsU['token'] . "'")->execute();

						$resultsU2 = $db->prepare("SELECT * FROM users WHERE token = '" . $rowsU['token'] . "'");
						$resultsU2->execute();
						$rowsU2 = $resultsU2->fetch();

						$message = '<h3>Hi ' . ucwords($rowsU2['name']) . ',</h3>Your verification code is <h1>' . $verification_code . '</h1>';
						$mail = new PHPMailer(true);

						try {
							$mail->SMTPDebug = 0;
							$mail->isSMTP();
							$mail->Host       = $mailHost;
							$mail->SMTPAuth   = true;
							$mail->Username   = $mailUsername;
							$mail->Password   = $mailPassword;
							$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
							$mail->Port       = 587;
							$mail->setFrom($mailsetFrom_1, $mailsetFrom_2);
							$mail->addAddress($username, ucwords($rowsU2['name']));

							$mail->Subject = 'Verification Code';
							$mail->Body    = $message;
							$mail->AltBody = $message;

							$mail->send();
						} catch (Exception $e) {
							echo json_encode(array(
								"status" => 0,
								"message" => 'Something went wrong!'
							));
							exit();
						}

						echo json_encode(array(
							"status" => 2,
							"message" => 'Please enter verification code',
							"token" => $rowsU['token']
						));
						exit();
					}
				} else {
					echo json_encode(array(
						"status" => 0,
						"message" => 'Invalid sign in credentials'
					));
					exit();
				}
			}
		}
	} else {
		echo json_encode(array(
			"status" => 0,
			"message" => 'Unauthorized action.'
		));
		exit();
	}
} else {
	echo json_encode(array(
		"status" => 0,
		"message" => 'Unauthorized action.'
	));
	exit();
}
