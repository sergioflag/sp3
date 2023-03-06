--DATA MENU
INSERT INTO menu(recurso)
VALUES ('inicio'),('usuarios'),('inventario'),('ventas');

--DATA PERFILES
INSERT INTO perfiles(perfil)
VALUES ('gerente'),('cajero');

--DATA PERFIL-MENU
INSERT INTO perfil_menu(id_menu,id_perfil)
VALUES (1,1),(2,1),(3,1),(4,1),
(1,2),(3,2),(4,2);