DELETE FROM #__rsform_component_types WHERE ComponentTypeId = 24;
DELETE FROM #__rsform_component_type_fields WHERE ComponentTypeId = 24;

DELETE FROM #__rsform_config WHERE SettingName = 'recaptcha.private.key';
DELETE FROM #__rsform_config WHERE SettingName = 'recaptcha.public.key';
DELETE FROM #__rsform_config WHERE SettingName = 'recaptcha.theme';
