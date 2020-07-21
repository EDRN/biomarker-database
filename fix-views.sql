-- CakePHP sucks

ALTER VIEW `search_organs` AS
SELECT `organ_data`.`biomarker_id` AS `biomarker_id`, group_concat(`organs`.`name` separator ', ') AS `organs` 
FROM (`organ_data` join `organs` ON ((`organ_data`.`organ_id` = `organs`.`id`)))
GROUP BY `organ_data`.`biomarker_id`;

