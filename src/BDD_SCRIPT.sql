drop table NIVEAU;

drop table PERSONNE;

drop table RESERVE;

drop table SALLE;

drop table TYPESALLE;

create table NIVEAU (
   NIVEAU_ID            SERIAL               not null,
   NIVEAU_NOM           VARCHAR(1024)        not null,
   constraint PK_NIVEAU primary key (NIVEAU_ID)
);

create table PERSONNE (
   PERSONNE_ID          SERIAL               not null,
   NIVEAU_ID            INT4                 not null,
   PERSONNE_NOM         VARCHAR(1024)        not null,
   PERSONNE_PRENOM      VARCHAR(1024)        not null,
   constraint PK_PERSONNE primary key (PERSONNE_ID)
);

create table RESERVE (
   PERSONNE_ID          INT4                 not null,
   SALLE_ID             INT4                 not null,
   RESERVE_DUREE        INT4                 not null,
   RESERVE_DATEDEBUT    DATE                 not null,
   RESERVE_DATEFIN      DATE                 not null,
   RESERVE_CODE         INT4                 not null,
   RESERVE_STATUS       INT4                 not null,
   constraint PK_RESERVE primary key (PERSONNE_ID, SALLE_ID)
);


create table SALLE (
   SALLE_ID             SERIAL               not null,
   TYPESALE_ID          INT4                 not null,
   SALLE_NUM            VARCHAR(1024)        not null,
   SALLE_CAPACITE       INT4                 not null,
   constraint PK_SALLE primary key (SALLE_ID)
);

create table TYPESALLE (
   TYPESALE_ID          SERIAL               not null,
   TYPESALLE_NOM        VARCHAR(1024)        not null,
   constraint PK_TYPESALLE primary key (TYPESALE_ID)
);

alter table PERSONNE
   add constraint FK_PERSONNE_EST_DE_NI_NIVEAU foreign key (NIVEAU_ID)
      references NIVEAU (NIVEAU_ID)
      on delete restrict on update restrict;

alter table RESERVE
   add constraint FK_RESERVE_RESERVE_SALLE foreign key (SALLE_ID)
      references SALLE (SALLE_ID)
      on delete restrict on update restrict;

alter table RESERVE
   add constraint FK_RESERVE_RESERVE2_PERSONNE foreign key (PERSONNE_ID)
      references PERSONNE (PERSONNE_ID)
      on delete restrict on update restrict;

alter table SALLE
   add constraint FK_SALLE_CORRESPON_TYPESALL foreign key (TYPESALE_ID)
      references TYPESALLE (TYPESALE_ID)
      on delete restrict on update restrict;

