--- CONSULTAS MODULO DE USUARIOS

    -- LISTA DE USUARIOS

    SELECT 
        personas.id_persona id_persona, personas.nombres nombres, personas.a_paterno a_paterno, personas.a_materno a_materno, personas.f_nacimiento f_nacimiento, personas.telefono telefono,
        usuarios.id_usuario id_usuario, usuarios.correo correo, usuarios.estado estado,
        perfiles.perfil perfil
    FROM usuarios
    INNER JOIN personas ON usuarios.id_persona = personas.id_persona
    INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil

    -- UN SOLO USUARIO
    SELECT 
        personas.id_persona id_persona, personas.nombres nombres, personas.a_paterno a_paterno, personas.a_materno a_materno, personas.f_nacimiento f_nacimiento, personas.telefono telefono,
        usuarios.id_usuario id_usuario, usuarios.correo correo, usuarios.estado estado,
        perfiles.perfil perfil,
    FROM usuarios
    INNER JOIN personas ON usuarios.id_persona = personas.id_persona
    INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
    WHERE usuarios.id_usuario = '1'

    -- INSERTAR UN USUARIO
        -- BUSCAMOS SI YA EXISTE O NO
        SELECT 1 existe
        FROM usuarios
        WHERE correo = 'sergio@mail.com'

        -- INSERT DE LA TABLA PERSONAS
        INSERT INTO personas(nombres,a_paterno,a_materno,f_nacimiento,telefono)
        VALUES('','','','','')

        -- INSERT DE LA TABLA USUARIOS
        INSERT INTO usuarios(correo,contrasena,id_persona,id_perfil)
        VALUES('','',(SELECT MAX(id_persona) FROM personas),1)

        -- OBTENER EL ULTIMO USUARIO REGISTRADO
        SELECT MAX(id_usuario) id_usuario FROM usuarios

    -- ACTUALIZAR UN USUARIO

        -- BUSCAMOS EL USUARIO EN BASE A SU ID
        SELECT 1 existe FROM usuarios WHERE id_usuario = ''

        -- UPDATE  de la tabla PERSONAS
        UPDATE personas
        SET nombres = '', a_paterno = '', a_materno = '', f_nacimiento = '', telefono = ''
        WHERE id_persona = ''

        -- UPDATE  de la tabla USUARIOS
        UPDATE usuarios
        SET correo = '', id_perfil = ''
        WHERE id_persona = ''

    -- ELIMINAR UN USUARIO
        -- BUSCAMOS EL USUARIO EN BASE A SU ID
            SELECT 1 existe FROM usuarios WHERE id_usuario = ''

        -- ELIMINAR UN USUARIO (En realidad, solo se les cambia los estados a las tablas de personas y usuarios a false)
            UPDATE personas SET estado = 0
            UPDATE usuarios SET estado = 0


-- MODULO DE LOGIN

    -- BUSCAR UN USUARIO EN BASE A SU ID Y EXTRAER SU MENU
    SELECT 
        personas.id_persona id_persona, personas.nombres nombres, personas.a_paterno a_paterno, personas.a_materno a_materno,
        usuarios.contrasena contrasena,
        perfiles.perfil perfil,
        menu.recurso recurso
    FROM usuarios
    INNER JOIN personas ON usuarios.id_persona = personas.id_persona
    INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
    INNER JOIN perfil_menu ON perfiles.id_perfil = perfil_menu.id_perfil
    INNER JOIN menu ON perfil_menu.id_menu = menu.id_menu
    WHERE usuarios.correo = ''

    -- BUSCAR UN USUARIO PARA METODO DE ACTUALIZAR SU CONTRASEÑA
    SELECT 
        personas.id_persona id_persona, personas.nombres nombres, personas.a_paterno a_paterno, personas.a_materno a_materno,
        usuarios.contrasena contrasena
    FROM usuarios
    INNER JOIN personas ON usuarios.id_persona = personas.id_persona
    WHERE usuarios.correo = ''

    -- ACTUALIZAR CONTRASENA
    UPDATE usuarios
    SET contrasena = ''
    WHERE correo = ''


