<?php
/*
 * Lancez ce fichier via une cron (syntaxe dans cron.sh).
 * Personnellement il se lance toutes les minutes.
 * Ce n'est pas pour ça que tous les services sont vérifiés toutes les minutes:
 * voir les constantes ci-dessous
 */

define('KEY', 'blabla123');  // Clé servant à protéger l'accès à ce fichier
define('INTERVAL', 600); // Interval entre chaque "Ping"
define('CRITICAL_INTERVAL', 60); // Interval en cas de "pré-down" (voir plus bas

/*
 * Envoi une notification PushBullet sur votre compte Pushbullet (logique)
 * NB: J'utilise PushBullet, mais vous pouvez modifier
 * ce code pour utiliser votre propre service voir
 * votre propre application.
 */
function sendPushbulletNotification($title, $content='') {
	$postdata = json_encode(array(
		'type' => 'note',
		'title' => $title,
		'body' => $content,
	));

	shell_exec("curl -u YOUR-PUSHBULLET-KEY: -X POST https://api.pushbullet.com/v2/pushes --header 'Content-Type: application/json' --data-binary '$postdata'");
}

function sendFreeSMS($content){
	/* L'API SMS de Free permet d'envoyer des SMS d'alertes gratuitement sur votre numéro de portable.
	C'est une option gratuite à activer sur http://mobile.free.fr, se connecter, puis mes options, et
	activer "Notifications par SMS".
	Rentrer ci-dessous votre identifiant de compte et la clé de sécurité fournie, et vous serez prêts
	à recevoir des SMS.
	*/
	$url = 'https://smsapi.free-mobile.fr/sendmsg';
	$paramUser = ""; //$paramUser -> votre identifiant à 8 chiffres (
	$paramPass = ""; //$paramPass -> clé alphanumérique d'identification au service.
	$paramMsg  = "[PingPong] ".$content; // Commodité qui me permet de différencier quel programme m'envoie un SMS, car plusieurs de mes programmes utilisent cette API.

	$postdata = json_encode(array(
		"user" => $paramUser,
		"pass" => $paramPass,
		"msg"  => $paramMsg
		));
	shell_exec("curl -k -X POST $url --header 'Content-Type: application/json' --data-binary '$postdata'");
}

// Empêche le script d'être lancé via le web (CLI seulement)
if (isset($argc, $argv)) {

	// Si la clé donnée en argument est correcte
	if (isset($argv[1]) && $argv[1] == KEY) {
		require_once 'system/Database.php';
		require_once 'system/Utils.php';
		$bdd = new Database();

		$req = $bdd->query("SELECT * FROM service WHERE activated=1");
		// Pour chaque service
		foreach ($req as $res) {
			$id = $res['id'];
			$host = $res['host'];
			$port = $res['port'];
			$timeout = 5;

			echo 'Ping ('.$host.':'.$port.') ... ';

			// Si on a dépassé la date de prochain passage pour le service courant
			if (Utils::time() > $res['next_checking_timestamp']) {

				// On teste la réponse du service (le fameux "Ping)
				if (!($fp = @fsockopen($host, $port, $errStr, $timeout))) {
					// Down
					 echo '**DOWN**';

					switch ($res['status']) {
						// Si le service est "En attente"
						case 2:
							// C'est VRAIMENT down, modification du statut en "DOWN", on reviendra checker dans INTERVAL
							$nextCheckTimestamp = Utils::time() + INTERVAL;
							$bdd->query("UPDATE service SET status=3, next_checking_timestamp=$nextCheckTimestamp WHERE id=$id");

							// Envoi de la notif de DOWN
							sendPushbulletNotification($res['name'].': **DOWN**');
						break;

						// Si le service est déjà down
						case 3:
							// On se contente de re-attrendre INTERVAL
							$nextCheckTimestamp = Utils::time() + INTERVAL;
							$bdd->query("UPDATE service SET next_checking_timestamp=$nextCheckTimestamp WHERE id=$id");
						break;

						// Dans tous les autres cas (indéterminé ou opérationnel)
						default:
							// On passe "En attente" (le fameux pré-down), et on reprogramme un passage dans CRITICAL_INTERVAL

							/*
							 * Cette technique est utilisé pour prévenir des "mini-drops"
							 * qui peuvent parvenir sur vos services
							 * cela vous évite d'incessantes notifications
							 * up/down/up/down/up/down
							 */
							$nextCheckTimestamp = Utils::time() + CRITICAL_INTERVAL;
							$bdd->query("UPDATE service SET status=2, next_checking_timestamp=$nextCheckTimestamp WHERE id=$id");
						break;
					}
				}
				else {
					// Up

					// Après le Ping, le Pong...
					echo 'Pong !';

					// Programmation du prochain passage dans INTERVAL
					$nextCheckTimestamp = Utils::time() + INTERVAL;

					// Si le service était DOWN
					if ($res['status'] == 3) {
						// Envoi de la notif d'UP
						sendPushbulletNotification($res['name'].': **UP**');
					}

					$bdd->query("UPDATE service SET status=1, next_checking_timestamp=$nextCheckTimestamp WHERE id=$id");
				}
			}
			else {
				// Ce n'est pas encore le tour de ce service
				echo 'Not yet !';
			}

			echo "\n";
		}
	}
	else {
		// Pour les STMG qui savent pas se servir d'un script
		echo 'Usage: php cron.php <auth key>'."\n";
	}
}
else {
	// Si le script est lancé depuis le web, on insulte gentiment.
	header('HTTP/1.1 403 Forbidden');
	echo '<h1>403 Fordidden</h1>';
}