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
   dateDebut    DATE                 not null,
   dateFin      DATE                 not null,
   code         INT4                 not null,
   status       INT4                 not null,
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
      on delete restrict on update restrict;

alter table RESERVE
   add constraint FK_RESERVE_RESERVE2_PERSONNE foreign key (personneId)
      references PERSONNE (id)
      on delete restrict on update restrict;

alter table SALLE
   add constraint FK_SALLE_CORRESPON_TYPESALL foreign key (typeSalleId)
      references TYPESALLE (id)
      on delete restrict on update restrict;