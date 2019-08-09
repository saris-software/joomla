function ajaxValidationRecaptcha(task, formId, data, componentId) {
	switch (task) {
		case 'beforeSend':
			
		break;
		
		case 'afterSend':
			if (data.response[1] && data.response[1].indexOf(componentId) > -1) {
				Recaptcha.reload();
			}
		break;
	}
}