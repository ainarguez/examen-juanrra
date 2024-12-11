CREATE TABLE aulas
        (
             id INT NOT NULL AUTO_INCREMENT PRIMARY KEY
            ,nombre VARCHAR(20)
            ,letra CHAR(01)
            ,numero INT
            ,planta INT

            ,ip_alta            VARCHAR(15)
            ,fecha_alta         TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ,ip_ult_mod         VARCHAR(15)
            ,fecha_ult_mod      TIMESTAMP DEFAULT CURRENT_TIMESTAMP
      );

INSERT INTO aulas (nombre,letra,numero,planta) VALUES ('Ateka','D' ,'9'  ,'2');
INSERT INTO aulas (nombre,letra,numero,planta) VALUES ('Medusa','D','78','2');
INSERT INTO aulas (nombre,letra,numero,planta) VALUES ('Medusa','I','18','1');
INSERT INTO aulas (nombre,letra,numero,planta) VALUES ('Ateka' ,'D' ,'53' ,'3');
INSERT INTO aulas (nombre,letra,numero,planta) VALUES ('1DAW'  ,'I'  ,'63'  ,'3');
INSERT INTO aulas (nombre,letra,numero,planta) VALUES ('2DAW'  ,'D'  ,'56'  ,'2');
INSERT INTO aulas (nombre,letra,numero,planta) VALUES ('Ateka' ,'I' ,'58' ,'3');
INSERT INTO aulas (nombre,letra,numero,planta) VALUES ('1DAW'  ,'D'  ,'65'  ,'1');