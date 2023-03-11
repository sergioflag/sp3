const app = Vue.createApp({
    components:{
        'ventas-component':ventasComponent,
        'inventario-component':inventarioComponent,
        'inicio-component':inicioComponent,
        'usuarios-component':usuariosComponent,
        'login-component':loginComponent,
    }
})
app.mount('#app')