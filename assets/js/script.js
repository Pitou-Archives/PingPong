// La valeur de jet_lag doit être la même que dans system/Utils::time()
var jet_lag = 0;

// Chargement du module AJAX
function loadAjax() {
	var xhr = null;
	
	if (window.XMLHttpRequest || window.ActiveXObject) {
		if (window.ActiveXObject) {
			try {
				xhr = new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch(e) {
				xhr = new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		else {
			xhr = new XMLHttpRequest(); 
		}
	}
	else {
		alert("Votre navigateur ne supporte pas l'objet XMLHttpRequest");
		return null;
	}
	
	return xhr;
}

// Appeler cette fonction régulièrement pour avoir une mise à jour visuelle de l'état de vos services
function updateServicesStatus() {
	var xhr = loadAjax();
	xhr.onreadystatechange = function() {
		if (xhr.readyState == 4 && (xhr.status == 200 || xhr.status == 0)) {
			var data = JSON.parse(xhr.responseText);
			for (var i = 0; i < data.length; i++) {
				var date = new Date(data[i].next_checking_timestamp * 1000);

				var day = (date.getDate() < 10) ? '0' + date.getDate() : date.getDate();
				var month = parseInt(date.getMonth() + 1);
				month = (month < 10) ? '0' + month : month;
				var hours = date.getHours() - jet_lag;
				hours = (hours < 10) ? '0' + hours : hours;
				var minutes= (date.getMinutes() < 10) ? '0' + date.getMinutes() : date.getMinutes();
				var seconds = (date.getSeconds() < 10) ? '0' + date.getSeconds() : date.getSeconds();

				var str_date = day+'/'+month+'/'+date.getFullYear()+' '+hours+':'+minutes+':'+seconds;
				
				var status;
				switch (data[i].status * data[i].activated) {
					case 0:
						status = '<span class="label label-default">Indéterminé</span>';
					break;
				
					case 1:
						status = '<span class="label label-success">Opérationnel</span>';
					break;
					
					case 2:
						status = '<span class="label label-warning">En attente</span>';
					break;
					
					case 3:
						status = '<span class="label label-danger">DOWN</span>';
					break;
				}

				document.getElementById("status-"+data[i].id).innerHTML = status;
				document.getElementById("next-"+data[i].id).innerHTML = str_date;
			}
		}
	};
	xhr.open("GET", _webroot_+'home/api', true);
	xhr.send(null);
}

// Appel de la fonction updateServiceStatus() toutes les minutes
var timer = setInterval(function(){updateServicesStatus();}, 60000);
