insert into client (num_client,nom_client,adresse_client) values 
('CLT001','Norbert','Niort'),
('CLT002','Claude','Clisson'),
('CLT003','Marie','Marseille'),
('CLT004','Paul','Paris'),
('CLT005','Fabien','Fontenay Le Comte');

insert into menu (rang,libelle,url,role,parent_id) values 
('001','Accueil','app_accueil','ROLE_USER',null),
('002','Article','app_article_index','ROLE_USER',null),
('003','Client','app_client','ROLE_USER',null),
('004','Commande','app_commande','ROLE_USER',null),
('005','Parametre','app_parametre','ROLE_USER',null),

('006','Facture','app_facture','ROLE_USER',4),
('007','Devis','app_accueil','ROLE_USER',4),
('008','Avoir','app_accueil','ROLE_USER',4),

('009','User','app_acuser','ROLE_USER',5),
('010','Role','app_accueil','ROLE_USER',5),
('011','Menu','app_accueil','ROLE_USER',5);
