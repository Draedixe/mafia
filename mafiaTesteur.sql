INSERT INTO `fos_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `points`) VALUES
(1, 'Draedixe', 'draedixe', 'aa.kk@hh.fr', 'aa.kk@hh.fr', 1, '8kgmr09sc884s8cogwss0oowsw48cc8', 'vlHtdR7zYhCvzAI0Bik9zt6MEkryQGiwerhI3FapeWSR5SPRWKWzU1mRPk6nEWINNH/IgkulX7ojZKX6MHQkgQ==', '2015-01-22 15:39:08', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 0);

INSERT INTO `crime` (`id`, `nomCrime`) VALUES
(1, 'Assassinat'),
(2, 'Pokemon');

INSERT INTO `role` (`id`, `nomRole`, `description`, `enumRole`, `enumFaction`, `roleUnique`) VALUES
(1, 'Citoyen', 'cccc', 5, 0, 0),
(2, 'Mafioso', 'ff', 30, 1, 0);

INSERT INTO `crimes_role` (`role_id`, `crime_id`) VALUES
(2, 1);

INSERT INTO `categorie` (`id`, `nomCategorie`) VALUES
(1, 'Kill'),
(2, 'Bénin');

INSERT INTO `categories_role` (`role_id`, `categorie_id`) VALUES
(1, 2),
(2, 1);