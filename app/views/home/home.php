<?php
if (isset($data['ERROR_MSG'])):
	echo '<div class="alert alert-danger">'.$data['ERROR_MSG'].'</div>';
endif;
?>

<table class="table table-striped">
	<thead>
		<tr><th>#</th><th>Nom</th><th>Hôte</th><th>Port</th><th>Statut</th><th>Prochain passage</th><th>Actions</th></tr>
	</thead>
	
	<tbody>
		<form action="" method="post">
			<tr>
				<td></td>
				<td><input type="text" class="form-control" name="name" required placeholder="Nom" /></td>
				<td><input type="text" class="form-control" name="host" required placeholder="Hôte" /></td>
				<td><input class="form-control" type="tel" name="port" size="1" required placeholder="Port" /></td>
				<td></td>
				<td></td>
				<td><input type="submit" class="btn btn-success" value="Ajouter" /></td>
			</tr>
		</form>
		<?php
			foreach ($services as $serv):
				$serv = new Service($serv['id']);
				$style = ($serv->activated) ? 'color:black' : 'color:gray';
				$activate = ($serv->activated) ? '<button class="btn btn-warning" onclick="document.location.href=\''.WEBROOT.'home/active/'.$serv->id.'\'">Désactiver</button>' : '<button class="btn btn-info" onclick="document.location.href=\''.WEBROOT.'home/active/'.$serv->id.'\'">Ré-activer</button>';
				switch ($serv->status * $serv->activated):
					case 0:
						$status = '<span class="label label-default">Indéterminé</span>';
					break;
				
					case 1:
						$status = '<span class="label label-success">Opérationnel</span>';
					break;
					
					case 2:
						$status = '<span class="label label-warning">En attente</span>';
					break;
					
					case 3:
						$status = '<span class="label label-danger">DOWN</span>';
					break;
				endswitch;
				echo '<tr style="'.$style.'">
					<td>'.$serv->id.'</td>
					<td>'.$serv->name.'</td>
					<td>'.$serv->host.'</td>
					<td>'.$serv->port.'</td>
					<td id="status-'.$serv->id.'">'.$status.'</td>
					<td id="next-'.$serv->id.'">'.date('d/m/Y H:i:s', $serv->next_checking_timestamp).'</td>
					<td>'.$activate.' <button class="btn btn-danger" onclick="if(confirm(\'Êtes-vous sur de vouloir supprimer ce service ?\')){document.location.href=\''.WEBROOT.'home/delete/'.$serv->id.'\';}">Supprimer</button></td>
				</tr>';
			endforeach;
		?>
	</tbody>
</table>