-- This adjusts the so-called permanent identifiers to be other more permanent identifiers
-- (CakePHP is still awful)

UPDATE biomarker_datasets SET dataset_id = 'PLCO_Phase_III_Dataset' WHERE dataset_id = 'BGWHCramerPLCOPhaseIIIAnalysis';
UPDATE biomarker_datasets SET dataset_id = 'Pre-PLCO_Phase_II_Dataset' WHERE dataset_id = 'BGWHCramerPrePLCOPhaseIIAnalysis';
UPDATE biomarker_datasets SET dataset_id = 'Autoantibody_Biomarkers' WHERE dataset_id = 'FHCRCHanashAnnexinLamr';
UPDATE biomarker_datasets SET dataset_id = 'Barrett''s_Esophagus_Methylation_Profile_Dataset' WHERE dataset_id = 'HopkinsMeltzerBarrettMethylationProfiles';
UPDATE biomarker_datasets SET dataset_id = 'TSP_Pre-validation_using_Prostate_Rapid_Pre-Validation_Set.' WHERE dataset_id = 'BethIsraelSandaTSPPreval';
UPDATE biomarker_datasets SET dataset_id = 'Prostate_pre-validation_for_hk2,_hk4_and_hk11.' WHERE dataset_id = 'BethIsraelSandaDataMarkerPerformanceSummary';
UPDATE biomarker_datasets SET dataset_id = 'EDRN_WHI_Colon' WHERE dataset_id = 'EDRN_WHI_Colon_Pacific_National_Northwest_Laboratory';
