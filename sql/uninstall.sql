TRUNCATE TABLE `mc_mainsectors`;
DROP TABLE `mc_mainsectors`;

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
        SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'mainsectors'
    );