drop table RESERVE;
drop table SALLE;
drop table PERSONNE;
drop table TYPESALLE;

create table PERSONNE (
   id          SERIAL               not null,
   isAdmin             BOOLEAN                 not null,
   nom         VARCHAR(255)        not null,
   prenom      VARCHAR(255)        not null,
   login               VARCHAR(255)         not null,
   pwd                  VARCHAR(255)         not null,
   constraint PK_PERSONNE primary key (id),
   constraint UNIQUE_LOGIN unique(login)
);

create table RESERVE (
   id                  SERIAL        not null,
   personneId          INT4          not null,
   salleId             INT4          not null,
   duree        INT4                 not null,
   dateDebut    TIMESTAMP                 not null,
   dateFin      TIMESTAMP                 not null,
   code         INT4                 not null,
   status       INT4                 default 1 not null,
   constraint PK_RESERVE primary key (id,personneId, salleId)
);


create table SALLE (
   id             SERIAL               not null,
   typeSalleId          INT4                 not null,
   numero            VARCHAR(1024)        not null,
   capacite       INT4                 not null,
   constraint PK_SALLE primary key (id)
);

create table TYPESALLE (
   id          SERIAL               not null,
   nom        VARCHAR(1024)        not null,
   constraint PK_TYPESALLE primary key (id)
);

alter table RESERVE
   add constraint FK_RESERVE_RESERVE_SALLE foreign key (salleId)
      references SALLE (id)
      on delete CASCADE on update restrict;

alter table RESERVE
   add constraint FK_RESERVE_RESERVE2_PERSONNE foreign key (personneId)
      references PERSONNE (id)
      on delete CASCADE on update restrict;

alter table SALLE
   add constraint FK_SALLE_CORRESPON_TYPESALL foreign key (typeSalleId)
      references TYPESALLE (id)
      on delete CASCADE on update restrict;



/*insert into reserve (personneID,salleID,duree,dateDebut,dateFin,code,status) values (4,1,30,now(),now() + interval'2 hours',123,1);
insert into reserve (personneID,salleID,duree,dateDebut,dateFin,code,status) values (4,3,30,now() - interval'1 hour',now() - interval'30 minutes',789,1);
insert into reserve (personneID,salleID,duree,dateDebut,dateFin,code,status) values (4,2,30,now() - interval'1 hour',now() + interval'30 minutes',456,2);
insert into reserve (personneID,salleID,duree,dateDebut,dateFin,code,status) values (4,3,30,now(),now() + interval'2 hours',123,1);*/

insert into TYPESALLE(nom) values ('Simple');
insert into TYPESALLE(nom) values('Classique');
insert into TYPESALLE(nom) values ('Audio et web conférence');
insert into TYPESALLE(nom) values('Classique audition');
insert into TYPESALLE(nom) values ('Salle spécifique aux handicap visuels');
insert into TYPESALLE(nom) values('Salle spécifique avec logiciels spécifiques');

/*PROCEDURE TO DELETE RESERVATION 15 minutes after starting if no one in it*/
CREATE OR REPLACE FUNCTION check_reservation(duration interval) RETURNS void AS'
	DELETE FROM reserve where dateDebut <= (now()-duration) and status = 1;' LANGUAGE SQL;