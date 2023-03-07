--- CONSULTAS MODULO DE USUARIOS

    -- LISTA DE USUARIOS

    SELECT 
        personas.id_persona id_persona, personas.nombres nombres, personas.a_paterno a_paterno, personas.a_materno a_materno, personas.f_nacimiento f_nacimiento, personas.telefono telefono,
        usuarios.id_usuario id_usuario, usuarios.correo correo, usuarios.estado estado,
        perfiles.perfil perfil,
        menu.recurso recurso
    FROM usuarios
    INNER JOIN personas ON usuarios.id_persona = personas.id_persona
    INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
    INNER JOIN perfil_menu ON perfiles.id_perfil = perfil_menu.id_perfil
    INNER JOIN menu ON perfil_menu.id_menu = menu.id_menu

