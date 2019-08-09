/* Update the old parameter names */

UPDATE `#__modules` SET `params` = REPLACE(`params`, '"source_selection":"art"', '"source_selection":"items"') WHERE `module` = 'mod_raxo_allmode';
UPDATE `#__modules` SET `params` = REPLACE(`params`, '"source_selection":"cat","source_cat":[""]', '"source_selection":"all"') WHERE `module` = 'mod_raxo_allmode';
UPDATE `#__modules` SET `params` = REPLACE(`params`, '"source_selection":"cat"', '"source_selection":"selected","category_filter":"1"') WHERE `module` = 'mod_raxo_allmode';

UPDATE `#__modules` SET `params` = REPLACE(`params`, '"source_cat":', '"selected_categories":') WHERE `module` = 'mod_raxo_allmode';
UPDATE `#__modules` SET `params` = REPLACE(`params`, '"source_art":', '"selected_items":') WHERE `module` = 'mod_raxo_allmode';

UPDATE `#__modules` SET `params` = REPLACE(`params`, '"show_featured":', '"featured_items":') WHERE `module` = 'mod_raxo_allmode';
UPDATE `#__modules` SET `params` = REPLACE(`params`, '"layout":', '"module_layout":') WHERE `module` = 'mod_raxo_allmode';
UPDATE `#__modules` SET `params` = REPLACE(`params`, '"moduleclass_sfx":', '"module_class":') WHERE `module` = 'mod_raxo_allmode';
UPDATE `#__modules` SET `params` = REPLACE(`params`, '"exclude_art":', '"current_item":"1","exclude_items":') WHERE `module` = 'mod_raxo_allmode';
