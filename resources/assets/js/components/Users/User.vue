<template>
    <main class="main">
        <!-- Breadcrumb -->
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
        </ol>
        <div class="container-fluid">
            <!-- Ejemplo de tabla Listado -->
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Usuarios
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <select class="form-control col-md-3" v-model="criteria">
                                    <option value="name">Nombre</option>
                                    <option value="email">No de Control</option>
                                    <option value="id_role">Tipo de Usuario</option>
                                    <option value="condition">Estado</option>
                                </select>
                                <input type="text" v-if="criteria =='name' || criteria == 'email'" v-model="search" @keyup.enter="listUser(1,search,criteria)" class="form-control" placeholder="Texto a buscar">
                                <select v-else-if="criteria =='id_role' || criteria == 'condition'" v-model="search" @keyup.enter="listUser(1,search,criteria)" class="form-control">
                                    <optgroup  v-if="criteria == 'id_role'">
                                        <option value="" disabled selected>Seleccionar Tipo</option>
                                        <option value=2>Maestros</option>
                                        <option value=3>Alumnos</option>
                                    </optgroup >
                                    <optgroup  v-else-if="criteria == 'condition'">
                                        <option value="" disabled selected>Seleccionar estado</option>
                                        <option value=1>Activado</option>
                                        <option value=0>Desactivado</option>
                                    </optgroup >
                                </select>
                                <button type="submit" @click="listUser(1,search,criteria)" class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </div>
                    </div>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th>Tipo de Usuario</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="user in arrayUser" :key="user.id">
                                <td v-text="user[1].name"></td>
                                <td v-text="user[1].noControl"></td>
                                <td v-text="user[1].role"></td>
                                <td>
                                    <div v-if="user[1].condition">
                                        <span class="badge badge-success">Activo</span>
                                    </div>
                                    <div v-else>
                                        <span class="badge badge-danger">Desactivado</span>
                                    </div> 
                                </td>
                                <td>
                                    <button v-if="user[1].id_role == 2 && user[1].condition == 1" type="button" @click="openModal('user', 'upTe', user[1])" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button>
                                    <button v-else-if="user[1].id_role == 3 && user[1].condition == 1" type="button" @click="openModal('user', 'upSt', user[1])" class="btn btn-warning btn-sm">
                                        <i class="icon-pencil"></i>
                                    </button>
                                    <template >
                                        <button v-if="user[1].condition" type="button" class="btn btn-danger btn-sm" @click="deactivateUser(user[1].id, user[1].id_role)">
                                            <i class="icon-trash"></i>
                                        </button>
                                         <button v-else type="button" class="btn btn-info btn-sm" @click="activateUser(user[1].id, user[1].id_role)">
                                            <i class="icon-check"></i>
                                        </button>
                                    </template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li class="page-item" v-if="pagination.current_page > 1">
                                <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page - 1,search,criteria);">Ant</a>
                            </li>
                            <li class="page-item" v-for="page in pagesNumber" :key="page" :class="[page == isActived ? 'active' : '']">
                                <a class="page-link" href="#" @click.prevent="changePage(page,search,criteria)" v-text="page"></a>
                            </li>
                            <li class="page-item" v-if="pagination.current_page < pagination.last_page">
                                <a class="page-link" href="#" @click.prevent="changePage(pagination.current_page+1,search,criteria)">Sig</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
            <!-- Fin ejemplo de tabla Listado -->
        </div>
        <div class="modal fade" tabindex="-1" :class="{'show': modal}" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
            <div class="modal-dialog modal-primary modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" v-text="modalTitle"></h4>
                        <button type="button" class="close" @click="closeModal()" aria-label="Close">
                        <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div v-if="id_role == 2" class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label v-if="actionType==1 || actionType==2" class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <label v-else-if="actionType==3" class="col-md-3 form-control-label" for="text-input">Contraseña Actual</label>
                                <div class="col-md-9">
                                    <input v-if="actionType==1 || actionType==2" type="text" v-model="name" class="form-control" placeholder="Nombre del alumno">
                                    <input v-else-if="actionType==3" type="password" v-model="currentPass" class="form-control" placeholder="Contraseña Actual">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label v-if="actionType==1 || actionType==2" class="col-md-3 form-control-label" for="text-input">Número de Control</label>
                                <label v-else-if="actionType==3" class="col-md-3 form-control-label" for="text-input">Contraseña Nueva</label>
                                <div class="col-md-9">
                                    <input v-if="actionType==1 || actionType==2" type="text" v-model="username" class="form-control" placeholder="No. de Control">
                                    <input v-else-if="actionType==3" type="password" v-model="newPass" class="form-control" placeholder="Contraseña Nueva">
                                </div>
                            </div>
                            <div class="form-group row" v-if="actionType==1">
                                <label class="col-md-3 form-control-label" for="text-input">Contraseña</label>
                                <div class="col-md-9">
                                    <input type="password" v-model="password" class="form-control" placeholder="Contraseña">
                                </div>
                            </div>
                            <div class="form-group row" v-if="actionType==3">
                                <label class="col-md-3 form-control-label" for="text-input">Confirmar Contraseña</label>
                                <div class="col-md-9">
                                    <input v-if="actionType==3" type="password" v-model="confirmPass" class="form-control" placeholder="Confirmar Contraseña">
                                </div>
                            </div>
                            <!--<div class="form-group row"  v-if="actionType==1 || actionType==2">
                                <label class="col-md-3 form-control-label" for="text-input">Grupo</label>
                                <div class="col-md-9">
                                    <v-select id="vueSelect" label="name" :options="arrayGroups" v-model="group"></v-select>
                                </div>
                            </div>-->
                            <div v-show="userError" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in userShowErrorMsg" :key="error" v-text="error">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div v-if="id_role == 3" class="modal-body">
                        <form action="" method="post" enctype="multipart/form-data" class="form-horizontal">
                            <div class="form-group row">
                                <label v-if="actionType==1 || actionType==2" class="col-md-3 form-control-label" for="text-input">Nombre</label>
                                <label v-else-if="actionType==3" class="col-md-3 form-control-label" for="text-input">Contraseña actual</label>
                                <div class="col-md-9">
                                    <input v-if="actionType==1 || actionType==2" type="text" v-model="name" class="form-control" placeholder="Nombre del alumno">
                                    <input v-else-if="actionType==3" type="password" v-model="currentPass" class="form-control" placeholder="Contraseña Actual">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label v-if="actionType==1 || actionType==2" class="col-md-3 form-control-label" for="text-input">Número de Control</label>
                                <label v-else-if="actionType==3" class="col-md-3 form-control-label" for="text-input">Contraseña Nueva</label>
                                <div class="col-md-9">
                                    <input v-if="actionType==1 || actionType==2" type="text" v-model="username" class="form-control" placeholder="No. de Control">
                                    <input v-else-if="actionType==3" type="password" v-model="newPass" class="form-control" placeholder="Contraseña Nueva">
                                </div>
                            </div>
                            <div class="form-group row" v-if="actionType==1">
                                <label class="col-md-3 form-control-label" for="text-input">Contraseña</label>
                                <div class="col-md-9">
                                    <input type="password" v-model="password" class="form-control" placeholder="Contraseña">
                                </div>
                            </div>
                            <div class="form-group row" v-if="actionType==3">
                                <label class="col-md-3 form-control-label" for="text-input">Confirmar Contraseña</label>
                                <div class="col-md-9">
                                    <input v-if="actionType==3" type="password" v-model="confirmPass" class="form-control" placeholder="Confirmar Contraseña">
                                </div>
                            </div>
                            <div class="form-group row"  v-if="actionType==1 || actionType==2">
                                <label class="col-md-3 form-control-label" for="text-input">Grupo</label>
                                <div class="col-md-9">
                                    <v-select id="vueSelect" label="name" :options="arrayGroups" v-model="group"></v-select>
                                </div>
                            </div>
                            <div v-show="userError" class="form-group row div-error">
                                <div class="text-center text-error">
                                    <div v-for="error in userShowErrorMsg" :key="error" v-text="error">

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="closeModal()">Cerrar</button>
                        <!-- <button type="button" v-if="actionType==1" class="btn btn-primary" @click="registerStudent()">Guardar</button> -->
                        <button type="button" v-if="actionType==2" class="btn btn-primary" @click="updateUser()">Actualizar</button>
                        <!-- <button type="button" v-if="actionType==3" class="btn btn-primary" @click="pVal()">Cambiar Contraseña</button> -->
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <!--Fin del modal-->
    </main>
</template>

<script>
    export default {
        data(){
            return{

                //Global Variables
                name: '',
                username: '',
                password: '',
                arrayUser: [],
                password: '',
                currentPass: '',
                newPass: '',
                confirmPass:'',

                //Modal Variables
                modal: 0,
                modalTitle: '',
                actionType: 0,
                group: '',
                arrayGroups: [],
                id_role: '',
                id_group: '',
                id_user:'',

                //Validate User
                userError: 0,
                userShowErrorMsg: [],

                //Pagination
                pagination: {
                    'total' : 0,
                    'current_page' : 0,
                    'per_page' : 0,
                    'last_page' : 0,
                    'from' : 0,
                    'to' : 0,
                },
                offset: 3,
                criteria: 'name',
                search: ''
            }
        },
        computed:{

            //Activated(?)
            isActived: function(){
                return this.pagination.current_page;
            },

            //Calculates the pagination elements
            pagesNumber: function(){
                if(!this.pagination.to){
                    return [];
                }

                var from = this.pagination.current_page - this.offset;
                if (from < 1) {
                    from = 1;
                }

                var to = from + (this.offset * 2);
                if (to >= this.pagination.last_page) {
                    to = this.pagination.last_page;
                }

                var pagesArray = [];
                while (from <= to) {
                    pagesArray.push(from);
                    from++;
                }
                return pagesArray;
            }
        },
        methods:{
            listGroups(){
                let me = this;
                axios.get('/admin/getGroups/').then(function (response){
                    //Executed Succesfully
                    me.arrayGroups = response.data.groups;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            listUser(page,search,criteria){
                let me = this;
                var url = '/admin/usuario?page=' + page + '&search=' + search + '&criteria=' + criteria;
                //Categories table registries
                axios.get(url).then(function (response) {
                    var students = Object.keys(response.data.students.data).map(function(key) {
                        return [Number(key), response.data.students.data[key]];
                    });
                    var teachers = Object.keys(response.data.teachers.data).map(function(key) {
                        return [Number(key), response.data.teachers.data[key]];
                    });
                    var users = students.concat(teachers);
                    // handle success
                    me.arrayUser = users;
                    me.pagination = response.data.pagination;
                    console.log(me.pagination);
                })
                .catch(function (error) {
                    // handle error
                    console.log(error);
                });
            },
            changePage(page, search, criteria){
                let me = this;

                //Update the current page
                me.pagination.current_page = page;

                //Send the current page view data request
                me.listUser(page, search, criteria);
            },
            registerUser(){

                if (this.validateUser()) {
                    return;
                }

                let me = this;

                axios.post('/admin/maestro/registrar', {
                    'username': this.username,
                    'password': this.password,
                    'name': this.name,
                }).then(function (response){
                    //Executed Succesfully
                    me.closeModal();
                    Swal.fire({
                    type: 'success',
                    title: 'Éxito!',
                    text: 'Tu registro se ha creado correctamente!',
                    showConfirmButton: false,
                    timer: 2500
                    })
                    me.listUser(1,'','name');
                }).catch(function (error) {
                    console.log(error);
                    Swal.fire({
                    type: 'error',
                    title: 'Ups!',
                    text: 'Algo salió mal',
                    showConfirmButton: false,
                    timer: 2500
                    })
                });

            },
            updateUser(){

                if (this.validateUserUpdate()) {
                    return;
                }

                let me = this;
                // console.log(this);
                if(this.id_role == 2){
                    axios.put('/admin/maestro/actualizar', {
                        'name': this.name,
                        'username': this.username,
                        'id': this.id_user
                    }).then(function (response){
                        //Executed Succesfully
                        me.closeModal();
                        Swal.fire({
                            type: 'success',
                            title: 'Éxito!',
                            text: 'Tu registro se ha actualizado correctamente!',
                            showConfirmButton: false,
                            timer: 2500
                        })
                        me.listUser(1,'','name');
                    }).catch(function (error) {
                    console.log(error);
                    Swal.fire({
                    type: 'error',
                    title: 'Ups!',
                    text: 'Algo salió mal',
                    showConfirmButton: false,
                    timer: 2500
                    })
                });
                }
                else if(this.id_role == 3){
                    axios.put('/admin/alumno/actualizar', {
                        'name': this.name,
                        'username': this.username,
                        'id_group': this.id_group,
                        'id': this.id_user
                    }).then(function (response){
                        //Executed Succesfully
                        me.closeModal();
                        Swal.fire({
                            type: 'success',
                            title: 'Éxito!',
                            text: 'Tu registro se ha actualizado correctamente!',
                            showConfirmButton: false,
                            timer: 2500
                        })
                        me.listUser(1,'','name');
                    }).catch(function (error) {
                        console.log(error);
                        Swal.fire({
                        type: 'error',
                        title: 'Ups!',
                        text: 'Algo salió mal',
                        showConfirmButton: false,
                        timer: 2500
                        })
                    });
                }
            },
            validateUser(){
                this.userError=0;
                this.userShowErrorMsg = [];

                if(!this.name) this.userShowErrorMsg.push('El Nombre del Usuario no puede estar vacío.');
                if(!this.username) this.userShowErrorMsg.push('El número de control/clave no pueden estar vacíos.');
                if(!this.password) this.userShowErrorMsg.push('La contraseña no puede estar vacía.');
                if (this.userShowErrorMsg.length) this.userError = 1;
                
                return this.userError;
            },
            validateUserUpdate(){
                this.userError=0;
                this.userShowErrorMsg = [];

                if(!this.name) this.userShowErrorMsg.push('El Nombre del Usuario no puede estar vacío.');
                if(!this.username) this.userShowErrorMsg.push('El número de control/clave no pueden estar vacíos.');
                if (this.userShowErrorMsg.length) this.userError = 1;
                
                return this.userError;
            },
            closeModal(){
                this.modal = 0;
                this.modalTitle = '';
                this.name = '';
                this.username = '';
                this.password = '';
                this.userError = 0;
            },
            openModal(model, action, data = []){
                switch (model) {
                    case "user":
                    {
                        switch (action) {
                            case 'register':
                            {
                                //Show Modal
                                this.modal = 1;
                                this.modalTitle = 'Registrar Usuario';
                                this.name = '';
                                this.username = '';
                                this.password = '';
                                this.actionType = 1;
                                break;
                            }
                            case 'upSt':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Actualizar Alumno';
                                this.id = data['id'];
                                this.name = data['name'];
                                this.username = data['noControl'];
                                this.id_role = data['id_role'];
                                this.id_group = data['id_group'];
                                this.id_user = data['id_student']
                                if (data['group'] == null) this.group = 'Grupo no asignado'; else this.group = data['group'];
                                this.actionType = 2;
                                break;
                            }
                            case 'upTe':
                            {
                                this.modal = 1;
                                this.modalTitle = 'Actualizar Maestro';
                                this.id = data['id'];
                                this.name = data['name'];
                                this.id_role = data['id_role'];
                                this.id_user = data['id_teacher']
                                this.username = data['noControl'];
                                this.id_role = data['id_role'];                                
                                this.actionType = 2;
                                break;
                            }
                        }
                    }
                }
            },
            deactivateUser(id, id_role){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })

                swalWithBootstrapButtons.fire({
                title: 'Desactivar Usuario',
                text: "¿Está seguro que quiere desactivar este usuario?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;

                    axios.post('/admin/usuario/toggle', {
                        'id': id,
                        'id_role': id_role
                    }).then(function (response){
                        //Executed Succesfully
                        me.listUser(1,'','name');

                        swalWithBootstrapButtons.fire(
                        'Eliminado Satisfactoriamente!',
                        'El registro ha sido desactivado satisfactoriamente.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                        Swal.fire({
                        type: 'error',
                        title: 'Ups!',
                        text: 'Algo salió mal',
                        showConfirmButton: false,
                        timer: 2500
                        })
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            },
            activateUser(id, id_role){
                const swalWithBootstrapButtons = Swal.mixin({
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-danger',
                buttonsStyling: false,
                })
                swalWithBootstrapButtons.fire({
                title: 'Activar Usuario',
                text: "¿Está seguro que quiere activar de nuevo este usuario?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
                }).then((result) => {
                if (result.value) {

                    let me = this;

                    axios.post('/admin/usuario/toggle', {
                        'id': id,
                        'id_role': id_role
                    }).then(function (response){
                        //Executed Succesfully
                        me.listUser(1,'','name');
                        swalWithBootstrapButtons.fire(
                        'Activado Satisfactoriamente!',
                        'El registro ha sido activado satisfactoriamente.',
                        'success'
                        )
                    }).catch(function (error) {
                        console.log(error);
                        Swal.fire({
                        type: 'error',
                        title: 'Ups!',
                        text: 'Algo salió mal',
                        showConfirmButton: false,
                        timer: 2500
                        })
                    });

                } else if (
                    // Read more about handling dismissals
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                }
                })
            },
        },
        mounted() {
            this.listUser(1,this.search,this.criteria);
            this.listGroups();
       }
    }
</script>
<style>
    .modal-content{
        width: 100% !important;
        position: absolute !important;
    }
    .show{
        display: list-item !important;
        opacity: 1 !important;
        position: absolute !important;
        background-color: #3c29297a !important;
    }
    .div-error{
        display: flex;
        justify-content: center;
    }
    .text-error{
        color: red !important;
        font-weight: bold;
    }
</style>