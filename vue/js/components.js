const loaderComponent={
    template:'#loader-template'
}

const sidebarComponent={
    template:'#sidebar-template',
    data:function(){
        return {
            usuario:{
                nombres:'',
                a_paterno:'',
                a_materno:'',
                perfil:'',
            },
            menu:[]
        }
    },
    methods:{
        loadmenu:function(){
            // Se envia el token y  extraen la data
            fetch('http://localhost/sp3/sp3/controllers/auth/_api.php',{
                method:'GET',
                headers:{
                    'content-type':'application/json',
                    token:localStorage['token']
                }
            })
            .then(response=>response.json())
            .then((response)=>{

                if(response.error === false){

                    //Se guarda el menu en las variables locales
   
                   console.log(response)
                   this.usuario = response.data.usuario
                   this.menu = response.data.menu
   
                   //Se guarda la url actual de la vista
                   let url = window.location.pathname.split('/')
                   url=url[url.length-1]
                   
                   //Se crea un array con las vistas extraidas de la DB
                   let pages = this.menu.map((element)=>{
                       return element+'.html'
                   })
   
                   //Se valida que la URL de la vista actual exista dentro del array
   
                   if(pages.includes(url)===false){
                       window.location.replace('http://localhost/sp3/sp3/vue/views/inicio.html')
                       console.log('Acceso restringido')
                    }
                }else{
                    localStorage.removeItem('token')
                    window.location.replace('http://localhost/sp3/sp3/vue/views/login.html')

                }

            })
        },
        cerrar:function(){
            localStorage.removeItem('token')
            window.location.replace('http://localhost/sp3/sp3/vue/views/login.html')
        }
    },
    created:function(){
        this.loadmenu()
    }
}

const inicioComponent={
    template:'#inicio-template',
    components:{
        'sidebar-component':sidebarComponent
    }
}

const usuariosComponent={
    template:'#usuarios-template',
    components:{
        'sidebar-component':sidebarComponent
    },
    data:function(){
        return{
            listaUsuarios:[
                { id_persona:"", nombres:"", a_paterno:"", a_materno:"", f_nacimiento:"", telefono:"", id_usuario:"", correo:"", estado:"", perfil:""
                }
            ],
            perfiles:[
                {id_perfil:'1',perfil:'gerente'},
                {id_perfil:'2',perfil:'cajero'}
            ],
            formulario:{
                nombres:'',
                a_paterno:'',
                a_materno:'',
                f_nacimiento:'',
                telefono:'',
                correo:'',
                contrasena:'',
                id_perfil:''
            },
            elementos:{
                showLista:false,
                showFormContrasena:false,
                crear:false
            },
            metodo:''
        }
    },
    methods:{
        cargarLista:function(){
            fetch('http://localhost/sp3/sp3/controllers/usuarios/_api.php',{
                method:'GET',
                headers:{
                    'content-type':'application/json'
                }
            })
            .then(respuesta=>respuesta.json())
            .then((respuesta)=>{
                console.log(respuesta)
                this.listaUsuarios = respuesta.usuarios
            })
        },
        btn_cancelar:function(){
            this.elementos.showLista = true;
            this.elementos.showFormContrasena = false
            this.crear = false

        },
        btn_nuevoUsuario:function(){
            this.elementos.showFormContrasena = false
            this.elementos.showLista = false
            this.crear = true
            this.metodo = 'Registrar un nuevo usuario'
        },
        btn_actualizarContrasena:function(item){
            this.elementos.showFormContrasena = true
            this.elementos.showLista = false;
            this.crear = false
            this.metodo = 'Actualizar usuario'
        },
        cargarUsuario:function(item){
            this.elementos.showLista = false;
            this.elementos.showFormContrasena = false;
            this.crear = false;
            this.metodo = 'Actualizar registro'

            this.formulario = item

        },
        limpiar:function(){
            this.formulario.nombres = ''
            this.formulario.a_paterno = ''
            this.formulario.a_materno = ''
            this.formulario.f_nacimiento = ''
            this.formulario.telefono = ''
            this.formulario.id_perfil = ''
            this.formulario.correo = ''
            this.formulario.contrasena = ''
        },
        guardarUsuario:function(){

            let item = JSON.parse(JSON.stringify(this.formulario))
            const formularioData = Object.values(item)

            console.log(item)

            if(formularioData.includes('')){
                swal({
                    icon:'warning',
                    text:'Los campos están incompletos'
                })
            }else{

                swal({
                    title:'¿Desea proceder con el registro del usuario?',
                    icon:'warning',
                    buttons:true,
                    dangerMode:true
                })
                .then((guardar)=>{
                    if(guardar){

                        fetch('http://localhost/sp3/sp3/controllers/usuarios/_api.php',{
                            method:'POST',
                            body:JSON.stringify(item),
                            headers:{
                                'content-type':'application/json'
                            }
                        })
                        .then(respuesta=>respuesta.json())
                        .then((respuesta)=>{
                            console.log(respuesta)
                            if(respuesta.error === false){
                                item.id_usuario=respuesta.id_usuario
                                this.listaUsuarios.push(item)
                                swal({
                                    icon:'succes',
                                    text:respuesta.mensaje
                                })
                                this.limpiar()
                                this.btn_cancelar()
                            }else{
                                swal({
                                    icon:'error',
                                    text:respuesta.mensaje
                                })
                                this.limpiar()
                                this.btn_cancelar()
                            }
                        })

                    }else{
                        this.btn_cancelar()
                    }
                })

            }

        },
        actualizarUsuario(){

            let item = JSON.parse(JSON.stringify(this.formulario))

            const index = this.listaUsuarios.findIndex((element)=>element.id_usuario === item.id_usuario)
            let id = item.id_persona

            const formularioData = Object.values(item)

            if(formularioData.includes('')){
                swal({
                    icon:'warning',
                    text:'Los campos están incompletos'
                })
            }else{

                swal({
                    title:'¿Desea proceder con los cambios del registro del usuario?',
                    icon:'warning',
                    buttons:true,
                    dangerMode:true
                })
                .then((actualizar)=>{
                    if(actualizar){

                        fetch(`http://localhost/sp3/sp3/controllers/usuarios/_api.php?id=${id}`,{
                            method:'PUT',
                            body:JSON.stringify(item),
                            headers:{
                                'content-type':'application/json'
                            }
                        })
                        .then(respuesta=>respuesta.json())
                        .then((respuesta)=>{
                            console.log(respuesta)
                            if(respuesta.error === false){
                                if(item.id_perfil === '1'){
                                    item.perfil = 'gerente'
                                }else{
                                    item.perfil = 'cajero'
                                }

                                this.listaUsuarios[index] = item

                                this.limpiar()
                                this.btn_cancelar()
                            }else{
                                swal({
                                    icon:'error',
                                    text:respuesta.mensaje
                                })
                                this.limpiar()
                                this.btn_cancelar()
                            }
                        })

                    }else{
                        this.btn_cancelar()
                    }
                })

            }
        },
        eliminarUsuario(item){

            item = JSON.parse(JSON.stringify(item))

            const index = this.listaUsuarios.findIndex((element)=>element.id_usuario === item.id_usuario)
            let id = item.id_persona

            swal({
                title:'¿Desea proceder con la eliminación del registro?',
                icon:'warning',
                buttons:true,
                dangerMode:true
            })
            .then((eliminar)=>{
                if(eliminar){

                    fetch(`http://localhost/sp3/sp3/controllers/usuarios/_api.php?id=${id}`,{
                        method:'DELETE',
                        headers:{
                            'content-type':'application/json'
                        }
                    })
                    .then(respuesta=>respuesta.json())
                    .then((respuesta)=>{
                        console.log(respuesta)

                        if(respuesta.error === false){
                            item.estado='0'
                            this.listaUsuarios[index] = item

                            swal({
                                icon:'success',
                                text:respuesta.mensaje
                            })
                            this.limpiar()
                            this.btn_cancelar()
                        }else{
                            swal({
                                icon:'error',
                                text:respuesta.mensaje
                            })
                            this.limpiar()
                            this.btn_cancelar()
                        }
                    })

                }else{
                    this.btn_cancelar()
                }
            })

        }
    },
    created:function(){
        this.cargarLista()
        this.elementos.showFormContrasena = false
        this.elementos.showLista = true
    }
}

const inventarioComponent={
    template:'#inventario-template',
    components:{
        'sidebar-component':sidebarComponent
    }
}

const ventasComponent={
    template:'#ventas-template',
    components:{
        'sidebar-component':sidebarComponent
    }
}


const loginComponent={
    template:'#login-template',
    components:{
        'loader-component':loaderComponent
    },
    data:function(){
        return{
            usuario:{
                correo:'',
                contrasena:''
            },
            elementos:{
                showLoader:false,
            }
        }  
    },
    methods:{
        login:function(){
            let item = JSON.parse(JSON.stringify(this.usuario))
            const itemData = Object.values(item)

            if(itemData.includes('')){

                swal({
                    icon:'warning',
                    text:'Los campos están incompletos'
                })

            }else{
                fetch('http://localhost/sp3/sp3/controllers/auth/_api.php',{
                    method:'POST',
                    body:JSON.stringify(item),
                    headers:{
                        'content-type':'application/json'
                    }
                })
                .then(response=>response.json())
                .then((response)=>{
                    console.log(response)
                    if(response.error === false){

                        this.elementos.showLoader = true

                        setTimeout(() => {                            
                            localStorage.setItem('token',response.token)
                            window.location.replace('http://localhost/sp3/sp3/vue/views/inicio.html')
                            this.limpiar()
                            this.elementos.showLoader = false
                        }, 1500);

                    }else{
                        swal({
                            icon:'warning',
                            text: response.mensaje
                        })
                    }
                })

                this.limpiar()
            }
        },
        validarSesion:function(){

            if(localStorage['token']!==undefined){
                window.location.replace('http://localhost/sp3/sp3/vue/views/inicio.html')
            }else{
                this.elementos.showLoader = true
                setTimeout(() => {
                    this.elementos.showLoader = false           
                }, 1500);
            }
        },
        limpiar:function(){
            this.usuario.correo = ''
            this.usuario.contrasena = ''
        }

    },
    created(){
        this.validarSesion()
    }
}

